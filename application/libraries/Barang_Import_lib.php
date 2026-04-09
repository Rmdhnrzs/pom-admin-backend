<?php
defined ('BASEPATH') OR exit('No direct access script allowed');
require_once FCPATH . 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Barang_Import_lib {
    private $CI;
    public function __construct() {
        $this->CI =& get_instance();
    }

    public function preview(array $file, string $id_perusahaan):array 
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        try {
            $this->validateUpload($file);

            $excel = $this->parseFile($file['tmp_name']);
            $dbResult = $this->getBarangDB($id_perusahaan);
            $db = $dbResult['items'];

            $result = [];

            foreach ($excel as $kode => $row) {
                if (!empty($row['duplicate'])) {
                    $result[] = [
                        'kode' => $kode,
                        'status' => 'duplicate',
                        'errors'=> ['Duplikat dalam file'],
                        'changes' => [],
                        'excel' => $row,
                        'db' => null,
                    ];
                    continue;
                }

                $errors = $this->validateRow($row);

                if (isset($db[$kode])) {
                    $dbRow = $db[$kode];
                    $changeFields = $this->detectChanges($row, $dbRow);

                    $status = !empty($errors)
                        ? 'error'
                        : (!empty($changeFields) ? 'update' : 'no_change');

                    $result[] = [
                        'kode' => $kode,
                        'status' => $status,
                        'errors'=> $errors,
                        'changes' => $changeFields,
                        'excel' => $row,
                        'db' => $dbRow,
                    ];
                } else {
                    $deletedRow = isset($db[$kode]) && (int)$db[$kode]['status'] === 0 ? $db[$kode] : null;

                    $result[] = [
                        'kode' => $kode,
                        'status' => !empty($errors) ? 'error' : 'insert',
                        'errors' => $errors,
                        'changes' => [],
                        'excel' => $row,
                        'db' => $deletedRow,
                    ];
                }
            }

            $summary = [
                'total' => count($result),
                'insert'    => count(array_filter($result, fn($r) => $r['status'] === 'insert')),
                'update'    => count(array_filter($result, fn($r) => $r['status'] === 'update')),
                'no_change' => count(array_filter($result, fn($r) => $r['status'] === 'no_change')),
                'error'     => count(array_filter($result, fn($r) => $r['status'] === 'error')),
                'duplicate' => count(array_filter($result, fn($r) => $r['status'] === 'duplicate')),
            ];

            return [
                'success' => true,
                'data' => [
                    'summary' => $summary,
                    'items' => $result,
                    'duplicate' => $summary['duplicate'],
                ],
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
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
 
            $this->CI->db->trans_start();
 
            $insert  = [];
            $update  = 0;
            $skipped = 0;
 
            foreach ($excel as $kode => $row) {
 
                if (!empty($row['duplicate'])) {
                    $skipped++;
                    continue;
                }
 
                if (!empty($this->validateRow($row))) {
                    $skipped++;
                    continue;
                }
 
                $data = $this->prepareData($row, $id_perusahaan, $id_user);
 
                if (isset($db[$kode])) {
                    if ($this->doUpdate($db[$kode], $data)) {
                        $update++;
                    }
                } else {
                    $deleted = $this->CI->db->get_where('tb_barang', [
                        'kode_artikel' => $kode,
                        'status'       => 0,
                    ])->row();
 
                    if ($deleted) {
                        $data['status'] = 1;
                        $this->CI->db->update('tb_barang', $data, ['id' => $deleted->id]);
                        $update++;
                    } else {
                        $data['status'] = 1;
                        $insert[]       = $data;
                    }
                }
            }
 
            if (!empty($insert)) {
                $this->CI->db->insert_batch('tb_barang', $insert);
            }
 
            $this->CI->db->trans_complete();
 
            if (!$this->CI->db->trans_status()) {
                return ['success' => false, 'error' => 'Transaksi database gagal'];
            }
 
            return [
                'success' => true,
                'data'    => [
                    'inserted' => count($insert),
                    'updated'  => $update,
                    'skipped'  => $skipped,
                ],
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
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
 
        $sheet  = $reader->load($filePath)->getActiveSheet()->toArray();
        $header = $sheet[0] ?? [];
 
        $this->validateHeader($header);
 
        $result = [];
 
        foreach ($sheet as $i => $row) {
            if ($i === 0) continue;
            if (!isset($row[1]) || trim($row[1]) === '') continue;
 
            $kode = $this->normalize($row[1]);
            $kode = trim(preg_replace('/\s+BARANG\s*X\s*$/i', '', $kode));
 
            if (isset($result[$kode])) {
                $result[$kode]['duplicate'] = true;
                continue;
            }
 
            $result[$kode] = [
                'kode'        => $kode,
                'nama'        => trim($row[2] ?? ''),
                'keterangan'  => trim($row[3] ?? ''),
                'size'        => trim($row[4] ?? ''),
                'satuan'      => trim($row[5] ?? ''),
                'kelipatan'   => max(1, (int)($row[6] ?? 1)),
 
                'retail'        => $this->parseHarga($row[7]  ?? 0),
                'grosir'        => $this->parseHarga($row[8]  ?? 0),
                'grosir_10'     => $this->parseHarga($row[9]  ?? 0),
                'het_jawa'      => $this->parseHarga($row[10] ?? 0),
                'indo_barat'    => $this->parseHarga($row[11] ?? 0),
                'special_price' => $this->parseHarga($row[12] ?? 0),
                'barang_x'      => $this->parseHarga($row[13] ?? 0),
 
                'duplicate' => false,
            ];
        }
 
        return $result;
    }

    private function parseHarga($val): ?float
    {
        if ($val === null || $val === '') return null;
 
        if (is_string($val)) {
            $val = str_replace(['Rp', ' ', "\xA0"], '', $val);
            $val = trim($val);
        }
 
        if ($val === '' || $val === null) return null;
 
        // Format: 1.500,75
        if (preg_match('/^\d{1,3}(\.\d{3})+,\d+$/', $val)) {
            return (float) str_replace(['.', ','], ['', '.'], $val);
        }
 
        // Format: 10.000 atau 1.000.000
        if (preg_match('/^\d{1,3}(\.\d{3})+$/', $val)) {
            return (float) str_replace('.', '', $val);
        }
 
        // Format: 10,5
        if (preg_match('/^\d+,\d+$/', $val)) {
            return (float) str_replace(',', '.', $val);
        }
 
        if (is_numeric($val)) return (float) $val;
 
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
            'No', 'Kode', 'Barang', 'Keterangan', 'Size', 'Satuan', 'Kelipatan',
            'Retail', 'Grosir', 'Grosir_10', 'HET_Jawa', 'Indo_Barat', 'SP', 'Brg X',
        ];
 
        foreach ($expected as $i => $col) {
            $actual = trim($header[$i] ?? '');
            if (strtoupper($actual) !== strtoupper($col)) {
                throw new \Exception(
                    "Format file tidak sesuai template. " .
                    "Kolom ke-" . ($i + 1) . " harus \"$col\", ditemukan \"$actual\". " .
                    "Silahkan unduh template kembali."
                );
            }
        }
    }

    private function validateRow(array $row): array
    {
        $errors = [];
 
        $validSize   = ['S','M','L','XL','XXL','XXXL','XXXXL','S/M','L/XL','M/L','XL/XXL','ALL SIZE'];
        $validSatuan = ['Pck','Pcs','Box','Psg','BOX'];
 
        if (empty($row['kode'])) $errors[] = 'Kode kosong';
        if (empty($row['nama'])) $errors[] = 'Nama kosong';
 
        if (empty($row['size'])) {
            $errors[] = 'Size kosong';
        } elseif (!in_array(strtoupper(trim($row['size'])), array_map('strtoupper', $validSize))) {
            $errors[] = 'Size tidak valid (' . implode('/', $validSize) . ')';
        }
 
        if (empty($row['satuan'])) {
            $errors[] = 'Satuan kosong';
        } elseif (!in_array($row['satuan'], $validSatuan)) {
            $errors[] = 'Satuan tidak valid (' . implode('/', $validSatuan) . ')';
        }
 
        $kelipatan = (int)($row['kelipatan'] ?? 0);
        if ($kelipatan < 1 || $kelipatan > 1000) {
            $errors[] = 'Kelipatan harus antara 1 – 1000';
        }
 
        $isBarangX = ($row['barang_x'] ?? 0) > 0;
 
        if ($isBarangX) {
            $adaHargaLain = $row['retail'] || $row['grosir'] || $row['grosir_10']
                || $row['het_jawa'] || $row['indo_barat'] || $row['special_price'];
 
            if ($adaHargaLain) {
                $errors[] = 'Barang X hanya boleh isi kolom Barang X';
            }
        } else {
            if (!empty($row['barang_x'])) {
                $errors[] = 'Barang normal tidak boleh isi kolom Barang X';
            }
        }
 
        return $errors;
    }

    private function validateUpload(array $file): void
    {
        if (empty($file['tmp_name'])) {
            throw new \Exception('File kosong');
        }
 
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new \Exception('Ukuran file maksimal 5MB');
        }
 
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx', 'xls', 'csv'])) {
            throw new \Exception('Format file tidak didukung. Gunakan xlsx, xls, atau csv');
        }
    }

    private function getBarangDB(string $id_perusahaan): array
    {
        if (empty($id_perusahaan)) {
            throw new \Exception('ID Perusahaan tidak dikirim');
        }
 
        $perusahaan = $this->CI->db->get_where('tb_perusahaan', ['id' => $id_perusahaan])->row();
 
        if (!$perusahaan) {
            throw new \Exception('Perusahaan tidak ditemukan');
        }
 
        $rows   = $this->CI->db->get_where('tb_barang', [])->result();
        $result = [];
 
        foreach ($rows as $r) {
            $kode          = $this->normalize($r->kode_artikel);
            $result[$kode] = [
                'id'            => $r->id,
                'kode'          => $kode,
                'nama'          => $r->nama_artikel,
                'keterangan'    => $r->keterangan,
                'size'          => $r->size,
                'satuan'        => $r->satuan,
                'kelipatan'     => $r->kelipatan ?? 1,
                'retail'        => $r->retail,
                'grosir'        => $r->grosir,
                'grosir_10'     => $r->grosir_10,
                'het_jawa'      => $r->het_jawa,
                'indo_barat'    => $r->indo_barat,
                'special_price' => $r->special_price,
                'barang_x'      => $r->barang_x,
                'status'        => $r->status,
            ];
        }
 
        $semuaPT      = $this->CI->db->get('tb_perusahaan')->result();
        $perusahaanMap = [];
        foreach ($semuaPT as $p) {
            $perusahaanMap[strtoupper(trim($p->nama))] = $p->id;
        }
 
        return ['items' => $result, 'map' => $perusahaanMap];
    }

    private function detectChanges(array $excelRow, array $dbRow): array
    {
        $changed = [];
 
        foreach ($excelRow as $field => $val) {
            if (!isset($dbRow[$field])) continue;
 
            if (is_numeric($val)) {
                if ((float)$dbRow[$field] !== (float)$val) {
                    $changed[] = $field;
                }
            } else {
                if ($this->normalize($dbRow[$field]) !== $this->normalize($val)) {
                    $changed[] = $field;
                }
            }
        }
 
        return $changed;
    }

    private function prepareData(array $row, string $id_perusahaan, string $id_user): array
    {
        return [
            'kode_artikel'  => $row['kode'],
            'nama_artikel'  => $row['nama'],
            'keterangan'    => $row['keterangan'],
            'size'          => $row['size'],
            'satuan'        => $row['satuan'],
            'kelipatan'     => max(1, (int)$row['kelipatan']),
            'retail'        => $row['retail'],
            'grosir'        => $row['grosir'],
            'grosir_10'     => $row['grosir_10'],
            'het_jawa'      => $row['het_jawa'],
            'indo_barat'    => $row['indo_barat'],
            'special_price' => $row['special_price'],
            'barang_x'      => $row['barang_x'],
            'kategori'      => ($row['barang_x'] ?? 0) > 0 ? 1 : 0,
            'id_perusahaan' => $id_perusahaan,
            'updated_at'    => date('Y-m-d'),
            'id_user'       => $id_user,
        ];
    }

    private function doUpdate(array $dbRow, array $data): bool
    {

        if ((int)$dbRow['status'] === 0) {
            $data['status'] = 1;
            $this->CI->db->update('tb_barang', $data, ['id' => $dbRow['id']]);
            return true;
        }
 
        $skip = ['updated_at', 'id_user', 'id_perusahaan', 'status'];
 
        foreach ($data as $field => $val) {
            if (in_array($field, $skip) || !isset($dbRow[$field])) continue;
 
            if ((string)$dbRow[$field] !== (string)$val) {
                $this->CI->db->update('tb_barang', $data, ['id' => $dbRow['id']]);
                return true;
            }
        }
 
        return false;
    }
}