<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
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
		$data['view'] = 'admin/customer';
		$data['title'] = 'Data Customer';
		$data['perusahaan'] = $this->db->query("SELECT * from tb_perusahaan order by id")->result();
		$data['customer'] = $this->db->query("SELECT tc.*, tp.nama as nama_perusahaan, tu.nama as sales from tb_customer tc
		left join tb_user tu on tc.id_sales = tu.id
		join tb_perusahaan tp on tp.id = tc.id_perusahaan
		where tc.status = 1 order by tc.id desc")->result();
		$data['sales'] = $this->db->query("SELECT * from tb_user where id_role = 2 and status = 1 order by id desc")->result();
		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/index.php', $data);
		$this->load->view('templates/footer.php');
	}
	public function getdata()
	{
		// Mengambil parameter id_barang dari permintaan Ajax
		$tes = $this->input->GET('id_cust');

		// Mengambil data artikel dari tabel tb_barang berdasarkan id_barang
		$artikel = $this->db->query("SELECT tb_customer.*, tb_perusahaan.nama as perusahaan FROM tb_customer join tb_perusahaan on tb_perusahaan.id = tb_customer.id_perusahaan WHERE tb_customer.id = '$tes'")->row();
		header('Content-Type: application/json'); // Tambahkan header untuk menandakan bahwa respons adalah JSON

		// Jika data artikel ditemukan, kirimkan objek JSON dengan atribut-artibut yang diperlukan
		if ($artikel) {
			$response = array(
				'id_cust' => $artikel->id,
				'nama_customer' => $artikel->nama_customer,
				'no_pelanggan' => $artikel->no_pelanggan,
				'alamat' => $artikel->alamat,
				'area' => $artikel->area,
				'tipe_harga' => $artikel->tipe_harga,
				'margin' => $artikel->margin,
				'min_order' => $artikel->minimum_order,
				'id_sales' => $artikel->id_sales,
				'perusahaan' => $artikel->perusahaan,
				'termasuk_pajak' => $artikel->termasuk_pajak,
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
		$fields = array('minimum_order');
		foreach ($fields as $field) {
			$rupiah = $this->input->post($field);
			$angka = str_replace(array('Rp', '.', ','), array('', '', '.'), $rupiah);
			$data[$field] = (float) $angka;
		}

		$data['nama_customer'] = $this->input->post('customer');
		$data['id_perusahaan'] = $this->input->post('perusahaan');
		$data['no_pelanggan'] = $this->input->post('no_pelanggan');
		$data['alamat'] = $this->input->post('alamat');
		$data['area'] = $this->input->post('area');
		$data['alamat'] = $this->input->post('alamat');
		$data['tipe_harga'] = $this->input->post('tipe_harga');
		$data['margin'] = $this->input->post('margin');
		$data['id_sales'] = $this->input->post('sales');
		$data['termasuk_pajak'] = $this->input->post('termasuk_pajak');
		$data['id_user'] = $id_user;
		$data['updated_at']	= date('Y-m-d');

		// Proses update
		$this->db->insert('tb_customer', $data);
		tampil_alert('success', 'BERHASIL', 'Data Customer berhasil di tambah');
		redirect(base_url('Customer'));
	}
	public function update()
	{
		$id_user = $this->session->userdata('id');
		$id_cust = $this->input->post('id_cust');

		// Format angka dari format rupiah ke angka biasa
		$fields = array('minimum_order');
		foreach ($fields as $field) {
			$rupiah = $this->input->post($field);
			$angka = str_replace(array('Rp', '.', ','), array('', '', '.'), $rupiah);
			$data[$field] = (float) $angka;
		}

		$data['nama_customer'] = $this->input->post('customer');
		$data['no_pelanggan'] = $this->input->post('no_pelanggan');
		$data['alamat'] = $this->input->post('alamat');
		$data['area'] = $this->input->post('area');
		$data['alamat'] = $this->input->post('alamat');
		$data['tipe_harga'] = $this->input->post('tipe_harga');
		$data['margin'] = $this->input->post('margin');
		$data['id_sales'] = $this->input->post('sales');
		$data['termasuk_pajak'] = $this->input->post('termasuk_pajak');
		$data['id_user'] = $id_user;
		$data['updated_at']	= date('Y-m-d');

		// Proses update
		$this->db->update('tb_customer', $data, array('id' => $id_cust));
		tampil_alert('success', 'DI UPDATE', 'Data Customer berhasil di update');
		redirect(base_url('Customer'));
	}
	// hapus data
	public function hapus_data($id)
	{
		// Proses penghapusan data berdasarkan ID
		$this->db->query("UPDATE tb_customer set status = 0 where id ='$id'");
		tampil_alert('success', 'DI HAPUS', 'Data Customer berhasil di hapus');
		redirect(base_url('Customer'));
	}
	// cek kode apakah sudah ada
	public function no_pelanggan()
	{
		// Ambil data kode barang yang dikirim melalui AJAX
		$kode = $this->input->post('kode');

		// Lakukan pengecekan kode barang di database
		$query = $this->db->get_where('tb_customer', array('no_pelanggan' => $kode, 'status' => 1));
		$result = $query->row();

		// Buat respons dalam format JSON
		$response = array('exist' => ($result !== null));
		echo json_encode($response);
	}
	// cek kode apakah sudah ada
	public function cek_customer()
	{
		// Ambil data kode barang yang dikirim melalui AJAX
		$kode = $this->input->post('kode');

		// Lakukan pengecekan kode barang di database
		$query = $this->db->get_where('tb_customer', array('nama_customer' => $kode, 'status' => 1));
		$result = $query->row();

		// Buat respons dalam format JSON
		$response = array('exist' => ($result !== null));
		echo json_encode($response);
	}
}
