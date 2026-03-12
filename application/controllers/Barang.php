<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
}
