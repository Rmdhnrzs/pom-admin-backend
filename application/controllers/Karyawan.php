<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller {
	public function __construct(){
		parent::__construct();

		if ($this->session->userdata('login') == false) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$data['view'] = 'karyawan';
		$data['title'] = 'Data Master';
		$data['karyawan'] = $this->db->query("SELECT * from tb_karyawan where nama not like '-' order by nama")->result();
		$this->load->view('templates/header.php',$data);
		$this->load->view('templates/index.php',$data);
		$this->load->view('templates/footer.php');
	}


}
