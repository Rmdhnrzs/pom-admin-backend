<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class Gudang extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->helper('url');
		if ($this->session->userdata('login') == false) {
			redirect(base_url('auth'));
		}
    }
    public function index() {
		$data['view'] = 'admin/gudang';
		$data['title'] = 'Gudang';
		$data['barang'] = $this->db->query("SELECT tb_barang.*, tb_perusahaan.nama as nama_perusahaan, tb_perusahaan.id as id_perusahaan FROM tb_barang JOIN tb_perusahaan ON tb_barang.id_perusahaan = tb_perusahaan.id WHERE tb_barang.deleted_at IS NULL ORDER BY tb_barang.id DESC")->result();
		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/index.php', $data);
		$this->load->view('templates/footer.php');
	}

	public function impor() {
		$barang = $this->db->query("SELECT * FROM tb_barang WHERE deleted_at IS NULL")->result();
		
		  // --- Validation ---
		if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
			die('File upload failed.');
		}

		$file = $_FILES['excel_file'];
		$allowedMimes = [
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
			'application/vnd.ms-excel',                                           // .xls
			'text/csv',                                                            // .csv
		];

		if (!in_array($file['type'], $allowedMimes)) {
			die('Invalid file type.');
		}

		// --- Read the file ---
		$spreadsheet = IOFactory::load($file['tmp_name']);
		$sheet = $spreadsheet->getActiveSheet();

		// --- Iterate rows ---
		$data = [];
		foreach ($sheet->getRowIterator() as $row) {
			$rowData = [];
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);

			foreach ($cellIterator as $cell) {
				$rowData[] = $cell->getValue();
			}

			$data[] = $rowData;
		}

		$excelData = [];

		// Step 1: index excel data by kode_artikel for easy lookup
		foreach ($data as $index => $row) {
			if ($index < 1) continue;
			$kode = trim($row[1]);
			if (empty($kode)) continue;

			$excelData[$kode] = [
				'kode_artikel' => $kode,
				'nama' => $row[2],
				'stok' => $row[4],
			];
		}

		// Step 2: index DB data by kode_artikel
		$dbData = [];
		foreach ($barang as $b) {
			$dbData[$b->kode_artikel] = [
				'id' => $b->id,
				'kode_artikel' => $b->kode_artikel,
				'nama' => $b->nama_artikel,
				'stok' => $b->stok,
			];
		}

		// Step 3: compare
		$matched = [];
		foreach ($excelData as $kode => $excelRow) {
			// exists in both
			if (isset($dbData[$kode])) {
				$matched[] = [
					'id' => $dbData[$kode]['id'],
					'kode_artikel' => $kode,
					'nama_excel'   => $excelRow['nama'],
					'stok_excel'   => (int) $excelRow['stok'],
					'nama_db'      => $dbData[$kode]['nama'],
					'stok_db'      => (int) $dbData[$kode]['stok'],
					'changed' => $dbData[$kode]['stok'] != $excelRow['stok'],
					'is_exist'     => true,
					'in_db_only'   => false,
				];
			} 
		}

		// foreach ($dbData as $kode => $dbRow) {
		// 	if (!isset($excelData[$kode])) {
		// 		$matched[] = [
		// 			'id'           => $dbRow['id'],
		// 			'kode_artikel' => $kode,
		// 			'nama_excel'   => '-',
		// 			'stok_excel'   => '-',
		// 			'nama_db'      => $dbRow['nama'],
		// 			'stok_db'      => (int) $dbRow['stok'],
		// 			'changed'      => null,
		// 			'is_exist'     => true,
		// 			'in_db_only'   => true,
		// 		];
		// 	}
		// }
		
		$this->session->set_userdata('impor_matched', $matched);
		echo json_encode([
			'success' => true,
			'matched' => $matched,
		]);
	}
	public function confirm_impor() {
		$matched = $this->session->userdata('impor_matched');
		if (empty($matched)) {
			echo json_encode(['success' => false, 'message' => 'Tidak ada data untuk dikonfirmasi.']);
			return;
		}

		foreach ($matched as $row) {
			$this->db->where('kode_artikel', $row['kode_artikel'])
				->where('deleted_at', null)->update('tb_barang', [
				'stok' => $row['stok_excel'],
				'updated_stok_at' => date('Y-m-d H:i:s'),
			]);
		}

		$this->session->unset_userdata('impor_matched');
		$this->session->set_flashdata('success', 'Import berhasil disimpan.');

		echo json_encode(['success' => true, 'redirect' => base_url('gudang')]);
		return;
	}
}