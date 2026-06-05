<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class Barang_Import_lib
{
    private $CI;

    private $priceFields = [
        'retail',
        'grosir',
        'grosir_10',
        'het_jawa',
        'indo_barat',
        'special_price',
        'barang_x',
    ];

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function preview(array $file, string $id_perusahaan): array
    {
        try {
            $this->validateUpload($file);

            $excel    = $this->parseFile($file['tmp_name']);
            $dbResult = $this->getBarangDB($id_perusahaan);
            $db       = $dbResult['items'];

            $result = [];

            foreach ($excel as $row) {
                $kode = $row['kode'];

                if (!empty($row['duplicate'])) {
                    $result[] = [
                        'kode'    => $kode,
                        'status'  => 'duplicate',
                        'errors'  => ['Duplikat dalam file'],
                        'changes' => [],
                        'excel'   => $row,
                        'db'      => null,
                    ];
                    continue;
                }

                $errors = $this->validateRow($row);

                if (isset($db[$kode]) && empty($db[$kode]['deleted_at'])) {
                    $dbRow        = $db[$kode];
                    $changeFields = $this->detectChanges($row, $dbRow);

                    if (!empty($errors)) {
                        $status = 'error';
                    } elseif (!empty($changeFields)) {
                        $status = 'update';
                    } else {
                        $status = 'no_change';
                    }

                    $result[] = [
                        'kode'    => $kode,
                        'status'  => $status,
                        'errors'  => $errors,
                        'changes' => $changeFields,
                        'excel'   => $row,
                        'db'      => $dbRow,
                    ];
                } else {
                    $result[] = [
                        'kode'    => $kode,
                        'status'  => !empty($errors) ? 'error' : 'insert',
                        'errors'  => $errors,
                        'changes' => [],
                        'excel'   => $row,
                        'db'      => null,
                    ];
                }
            }

            $summary = $this->buildSummary($result);

            return [
                'success' => true,
                'data'    => [
                    'summary'   => $summary,
                    'items'     => $result,
                    'duplicate' => $summary['duplicate'],
                ],
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    public function import(array $file, string $id_perusahaan, string $id_user): array
    {
        try {
            $this->validateUpload($file);

            $excel    = $this->parseFile($file['tmp_name']);
            $dbResult = $this->getBarangDB($id_perusahaan);
            $db       = $dbResult['items'];

            $this->CI->db->trans_begin();

            $insert  = [];
            $update  = 0;
            $skipped = 0;

            foreach ($excel as $row) {
                $kode = $row['kode'];

                if (!empty($row['duplicate'])) {
                    $skipped++;
                    continue;
                }

                $errors = $this->validateRow($row);
                if (!empty($errors)) {
                    $skipped++;
                    continue;
                }

                $data = $this->prepareData($row, $id_perusahaan, $id_user);

                if (isset($db[$kode])) {
                    if (!empty($db[$kode]['deleted_at'])) {
                        $data['deleted_at'] = null;

                        $this->CI->db
                            ->where('id', $db[$kode]['id'])
                            ->update('tb_barang', $data);

                        $update++;
                    } else {
                        if ($this->doUpdate($db[$kode], $data)) {
                            $update++;
                        } else {
                            $skipped++;
                        }
                    }
                } else {
                    $data['deleted_at'] = null;
                    $insert[] = $data;
                }
            }

            if (!empty($insert)) {
                $this->CI->db->insert_batch('tb_barang', $insert);
            }

            if ($this->CI->db->trans_status() === false) {
                $this->CI->db->trans_rollback();

                return [
                    'success' => false,
                    'error'   => 'Transaksi database gagal',
                ];
            }

            $this->CI->db->trans_commit();

            return [
                'success' => true,
                'data'    => [
                    'inserted' => count($insert),
                    'updated'  => $update,
                    'skipped'  => $skipped,
                ],
            ];
        } catch (\Throwable $e) {
            if ($this->CI->db->trans_status() === false) {
                $this->CI->db->trans_rollback();
            }

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    private function parseFile(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);

        if ($reader instanceof \PhpOffice\PhpSpreadsheet\Reader\Csv) {
            $sample    = file_get_contents($filePath);
            $delimiter = substr_count($sample, ';') > substr_count($sample, ',') ? ';' : ',';
            $reader->setDelimiter($delimiter);
        }

        $spreadsheet = $reader->load($filePath);
        $sheet       = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        $headerRowIndex = null;

        foreach ($sheet as $i => $row) {
            if (strtoupper(trim($row[0] ?? '')) === 'NO') {
                $headerRowIndex = $i;
                break;
            }
        }

        if ($headerRowIndex === null) {
            throw new \Exception('Format file tidak valid. Baris header (No, Kode, Barang, ...) tidak ditemukan.');
        }

        $header = $sheet[$headerRowIndex];
        $this->validateHeader($header);

        $result      = [];
        $kodeCounter = [];

        foreach ($sheet as $i => $row) {
            if ($i <= $headerRowIndex) {
                continue;
            }

            if (!isset($row[1]) || trim((string) $row[1]) === '') {
                continue;
            }

            $kode = $this->normalize($row[1]);
            $kode = trim(preg_replace('/\s+BARANG\s*X\s*$/i', '', $kode));

            $item = [
                'row_number'     => $i + 1,
                'kode'           => $kode,
                'nama'           => trim((string) ($row[2] ?? '')),
                'keterangan'     => trim((string) ($row[3] ?? '')),
                'size'           => trim((string) ($row[4] ?? '')),
                'satuan'         => trim((string) ($row[5] ?? '')),
                'kelipatan'      => (int) ($row[6] ?? 0),
                'kategori_label' => $this->normalizeKategori($row[7] ?? ''),

                'retail'         => $this->parseHarga($row[8]  ?? 0),
                'grosir'         => $this->parseHarga($row[9]  ?? 0),
                'grosir_10'      => $this->parseHarga($row[10] ?? 0),
                'het_jawa'       => $this->parseHarga($row[11] ?? 0),
                'indo_barat'     => $this->parseHarga($row[12] ?? 0),
                'special_price'  => $this->parseHarga($row[13] ?? 0),
                'barang_x'       => $this->parseHarga($row[14] ?? 0),

                'duplicate'      => false,
            ];

            $result[] = $item;

            if (!isset($kodeCounter[$kode])) {
                $kodeCounter[$kode] = 0;
            }

            $kodeCounter[$kode]++;
        }

        foreach ($result as &$item) {
            if (($kodeCounter[$item['kode']] ?? 0) > 1) {
                $item['duplicate'] = true;
            }
        }
        unset($item);

        return $result;
    }

    private function parseHarga($val): ?float
    {
        if ($val === null || $val === '') {
            return null;
        }

        if (is_string($val)) {
            $val = str_replace(['Rp', ' ', "\xc2\xa0"], '', $val);
            $val = trim($val);
        }

        if ($val === '' || $val === null) {
            return null;
        }

        if (preg_match('/^\d{1,3}(\.\d{3})+,\d+$/', (string) $val)) {
            return (float) str_replace(['.', ','], ['', '.'], $val);
        }

        if (preg_match('/^\d{1,3}(\.\d{3})+$/', (string) $val)) {
            return (float) str_replace('.', '', $val);
        }

        if (preg_match('/^\d+,\d+$/', (string) $val)) {
            return (float) str_replace(',', '.', $val);
        }

        if (is_numeric($val)) {
            return (float) $val;
        }

        return null;
    }

    private function normalize($val): string
    {
        $val = preg_replace('/[[:^print:]]/', '', (string) $val);
        $val = preg_replace('/\s+/', ' ', $val);

        return strtoupper(trim($val));
    }

    private function validateHeader(array $header): void
    {
        $expected = [
            'No',
            'Kode',
            'Barang',
            'Keterangan',
            'Size',
            'Satuan',
            'Kelipatan',
            'Kategori',
            'Retail',
            'Grosir',
            'Grosir_10',
            'HET_Jawa',
            'Indo_Barat',
            'SP',
            'Brg X',
        ];

        foreach ($expected as $i => $col) {
            $actual = trim((string) ($header[$i] ?? ''));

            if (strtoupper($actual) !== strtoupper($col)) {
                throw new \Exception(
                    'Format file tidak sesuai template. ' .
                    'Kolom ke-' . ($i + 1) . ' harus "' . $col . '", ditemukan "' . $actual . '". ' .
                    'Silahkan unduh template kembali.'
                );
            }
        }
    }

    private function normalizeKategori($val): string
    {
        $val = strtoupper(trim((string) $val));

        if (in_array($val, ['NORMAL'], true)) {
            return 'NORMAL';
        }

        if (in_array($val, ['SPECIAL PRICE', 'SPECIAL_PRICE', 'SPECIALPRICE', 'SP'], true)) {
            return 'SPECIAL PRICE';
        }

        if (in_array($val, ['BARANG X', 'BARANG_X', 'BRG X', 'BRGX'], true)) {
            return 'BARANG X';
        }

        return $val;
    }

    private function validateRow(array $row): array
    {
        $errors = [];

        $validSize = [
            'S',
            'M',
            'L',
            'XL',
            'XXL',
            'XXXL',
            'XXXXL',
            'S/M',
            'L/XL',
            'M/L',
            'XL/XXL',
            'ALL SIZE',
        ];

        $validSatuan = ['PCK', 'PCS', 'BOX', 'PSG'];
        $validKategori = ['NORMAL', 'SPECIAL PRICE', 'BARANG X'];

        if (empty($row['kode'])) {
            $errors[] = 'Kode kosong';
        }

        if (empty($row['nama'])) {
            $errors[] = 'Nama kosong';
        }

        $size = strtoupper(trim((string) ($row['size'] ?? '')));
        if ($size === '') {
            $errors[] = 'Size kosong';
        } elseif (!in_array($size, $validSize, true)) {
            $errors[] = 'Size tidak valid (' . implode('/', $validSize) . ')';
        }

        $satuan = strtoupper(trim((string) ($row['satuan'] ?? '')));
        if ($satuan === '') {
            $errors[] = 'Satuan kosong';
        } elseif (!in_array($satuan, $validSatuan, true)) {
            $errors[] = 'Satuan tidak valid (Pck/Pcs/Box/Psg)';
        }

        $kelipatan = (int) ($row['kelipatan'] ?? 0);
        if ($kelipatan < 1 || $kelipatan > 1000) {
            $errors[] = 'Kelipatan harus antara 1 - 1000';
        }

        $kategoriLabel = strtoupper(trim((string) ($row['kategori_label'] ?? '')));

        if ($kategoriLabel === '') {
            $errors[] = 'Kategori kosong';
        } elseif (!in_array($kategoriLabel, $validKategori, true)) {
            $errors[] = 'Kategori tidak valid (Normal / Special Price / Barang X)';
        }

        $retail        = (float) ($row['retail'] ?? 0);
        $grosir        = (float) ($row['grosir'] ?? 0);
        $grosir_10     = (float) ($row['grosir_10'] ?? 0);
        $het_jawa      = (float) ($row['het_jawa'] ?? 0);
        $indo_barat    = (float) ($row['indo_barat'] ?? 0);
        $special_price = (float) ($row['special_price'] ?? 0);
        $barang_x      = (float) ($row['barang_x'] ?? 0);

        if ($kategoriLabel === 'BARANG X') {
            $adaHargaLain =
                $retail > 0 ||
                $grosir > 0 ||
                $grosir_10 > 0 ||
                $het_jawa > 0 ||
                $indo_barat > 0 ||
                $special_price > 0;

            if ($barang_x <= 0) {
                $errors[] = 'Kategori Barang X wajib mengisi harga Barang X';
            }

            if ($adaHargaLain) {
                $errors[] = 'Kategori Barang X hanya boleh isi kolom harga Barang X';
            }
        } else {
            if ($barang_x > 0) {
                $errors[] = 'Kategori Normal/Special Price tidak boleh isi kolom Barang X';
            }
        }

        if ($kategoriLabel === 'SPECIAL PRICE' && $special_price <= 0) {
            $errors[] = 'Kategori Special Price wajib mengisi kolom SP';
        }

        return $errors;
    }

    private function validateUpload(array $file): void
    {
        if (empty($file)) {
            throw new \Exception('File tidak dikirim');
        }

        if (isset($file['error']) && $file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Upload file gagal. Kode error: ' . $file['error']);
        }

        if (empty($file['tmp_name'])) {
            throw new \Exception('File kosong');
        }

        if (!file_exists($file['tmp_name'])) {
            throw new \Exception('File upload tidak ditemukan di server');
        }

        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            throw new \Exception('Ukuran file maksimal 5MB');
        }

        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));

        if (!in_array($ext, ['xlsx', 'xls', 'csv'], true)) {
            throw new \Exception('Format file tidak didukung. Gunakan xlsx, xls, atau csv');
        }
    }

    private function getBarangDB(string $id_perusahaan): array
    {
        if (empty($id_perusahaan)) {
            throw new \Exception('ID Perusahaan tidak dikirim');
        }

        $perusahaan = $this->CI->db
            ->get_where('tb_perusahaan', ['id' => $id_perusahaan])
            ->row();

        if (!$perusahaan) {
            throw new \Exception('Perusahaan tidak ditemukan');
        }

        $rows = $this->CI->db
            ->get_where('tb_barang', ['id_perusahaan' => $id_perusahaan])
            ->result();

        $result = [];

        foreach ($rows as $r) {
            $kode = $this->normalize($r->kode_artikel);

            if ((int) $r->kategori === 1) {
                $kategoriLabel = 'BARANG X';
            } elseif ((float) $r->special_price > 0) {
                $kategoriLabel = 'SPECIAL PRICE';
            } else {
                $kategoriLabel = 'NORMAL';
            }

            $result[$kode] = [
                'id'             => $r->id,
                'kode'           => $kode,
                'nama'           => $r->nama_artikel,
                'keterangan'     => $r->keterangan,
                'size'           => $r->size,
                'satuan'         => $r->satuan,
                'kelipatan'      => $r->kelipatan ?? 1,
                'kategori'       => $r->kategori,
                'kategori_label' => $kategoriLabel,
                'retail'         => $r->retail,
                'grosir'         => $r->grosir,
                'grosir_10'      => $r->grosir_10,
                'het_jawa'       => $r->het_jawa,
                'indo_barat'     => $r->indo_barat,
                'special_price'  => $r->special_price,
                'barang_x'       => $r->barang_x,
                'deleted_at'     => $r->deleted_at,
            ];
        }

        return [
            'items' => $result,
        ];
    }

    private function detectChanges(array $excelRow, array $dbRow): array
    {
        $changed = [];

        $textFields = [
            'kode',
            'nama',
            'keterangan',
            'size',
            'satuan',
        ];

        foreach ($textFields as $field) {
            $excelValue = $this->normalize($excelRow[$field] ?? '');
            $dbValue    = $this->normalize($dbRow[$field] ?? '');

            if ($excelValue !== $dbValue) {
                $changed[] = $field;
            }
        }

        if ((int) ($excelRow['kelipatan'] ?? 0) !== (int) ($dbRow['kelipatan'] ?? 0)) {
            $changed[] = 'kelipatan';
        }

        foreach ($this->priceFields as $field) {
            $excelValue = (float) ($excelRow[$field] ?? 0);
            $dbValue    = (float) ($dbRow[$field] ?? 0);

            if (abs($excelValue - $dbValue) > 0.0001) {
                $changed[] = $field;
            }
        }

        $excelKategori = $this->mapKategoriToDb($excelRow['kategori_label'] ?? 'NORMAL');
        $dbKategori    = (int) ($dbRow['kategori'] ?? 0);

        if ($excelKategori !== $dbKategori) {
            $changed[] = 'kategori';
        }

        return array_values(array_unique($changed));
    }

    private function mapKategoriToDb(string $kategoriLabel): int
    {
        return strtoupper(trim($kategoriLabel)) === 'BARANG X' ? 1 : 0;
    }

    private function prepareData(array $row, string $id_perusahaan, string $id_user): array
    {
        return [
            'kode_artikel'  => $row['kode'],
            'nama_artikel'  => $row['nama'],
            'keterangan'    => $row['keterangan'],
            'size'          => $row['size'],
            'satuan'        => $row['satuan'],
            'kelipatan'     => (int) $row['kelipatan'],

            'retail'        => (float) ($row['retail'] ?? 0),
            'grosir'        => (float) ($row['grosir'] ?? 0),
            'grosir_10'     => (float) ($row['grosir_10'] ?? 0),
            'het_jawa'      => (float) ($row['het_jawa'] ?? 0),
            'indo_barat'    => (float) ($row['indo_barat'] ?? 0),
            'special_price' => (float) ($row['special_price'] ?? 0),
            'barang_x'      => (float) ($row['barang_x'] ?? 0),

            'kategori'      => $this->mapKategoriToDb($row['kategori_label'] ?? 'NORMAL'),
            'id_perusahaan' => $id_perusahaan,
            'updated_at'    => date('Y-m-d'),
            'id_user'       => $id_user,
        ];
    }

    private function doUpdate(array $dbRow, array $data): bool
    {
        if (!$this->hasDataChanges($dbRow, $data)) {
            return false;
        }

        $this->CI->db
            ->where('id', $dbRow['id'])
            ->update('tb_barang', $data);

        return true;
    }

    private function hasDataChanges(array $dbRow, array $data): bool
    {
        $textMap = [
            'kode_artikel' => 'kode',
            'nama_artikel' => 'nama',
            'keterangan'   => 'keterangan',
            'size'         => 'size',
            'satuan'       => 'satuan',
        ];

        foreach ($textMap as $dataField => $dbField) {
            $newValue = $this->normalize($data[$dataField] ?? '');
            $oldValue = $this->normalize($dbRow[$dbField] ?? '');

            if ($newValue !== $oldValue) {
                return true;
            }
        }

        if ((int) ($data['kelipatan'] ?? 0) !== (int) ($dbRow['kelipatan'] ?? 0)) {
            return true;
        }

        foreach ($this->priceFields as $field) {
            $newValue = (float) ($data[$field] ?? 0);
            $oldValue = (float) ($dbRow[$field] ?? 0);

            if (abs($newValue - $oldValue) > 0.0001) {
                return true;
            }
        }

        if ((int) ($data['kategori'] ?? 0) !== (int) ($dbRow['kategori'] ?? 0)) {
            return true;
        }

        return false;
    }

    private function buildSummary(array $items): array
    {
        $summary = [
            'total'     => 0,
            'insert'    => 0,
            'update'    => 0,
            'no_change' => 0,
            'error'     => 0,
            'duplicate' => 0,
        ];

        foreach ($items as $item) {
            $summary['total']++;

            $status = $item['status'] ?? '';

            if (array_key_exists($status, $summary)) {
                $summary[$status]++;
            }
        }

        return $summary;
    }
}