<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php';
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

			$id_perusahaan = $this->input->post('id_perusahaan');

			$excel = $this->parseBarangFile($_FILES['file']['tmp_name']);
			$db    = $this->getBarangDB($id_perusahaan);

			$result = [];

			foreach ($excel as $kode => $row) {

				// Validasi ulang
				$errors = [];

				if (!$row['kode']) $errors[] = 'Kode kosong';
				if (!$row['nama']) $errors[] = 'Nama kosong';
				if ((int)$row['kelipatan'] < 1) {
					$errors[] = 'Kelipatan harus >= 1';
				}
				$is_valid = empty($errors);

				if (isset($db[$kode])) {
					$dbRow = $db[$kode];

					$changedFields = [];

					foreach ($row as $field => $val) {
						if (!isset($dbRow[$field])) continue;

						if ((string)$dbRow[$field] !== (string)$val) {
							$changedFields[] = $field;
						}
					}

					$result[] = [
						'kode' => $kode,
						'status' => $is_valid
							? (empty($changedFields) ? 'same' : 'update')
							: 'invalid',
						'errors' => $errors,
						'changes' => $changedFields,
						'excel' => $row,
						'db' => $dbRow
					];

				} else {
					$result[] = [
						'kode' => $kode,
						'status' => $is_valid ? 'insert' : 'invalid',
						'errors' => $errors,
						'changes' => array_keys($row),
						'excel' => $row,
						'db' => null
					];
				}
			}

			return $this->jsonSuccess([
				'total' => count($result),
				'items' => $result
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

			$id_perusahaan = $this->input->post('id_perusahaan');
			$id_user = $this->session->userdata('id');

			$excel = $this->parseBarangFile($_FILES['file']['tmp_name']);
			$db    = $this->getBarangDB($id_perusahaan);

			$this->db->trans_start();

			$insert = [];
			$update = 0;
			$skipped = 0;

			foreach ($excel as $kode => $row) {

				// validasi
				if (!$row['kode'] || !$row['nama'] || $row['kelipatan'] < 1 || $row['kelipatan'] > 1000) {
					$skipped++;
					continue;
				}

				$data = $this->prepareBarangImport($row, $id_perusahaan, $id_user);

				if (isset($db[$kode])) {

					if ($this->updateBarangImport($db[$kode], $data)) {
						$update++;
					}

				} else {

					$data['status'] = 1;
					$insert[] = $data;
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

		$spreadsheet = $reader->load($file);
		$sheet = $spreadsheet->getActiveSheet()->toArray();

		$result = [];

		foreach ($sheet as $i => $row) {

			if ($i === 0) continue;
			if (!isset($row[2]) || trim($row[2]) === '') continue;

			$kode = $this->normalize($row[2]);
			if (isset($result[$kode])){
				$result[$kode]['Duplicate'] = true;
			}
			$result[$kode] = [
				'kode' => $kode,
				'nama' => trim($row[3] ?? ''),
				'keterangan' => trim($row[4] ?? ''),
				'size' => trim($row[5] ?? ''),
				'satuan' => trim($row[6] ?? ''),
				'retail' => $this->parseHarga($row[7] ?? 0),
				'grosir' => $this->parseHarga($row[8] ?? 0),
				'grosir_10' => $this->parseHarga($row[9] ?? 0),
				'het_jawa' => $this->parseHarga($row[10] ?? 0),
				'indo_barat' => $this->parseHarga($row[11] ?? 0),
				'special_price' => $this->parseHarga($row[12] ?? 0),
				'barang_x' => $this->parseHarga($row[13] ?? 0),
				'kelipatan' => max(1, (int)($row[14] ?? 1)),
			];
		}

		return $result;
	}
	private function parseHarga($val)
	{
		$val = str_replace(['Rp', '.', ','], ['', '', '.'], $val);
		return (float)$val;
	}
	private function normalize($val)
	{
		// Hilangkan karakter aneh (non printable)
		$val = preg_replace('/[[:^print:]]/', '', $val);

		// Replace semua whitespace (tab, newline, dll) jadi 1 spasi
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

		$rows = $this->db
			->where('id_perusahaan', $id_perusahaan)
			->get('tb_barang')
			->result();

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
				'kelipatan' => $r->kelipatan,
				'status' => $r->status,
				'perusahaan' => $perusahaan->nama
			];
		}

		return $result;
	}
	private function compareBarang($excel, $db)
	{
		$result = [];

		foreach ($excel as $kode => $ex) {

			if (isset($db[$kode])) {

				$dbRow = $db[$kode];
				$changes = [];

				foreach ($ex as $field => $val) {

					if (!array_key_exists($field, $dbRow)) continue;

					if (is_numeric($val)) {

						if ((float)$dbRow[$field] !== (float)$val) {
							$changes[] = $field;
						}

					} else {

						$dbVal = $this->normalize($dbRow[$field]);
						$exVal = $this->normalize($val);

						if ($dbVal !== $exVal) {
							$changes[] = $field;
						}
					}
				}

				$result[] = [
					'kode'    => $kode,
					'is_exist'=> true,
					'is_new'  => false,
					'changed' => !empty($changes),
					'changes' => $changes,
					'excel'   => $ex,
					'db'      => $dbRow
				];

			} else {

				$result[] = [
					'kode'    => $kode,
					'is_exist'=> false,
					'is_new'  => true,
					'changed' => true,
					'changes' => array_keys($ex),
					'excel'   => $ex,
					'db'      => null
				];
			}
		}

		return $result;
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

		foreach ($data as $field => $val) {
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
