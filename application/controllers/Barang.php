<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Barang extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') == false) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$data['view'] = 'admin/barang';
		$data['title'] = 'Data Barang';
		$data['barang'] = $this->db->query("SELECT tb_barang.*, tb_perusahaan.nama as nama_perusahaan, tb_perusahaan.id as id_perusahaan from tb_barang join tb_perusahaan on tb_barang.id_perusahaan = tb_perusahaan.id where status = 1  order by id desc")->result();
		$data['perusahaan'] = $this->db->query("SELECT * from tb_perusahaan order by id")->result();
		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/index.php', $data);
		$this->load->view('templates/footer.php');
	}
	public function getdata()
	{
		// Mengambil parameter id_barang dari permintaan Ajax
		$tes = $this->input->GET('id_barang');

		// Mengambil data artikel dari tabel tb_barang berdasarkan id_barang
		$artikel = $this->db->query("SELECT tb_barang.*, tb_perusahaan.nama as perusahaan FROM tb_barang join tb_perusahaan on tb_perusahaan.id = tb_barang.id_perusahaan WHERE tb_barang.id = '$tes'")->row();
		header('Content-Type: application/json'); // Tambahkan header untuk menandakan bahwa respons adalah JSON

		// Jika data artikel ditemukan, kirimkan objek JSON dengan atribut-artibut yang diperlukan
		if ($artikel) {
			$response = array(
				'id_barang' => $artikel->id,
				'kode' => $artikel->kode_artikel,
				'nama' => $artikel->nama_artikel,
				'satuan' => $artikel->satuan,
				'kelipatan' => $artikel->kelipatan,
				'kelipatan_info'=> kelipatan_pesan((int)$artikel->kelipatan),
				'size' => $artikel->size,
				'kategori' => $artikel->kategori,
				'keterangan' => $artikel->keterangan,
				'retail' => $artikel->retail,
				'grosir' => $artikel->grosir,
				'grosir_10' => $artikel->grosir_10,
				'het_jawa' => $artikel->het_jawa,
				'indo_barat' => $artikel->indo_barat,
				'special_price' => $artikel->special_price,
				'barang_x' => $artikel->barang_x,
				'perusahaan' => $artikel->perusahaan,
			);
			echo json_encode($response);
		} else {
			// Jika data artikel tidak ditemukan, kirimkan objek JSON kosong
			echo json_encode(array());
		}
	}
	// simpan data
	public function simpan()
	{
		$id_user = $this->session->userdata('id');
		// Format angka dari format rupiah ke angka biasa
		$fields = array('retail', 'grosir', 'grosir_10', 'het_jawa', 'indo_barat', 'special_price', 'barang_x');
		foreach ($fields as $field) {
			$rupiah = $this->input->post($field);
			$angka = str_replace(array('Rp', '.', ','), array('', '', '.'), $rupiah);
			$data[$field] = (float) $angka;
		}

		$data['keterangan'] = $this->input->post('keterangan');
		$data['kode_artikel'] = $this->input->post('kode');
		$data['id_perusahaan'] = $this->input->post('perusahaan');
		$data['nama_artikel'] = $this->input->post('barang');
		$data['satuan'] = $this->input->post('satuan');
		$data['size'] = $this->input->post('size');
		$data['kategori'] = $this->input->post('kategori');
		$data['updated_at'] = date('Y-m-d');
		$data['id_user'] = $id_user;

		$kelipatan = (int)$this->input->post('kelipatan');
		if ($kelipatan < 1) {
			tampil_alert('error', 'GAGAL', 'Kelipatan minimal 1');
			redirect(base_url('Barang'));
			return;
		}

		$data['kelipatan'] = $kelipatan;

		// Proses update
		$this->db->insert('tb_barang', $data);
		tampil_alert('success', 'BERHASIL', 'Data Barang berhasil di tambah');
		redirect(base_url('Barang'));
	}
	public function update()
	{
		$id_user = $this->session->userdata('id');
		$id_barang = $this->input->post('id_barang');

		// Format angka dari format rupiah ke angka biasa
		$fields = array('retail', 'grosir', 'grosir_10', 'het_jawa', 'indo_barat', 'special_price', 'barang_x');
		foreach ($fields as $field) {
			$rupiah = $this->input->post($field);
			$angka = str_replace(array('Rp', '.', ','), array('', '', '.'), $rupiah);
			$data[$field] = (float) $angka;
		}

		$data['keterangan'] = $this->input->post('keterangan');
		$data['nama_artikel'] = $this->input->post('barang');
		$data['satuan'] = $this->input->post('satuan');

		$kelipatan = (int)$this->input->post('kelipatan');
		if ($kelipatan < 1) {
			tampil_alert('error', 'GAGAL', 'Kelipatan minimal 1');
			redirect(base_url('Barang'));
			return;
		}
		$data['kelipatan'] = $kelipatan;
		$data['size'] = $this->input->post('size');
		$data['kategori'] = $this->input->post('kategori');
		$data['updated_at'] = date('Y-m-d');
		$data['id_user'] = $id_user;

		// Proses update
		$this->db->update('tb_barang', $data, array('id' => $id_barang));
		tampil_alert('success', 'DI UPDATE', 'Data Barang berhasil di update');
		redirect(base_url('Barang'));
	}
	// hapus data
	public function hapus_data($id)
	{
		// Proses penghapusan data berdasarkan ID
		$this->db->query("UPDATE tb_barang set status = 0 where id = '$id'");
		tampil_alert('success', 'DI HAPUS', 'Data Barang berhasil di hapus');
		redirect(base_url('Barang'));
	}
	// cek kode apakah sudah ada
	public function check_kode_exist()
	{
		// Ambil data kode barang yang dikirim melalui AJAX
		$kode = $this->input->post('kode');

		// Lakukan pengecekan kode barang di database
		$query = $this->db->get_where('tb_barang', array('kode_artikel' => $kode));
		$result = $query->row();

		// Buat respons dalam format JSON
		$response = array('exist' => ($result !== null));
		echo json_encode($response);
	}
	public function preview_import()
	{
		try {

			if (!$this->session->userdata('login')) {
				return $this->jsonError('Unauthorized');
			}

			if (empty($_FILES['file']['tmp_name'])) {
				return $this->jsonError('File kosong');
			}

			$ekstensiFile = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
			if (!in_array($ekstensiFile, ['xlsx', 'xls', 'csv'])) {
				return $this->jsonError('Format file tidak didukung');
			}

			$maxSizeFile = 5 * 1024 * 1024;
			if ($_FILES['file']['size'] > $maxSizeFile) {
				return $this->jsonError('Ukuran file maksimal 5MB');
			}

			$id_perusahaan = $this->input->post('id_perusahaan');

			$excel = $this->parseBarangFile($_FILES['file']['tmp_name']);
			$dbResult = $this->getBarangDB($id_perusahaan);
			$db = $dbResult['items'];
			$perusahaanMap = $dbResult['map'];

			$result = [];

			foreach ($excel as $kode => $row) {

				// handle file duplikat
				if (!empty($row['duplicate'])) {
					$result[] = [
						'kode' => $kode,
						'status'=> 'duplicate',
						'errors'=> ['Duplicate dalam file'],
						'changes'=> [],
						'excel' => $row,
						'db'=> null
					];
					continue;
				}

				// validasi file excel
				$validSize = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL', 'S/M', 'L/XL', 'M/L', 'XL/XXL', 'ALL SIZE'];
				$validSatuan = ['Pck', 'Pcs', 'Box', 'Psg', 'BOX'];
				$errors = [];

				if (!$row['kode']) {
					$errors[] = 'Kode kosong';
				}

				if (!$row['nama']) {
					$errors[] = 'Nama kosong';
				}

				if (!$row['size']) {
					$errors[] = 'Size kosong';
				} elseif (!in_array(strtoupper(trim($row['size'])), array_map('strtoupper', $validSize))) {
					$errors[] = 'Size tidak valid (S/M/L/XL/XXL/XXXL/XXXXL/S/M/L/XL/M/L/XL/XXL/ALL SIZE)';
				}

				if (!$row['satuan']) {
					$errors[] = 'Satuan kosong';
				} elseif (!in_array($row['satuan'], $validSatuan)) {
					$errors[] = 'Satuan tidak valid (Pck/Pcs/Box/Psg/BOX)';
				}

				if ((int)$row['kelipatan'] < 1 || (int)$row['kelipatan'] > 1000) {
					$errors[] = 'Kelipatan harus 1 - 1000';
				}

				// validasi harga
				$hargaFields = [
					'retail', 'grosir', 'grosir_10',
    				'het_jawa', 'indo_barat', 'special_price', 'barang_x'
				];

				foreach ($hargaFields as $field) {
					if ($row[$field] !== null && !is_numeric($row[$field])) {
						$errors[] = "$field harus angka";
					}
				}

				// validasi kategori
				$isBarangX = ($row['barang_x'] ?? 0) > 0;

				if ($isBarangX) {
					// Barang X: kolom harga lain harus kosong
					if (
						$row['retail'] || $row['grosir'] || $row['grosir_10'] ||
						$row['het_jawa'] || $row['indo_barat'] || $row['special_price']
					) {
						$errors[] = 'Barang X hanya boleh isi kolom Barang X';
					}
				} else {
					// Normal: tidak boleh isi kolom harga barang_x
					if (!empty($row['barang_x'])) {
						$errors[] = 'Barang normal tidak boleh isi Barang X';
					}
				}

				//cek database
				if (isset($db[$kode])) {
					$dbRow = $db[$kode];
					$changeFields = [];

					foreach ($row as $field => $val) {

						if (!isset($dbRow[$field])) continue;

						if (is_numeric($val)) {

							if ((float)$dbRow[$field] !== (float)$val) {
								$changeFields[] = $field;
							}

						} else {

							$dbVal = $this->normalize($dbRow[$field]);
							$exVal = $this->normalize($val);

							if ($dbVal !== $exVal) {
								$changeFields[] = $field;
							}
						}
					}

					if (!empty($errors)) {
						$status = 'error';
					} else {
						$status = !empty($changeFields) ? 'update' : 'no_change';
					}

					$result[] = [
						'kode' => $kode,
						'status' => $status,
						'errors' => $errors,
						'changes' => $changeFields,
						'excel' => $row,
						'db' => $dbRow
					];
                } else {
					$status = !empty($errors) ? 'error' : 'insert';

					$result[] = [
						'kode' => $kode,
						'status' => $status,
						'errors' => $errors,
						'changes' => array_keys($row),
						'excel' => $row,
						'db' => null
					];					

				}
				
			}

			$summary = [
				'total' => count($result),
				'insert' => count(array_filter($result, fn($r) => $r['status'] === 'insert')),
				'update' => count(array_filter($result, fn($r) => $r['status'] === 'update')),
				'no_change' => count(array_filter($result, fn($r) => $r['status'] === 'no_change')),
				'error' => count(array_filter($result, fn($r) => $r['status'] === 'error')),
				'duplicate' => count(array_filter($result, fn($r) => $r['status'] === 'duplicate')),
			];
			/* untuk test
			$debug_updates = array_values(array_filter($result, fn($r) => $r['status'] === 'update'));
			$debug_updates = array_slice($debug_updates, 0, 3);
			*/
			return $this->jsonSuccess([
				'summary' => $summary,
				'items' => $result,
				// 'debug' => $debug_updates,
				'duplicate' => count(array_filter($result, fn($r) => $r['status'] === 'duplicate'))
			]);

		} catch (\Throwable $e) {
			return $this->jsonError($e->getMessage());
		}
	}

	public function import()
	{
		try {

			if (empty($_FILES['file']['tmp_name'])) {
				return $this->jsonError('File kosong');
			}

			$maxSizeFile = 5 * 1024 * 1024;
			if ($_FILES['file']['size'] > $maxSizeFile) {
				return $this->jsonError('Ukuran file maksimal 5MB');
			}

			$id_perusahaan = $this->input->post('id_perusahaan');
			$id_user = $this->session->userdata('id');

			$excel = $this->parseBarangFile($_FILES['file']['tmp_name']);
			$dbResult      = $this->getBarangDB($id_perusahaan);
			$db            = $dbResult['items'];
			$perusahaanMap = $dbResult['map'];

			$this->db->trans_start();

			$insert = [];
			$update = 0;
			$skipped = 0;

			foreach ($excel as $kode => $row) {

				if (!empty($row['duplicate'])) {
					$skipped++;
					continue;
				}
				// validasi
				$isBarangX = ($row['barang_x'] ?? 0) > 0;

				$invalid = false;

				$validSize   = ['S','M','L','XL','XXL','XXXL','XXXXL','S/M','L/XL','M/L','XL/XXL','ALL SIZE'];
				$validSatuan = ['Pck','Pcs','Box','Psg','BOX'];

				if (!$row['kode'] || !$row['nama'] || !$row['satuan'] || !$row['size']) {
					$invalid = true;
				}

				if (!in_array(strtoupper(trim($row['size'])), array_map('strtoupper', $validSize))) {
					$invalid = true;
				}

				if (!in_array($row['satuan'], $validSatuan)) {
					$invalid = true;
				}

				if ($row['kelipatan'] < 1 || $row['kelipatan'] > 1000) {
					$invalid = true;
				}

				if ($isBarangX) {
					if (
						$row['retail'] || $row['grosir'] || $row['grosir_10'] ||
						$row['het_jawa'] || $row['indo_barat'] || $row['special_price']
					) {
						$invalid = true;
					}
				} else {
					if (!empty($row['barang_x'])) {
						$invalid = true;
					}
				}

				if ($invalid) {
					$skipped++;
					continue;
				}

				$data = $this->prepareBarangImport($row, $id_perusahaan, $id_user);

				if (isset($db[$kode])) {

					if ($this->updateBarangImport($db[$kode], $data)) {
						$update++;
					}

				} else {
					$deleted = $this->db->get_where('tb_barang', [
						'kode_artikel' => $kode,
						'status' => 0
					])->row();

					if ($deleted) {
						$data['status'] = 1;
						$this->db->update('tb_barang', $data, ['id' => $deleted->id]);
						$update++;
					} else {
						$data['status'] = 1;
						$insert[] = $data;
					}
				}
			}

			if (!empty($insert)) {
				$this->db->insert_batch('tb_barang', $insert);
			}

			$this->db->trans_complete();

			if (!$this->db->trans_status()) {
				return $this->jsonError('Gagal import');
			}

			return $this->jsonSuccess([
				'inserted' => count($insert),
				'updated' => $update,
				'skipped' => $skipped
			]);

		} catch (\Throwable $e) {
			return $this->jsonError($e->getMessage());
		}
	}
	private function parseBarangFile($file)
	{
		$reader = IOFactory::createReaderForFile($file);
		$reader->setReadDataOnly(true);

		if ($reader instanceof \PhpOffice\PhpSpreadsheet\Reader\Csv) {
			$sample = file_get_contents($file);
			if (substr_count($sample, ';') > substr_count($sample, ',')) {
				$reader->setDelimiter(';');
			} else {
				$reader->setDelimiter(',');
			}
		}

		$spreadsheet = $reader->load($file);
		$sheet = $spreadsheet->getActiveSheet()->toArray();

		$header = $sheet[0] ?? [];

		// Validasi header — tanpa kolom PT
		$expectedHeader = ['No', 'Kode', 'Barang', 'Keterangan', 'Size', 'Satuan', 'Kelipatan', 'Retail', 'Grosir', 'Grosir_10', 'HET_Jawa', 'Indo_Barat', 'SP', 'Brg X'];

		foreach($expectedHeader as $i => $expected) {
			$actual = trim($header[$i] ?? '');
			if (strtoupper($actual) !== strtoupper($expected)) {
				throw new \Exception(
					"Format file tidak sesuai template, silahkan unduh template kembali."
				);
			}
		}

		$result = [];

		foreach ($sheet as $i => $row) {
			if ($i === 0) continue;
			if (!isset($row[1]) || trim($row[1]) === '') continue; 

			$kode = $this->normalize($row[1]);
			$kode = trim(preg_replace('/\s+BARANG\s*X\s*$/i', '', $kode));

			if (isset($result[$kode])) continue;

			$result[$kode] = [
				'kode'          => $kode,
				'nama'          => trim($row[2] ?? ''),
				'keterangan'    => trim($row[3] ?? ''),
				'size'          => trim($row[4] ?? ''),
				'satuan'        => trim($row[5] ?? ''),
				'kelipatan'     => max(1, (int)($row[6] ?? 1)),

				// raw data untuk validasi file
				'retail_raw' => $row[7] ?? null,
				'grosir_raw' => $row[8] ?? null,
				'grosir_10_raw' => $row[9] ?? null,
				'het_jawa_raw' => $row[10] ?? null,
				'indo_barat_raw' => $row[11] ?? null,
				'special_price_raw' => $row[12] ?? null,
				'barang_x' => $row[13] ?? null,

				// data untuk preview nya
				'retail'        => $this->parseHarga($row[7] ?? 0),
				'grosir'        => $this->parseHarga($row[8] ?? 0),
				'grosir_10'     => $this->parseHarga($row[9] ?? 0),
				'het_jawa'      => $this->parseHarga($row[10] ?? 0),
				'indo_barat'    => $this->parseHarga($row[11] ?? 0),
				'special_price' => $this->parseHarga($row[12] ?? 0),
				'barang_x'      => $this->parseHarga($row[13] ?? 0),
				'duplicate'     => false
			];
		}

		return $result;
	}
	private function parseHarga($val)
	{
		if ($val === null || $val === '') return null;

		// Bersihkan Rp dan spasi
		if (is_string($val)) {
			$val = str_replace(['Rp', ' ', "\xA0"], '', $val);
			$val = trim($val);
		}

		if ($val === '' || $val === null) return null;

		// 1. Format campuran: 1.500,75 
		if (preg_match('/^\d{1,3}(\.\d{3})+,\d+$/', $val)) {
			$val = str_replace('.', '', $val);
			$val = str_replace(',', '.', $val);
			return (float)$val;
		}

		// 2. Pola ribuan: 10.000 atau 1.000.000
		if (preg_match('/^\d{1,3}(\.\d{3})+$/', $val)) {
			$val = str_replace('.', '', $val);
			return (float)$val;
		}

		// 3. Desimal koma: 10,5 10.5
		if (preg_match('/^\d+,\d+$/', $val)) {
			$val = str_replace(',', '.', $val);
			return (float)$val;
		}

		// 4. Sudah numeric
		if (is_numeric($val)) {
			return (float)$val;
		}

		return null;
	}
	private function normalize($val)
	{
		// Hilangkan karakter non printable
		$val = preg_replace('/[[:^print:]]/', '', $val);

		// Replace semua whitespace jadi 1 spasi
		$val = preg_replace('/\s+/', ' ', $val);

		// Trim + uppercase
		return strtoupper(trim($val));
	}
	private function getBarangDB($id_perusahaan)
	{
		if (empty($id_perusahaan)) {
			throw new Exception('ID Perusahaan tidak dikirim');
		}

		$perusahaan = $this->db->get_where('tb_perusahaan', [
			'id' => $id_perusahaan
		])->row();

		if (!$perusahaan) {
			throw new Exception('Perusahaan tidak ditemukan di database');
		}

		$rows = $this->db->get_where('tb_barang', ['status' => 1])->result();

		$result = [];

		foreach ($rows as $r) {
			$kode = $this->normalize($r->kode_artikel);

			$result[$kode] = [
				'id' => $r->id,
				'kode' => $kode,
				'nama' => $r->nama_artikel,
				'keterangan' => $r->keterangan,
				'size' => $r->size,
				'satuan' => $r->satuan,
				'retail' => $r->retail,
				'grosir' => $r->grosir,
				'grosir_10' => $r->grosir_10,
				'het_jawa' => $r->het_jawa,
				'indo_barat' => $r->indo_barat,
				'special_price' => $r->special_price,
				'barang_x' => $r->barang_x,
				'kelipatan' => $r->kelipatan ?? 1,
				'status' => $r->status,
				'perusahaan' => $perusahaan->nama
			];
		}

		$semuaPT = $this->db->get('tb_perusahaan')->result();
		$perusahaanMap = [];
		foreach ($semuaPT as $p) {
			$perusahaanMap[strtoupper(trim($p->nama))] = $p->id;
		}

		return [
			'items' => $result,
			'map'   => $perusahaanMap
		];
	}
	private function jsonSuccess($data)
	{
		echo json_encode([
			'success' => true,
			'data' => $data
		]);
		exit;
	}

	private function jsonError($msg)
	{
		echo json_encode([
			'success' => false,
			'error' => $msg
		]);
		exit;
	}

	private function updateBarangImport($dbRow, $data)
	{
		if ((int)$dbRow['status'] === 0) {
			$data['status'] = 1;

			$this->db->update('tb_barang', $data, [
				'id' => $dbRow['id']
			]);
			
			return True;
		}

		$excludeCompare = ['update_at', 'id_user', 'id_perusahaan', 'status', 'id'];
		foreach ($data as $field => $val) {
			if (in_array($field, $excludeCompare)) continue;
			if (!isset($dbRow[$field])) continue;

			if ((string)$dbRow[$field] !== (string)$val) {
				$this->db->update('tb_barang', $data, [
					'id' => $dbRow['id']
				]);
				return true;
			}
		}

		return false;
	}
	private function prepareBarangImport($row, $id_perusahaan, $id_user)
	{
		return [
			'kode_artikel' => $row['kode'],
			'nama_artikel' => $row['nama'],
			'keterangan' => $row['keterangan'],
			'size' => $row['size'],
			'satuan' => $row['satuan'],
			'retail' => $row['retail'],
			'grosir' => $row['grosir'],
			'grosir_10' => $row['grosir_10'],
			'het_jawa' => $row['het_jawa'],
			'indo_barat' => $row['indo_barat'],
			'special_price' => $row['special_price'],
			'barang_x' => $row['barang_x'],
			'kelipatan' => max(1, $row['kelipatan']),
			'kategori' => ($row['barang_x'] ?? 0) > 0 ? 1 : 0,
			'id_perusahaan' => $id_perusahaan,
			'updated_at' => date('Y-m-d'),
			'id_user' => $id_user
		];
	}
}
