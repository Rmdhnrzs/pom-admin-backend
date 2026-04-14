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
		$data['barang'] = $this->db->query("SELECT tb_barang.*, tb_perusahaan.nama as nama_perusahaan, tb_perusahaan.id as id_perusahaan from tb_barang join tb_perusahaan on tb_barang.id_perusahaan = tb_perusahaan.id where tb_barang.deleted_at IS NULL order by id desc")->result();
		$data['perusahaan'] = $this->db->query("SELECT * from tb_perusahaan order by id")->result();
		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/index.php', $data);
		$this->load->view('templates/footer.php');
	}

	public function getdata()
	{
		$tes = $this->input->GET('id_barang');
		$artikel = $this->db->query("SELECT tb_barang.*, tb_perusahaan.nama as perusahaan FROM tb_barang join tb_perusahaan on tb_perusahaan.id = tb_barang.id_perusahaan WHERE tb_barang.id = '$tes'")->row();
		header('Content-Type: application/json');

		if ($artikel) {
			$response = array(
				'id_barang'     => $artikel->id,
				'kode'          => $artikel->kode_artikel,
				'nama'          => $artikel->nama_artikel,
				'satuan'        => $artikel->satuan,
				'kelipatan'     => $artikel->kelipatan,
				'kelipatan_info'=> kelipatan_pesan((int)$artikel->kelipatan),
				'size'          => $artikel->size,
				'kategori'      => $artikel->kategori,
				'keterangan'    => $artikel->keterangan,
				'retail'        => $artikel->retail,
				'grosir'        => $artikel->grosir,
				'grosir_10'     => $artikel->grosir_10,
				'het_jawa'      => $artikel->het_jawa,
				'indo_barat'    => $artikel->indo_barat,
				'special_price' => $artikel->special_price,
				'barang_x'      => $artikel->barang_x,
				'perusahaan'    => $artikel->perusahaan,
			);
			echo json_encode($response);
		} else {
			echo json_encode(array());
		}
	}

	public function simpan()
	{
		$id_user = $this->session->userdata('id');
		$fields = array('retail', 'grosir', 'grosir_10', 'het_jawa', 'indo_barat', 'special_price', 'barang_x');
		foreach ($fields as $field) {
			$rupiah = $this->input->post($field);
			$angka = str_replace(array('Rp', '.', ','), array('', '', '.'), $rupiah);
			$data[$field] = (float) $angka;
		}

		$data['keterangan']    = $this->input->post('keterangan');
		$data['kode_artikel']  = $this->input->post('kode');
		$data['id_perusahaan'] = $this->input->post('perusahaan');
		$data['nama_artikel']  = $this->input->post('barang');
		$data['satuan']        = $this->input->post('satuan');
		$data['size']          = $this->input->post('size');
		$data['kategori']      = $this->input->post('kategori');
		$data['updated_at']    = date('Y-m-d');
		$data['id_user']       = $id_user;

		$kelipatan = (int)$this->input->post('kelipatan');
		if ($kelipatan < 1) {
			tampil_alert('error', 'GAGAL', 'Kelipatan minimal 1');
			redirect(base_url('Barang'));
			return;
		}

		$data['kelipatan'] = $kelipatan;

		$this->db->insert('tb_barang', $data);
		tampil_alert('success', 'BERHASIL', 'Data Barang berhasil di tambah');
		redirect(base_url('Barang'));
	}

	public function update()
	{
		$id_user  = $this->session->userdata('id');
		$id_barang = $this->input->post('id_barang');

		$fields = array('retail', 'grosir', 'grosir_10', 'het_jawa', 'indo_barat', 'special_price', 'barang_x');
		foreach ($fields as $field) {
			$rupiah = $this->input->post($field);
			$angka = str_replace(array('Rp', '.', ','), array('', '', '.'), $rupiah);
			$data[$field] = (float) $angka;
		}

		$data['keterangan']  = $this->input->post('keterangan');
		$data['nama_artikel'] = $this->input->post('barang');
		$data['satuan']      = $this->input->post('satuan');

		$kelipatan = (int)$this->input->post('kelipatan');
		if ($kelipatan < 1) {
			tampil_alert('error', 'GAGAL', 'Kelipatan minimal 1');
			redirect(base_url('Barang'));
			return;
		}
		$data['kelipatan']  = $kelipatan;
		$data['size']       = $this->input->post('size');
		$data['kategori']   = $this->input->post('kategori');
		$data['updated_at'] = date('Y-m-d');
		$data['id_user']    = $id_user;

		$this->db->update('tb_barang', $data, array('id' => $id_barang));
		tampil_alert('success', 'DI UPDATE', 'Data Barang berhasil di update');
		redirect(base_url('Barang'));
	}

	public function hapus_data($id)
	{
		$this->db->query("UPDATE tb_barang set deleted_at = NOW() where id = '$id'");
		tampil_alert('success', 'DI HAPUS', 'Data Barang berhasil di hapus');
		redirect(base_url('Barang'));
	}

	public function check_kode_exist()
	{
		$kode          = $this->input->post('kode');
		$id_perusahaan = $this->input->post('id_perusahaan');

		$aktif = $this->db->get_where('tb_barang', [
			'kode_artikel'  => $kode,
			'id_perusahaan' => $id_perusahaan,
			'deleted_at'    => null,
		])->row();

		$dihapus = $this->db->query("
			SELECT * FROM tb_barang 
			WHERE kode_artikel = ? 
			AND id_perusahaan = ?
			AND deleted_at IS NOT NULL
			LIMIT 1
		", [$kode, $id_perusahaan])->row();

		$response = array(
			'exist'      => ($aktif !== null),
			'pernah_ada' => ($dihapus !== null),
		);
		echo json_encode($response);
	}

	public function preview_import()
	{
		if (!$this->session->userdata('login')) {
			echo json_encode(['success' => false, 'error' => 'Unauthorized']);
			return;
		}

		$result = $this->barang_import_lib->preview(
			$_FILES['file'],
			$this->input->post('id_perusahaan')
		);

		echo json_encode($result);
	}

	public function import()
	{
		$result = $this->barang_import_lib->import(
			$_FILES['file'],
			$this->input->post('id_perusahaan'),
			(string) $this->session->userdata('id')
		);

		echo json_encode($result);
	}

	// Fungsi untuk tombol download.
	public function export_excel($id_perusahaan = null)
	{

		if ($id_perusahaan) {
			$barang = $this->db->query("
				SELECT tb_barang.*, tb_perusahaan.nama as nama_perusahaan 
				FROM tb_barang 
				JOIN tb_perusahaan ON tb_barang.id_perusahaan = tb_perusahaan.id 
				WHERE tb_barang.deleted_at IS NULL 
				AND tb_barang.id_perusahaan = ?
				ORDER BY tb_barang.id DESC
			", [$id_perusahaan])->result();

			$nama_pt  = $this->db->get_where('tb_perusahaan', ['id' => $id_perusahaan])->row();
			$label_pt = $nama_pt ? strtoupper($nama_pt->nama) : 'PT';
		} else {
			$barang = $this->db->query("
				SELECT tb_barang.*, tb_perusahaan.nama as nama_perusahaan 
				FROM tb_barang 
				JOIN tb_perusahaan ON tb_barang.id_perusahaan = tb_perusahaan.id 
				WHERE tb_barang.deleted_at IS NULL 
				ORDER BY tb_barang.id DESC
			")->result();
			$label_pt = 'Semua PT';
		}

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet       = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Data Barang');

		$Fill   = \PhpOffice\PhpSpreadsheet\Style\Fill::class;
		$Align  = \PhpOffice\PhpSpreadsheet\Style\Alignment::class;
		$Border = \PhpOffice\PhpSpreadsheet\Style\Border::class;
		$Coord  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::class;

		$last_col = 'O';

		$sheet->mergeCells("A1:{$last_col}1");
		$sheet->setCellValue('A1', 'DATA BARANG POM');
		$sheet->getStyle('A1')->applyFromArray([
			'font'      => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Arial'],
			'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2C7BE5']],
			'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
		]);
		$sheet->getRowDimension(1)->setRowHeight(30);

		$sheet->mergeCells("A2:{$last_col}2");
		$sheet->setCellValue('A2', 'Perusahaan: ' . $label_pt);
		$sheet->getStyle('A2')->applyFromArray([
			'font'      => ['size' => 10, 'italic' => true, 'color' => ['rgb' => '555555'], 'name' => 'Arial'],
			'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF4FF']],
			'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
		]);
		$sheet->getRowDimension(2)->setRowHeight(18);

		$sheet->mergeCells("A3:{$last_col}3");
		$sheet->setCellValue('A3', 'Dicetak: ' . date('l, d F Y'));
		$sheet->getStyle('A3')->applyFromArray([
			'font'      => ['size' => 9, 'color' => ['rgb' => '888888'], 'name' => 'Arial'],
			'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF4FF']],
			'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
		]);
		$sheet->getRowDimension(3)->setRowHeight(16);

		$sheet->mergeCells("A4:{$last_col}4");
		$sheet->getStyle('A4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$sheet->getStyle('A4')->getFill()->getStartColor()->setRGB('EEF4FF');
		$sheet->getRowDimension(4)->setRowHeight(6);

		$headers = ['No','Kode','Barang','Keterangan','Size','Satuan','Kelipatan','Kategori','Retail','Grosir','Grosir_10','HET_Jawa','Indo_Barat','SP','Brg X'];
		foreach ($headers as $i => $h) {
			$col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
			$sheet->setCellValue($col . '5', $h);
		}
		$sheet->getStyle("A5:{$last_col}5")->applyFromArray([
			'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Arial'],
			'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2C7BE5']],
			'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
			'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
		]);
		$sheet->getRowDimension(5)->setRowHeight(22);

		$row = 6;
		$no  = 1;
		foreach ($barang as $k) {
			$fillColor = ($row % 2 == 0) ? 'FFFFFF' : 'F4F8FF';

			if ((int)$k->kategori === 1) {
				$kategori_label = 'Barang X';
			} elseif ((float)$k->special_price > 0) {
				$kategori_label = 'Special Price';
			} else {
				$kategori_label = 'Normal';
			}

			$rowData = [
				$no++,
				$k->kode_artikel,
				$k->nama_artikel,
				$k->keterangan,
				$k->size,
				$k->satuan,
				(int) $k->kelipatan,
				$kategori_label,
				(float) $k->retail,
				(float) $k->grosir,
				(float) $k->grosir_10,
				(float) $k->het_jawa,
				(float) $k->indo_barat,
				(float) $k->special_price,
				(float) $k->barang_x,
			];

			foreach ($rowData as $i => $val) {
				$col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
				$sheet->setCellValue($col . $row, $val);
			}

			$sheet->getStyle("A{$row}:{$last_col}{$row}")->applyFromArray([
				'font'    => ['size' => 10, 'name' => 'Arial'],
				'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => $fillColor]],
				'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'DEE2E6']]],
			]);

			foreach (['I','J','K','L','M','N','O'] as $col) {
				$sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('#,##0');
				$sheet->getStyle($col . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
			}

			$sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle("E{$row}:G{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			$sheet->getRowDimension($row)->setRowHeight(18);
			$row++;
		}

		$widths = [5, 12, 25, 20, 7, 8, 10, 12, 14, 14, 14, 14, 14, 14, 14];
		foreach ($widths as $i => $w) {
			$col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
			$sheet->getColumnDimension($col)->setWidth($w);
		}

		$sheet->freezePane('A6');

		$suffix   = $id_perusahaan ? '_' . strtoupper(str_replace(' ', '_', $label_pt)) : '_Semua_PT';
		$filename = 'Data_Barang_POM' . $suffix . '_' . date('Ymd') . '.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

	private function _collectFromData(): array
	{
		$data = [];

		foreach (['retail','grosir','grosir_10','het_jawa','indo_barat','special_price','barang_x'] as $field) {
			$rupiah       = $this->input->post($field);
			$angka        = str_replace(['Rp', '.', ','], ['', '', '.'], $rupiah);
			$data[$field] = (float) $angka;
		}

		$data['keterangan']  = $this->input->post('keterangan');
		$data['nama_artikel'] = $this->input->post('barang');
		$data['satuan']      = $this->input->post('satuan');
		$data['size']        = $this->input->post('size');
		$data['kategori']    = $this->input->post('kategori');

		return $data;
	}
}