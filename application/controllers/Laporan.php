<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {
	public function __construct(){
		parent::__construct();

		if ($this->session->userdata('login') == false) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$data['view'] = 'laporan';
		$data['title'] = 'Laporan';
		$periode_id = $this->input->post('periode_id');
		$data['penilaian'] = $this->db->query("SELECT tb_karyawan.nama, tb_karyawan.rfid, tb_nilai.nilai, tb_nilai.harga, tb_penilaian.jumlah, (SELECT jumlah from tb_penilaian where id_nilai =1 and periode_id = '$periode_id' and karyawan_id = tb_karyawan.id) as nilai_a, (SELECT jumlah from tb_penilaian where id_nilai =2 and periode_id = '$periode_id' and karyawan_id = tb_karyawan.id) as nilai_b, (SELECT jumlah from tb_penilaian where id_nilai =3 and periode_id = '$periode_id' and karyawan_id = tb_karyawan.id) as nilai_c from tb_penilaian join tb_karyawan on tb_karyawan.id = tb_penilaian.karyawan_id join tb_nilai on tb_nilai.id = tb_penilaian.id_nilai where tarik_hrd = 1 and tb_penilaian.periode_id = '$periode_id' group by tb_karyawan.id")->result();
		$data['harga_a'] = $this->db->query("SELECT harga from tb_nilai where id = 1")->row()->harga;;
		$data['harga_b'] = $this->db->query("SELECT harga from tb_nilai where id = 2")->row()->harga;;
		$data['harga_c'] = $this->db->query("SELECT harga from tb_nilai where id = 3")->row()->harga;;
		$data['periode'] = $this->db->query("SELECT id, date(awal_periode) as awal_periode, date(akhir_periode) as akhir_periode from tb_periode where akhir_periode is not null order by id desc limit 5")->result();
		$this->load->view('templates/header.php',$data);
		$this->load->view('templates/index.php',$data);
		$this->load->view('templates/footer.php');
	}


}
