<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct(){
		parent::__construct();

		if ($this->session->userdata('login') == false) {
			redirect(base_url('auth'));
		}
	}

	public function index()
	{
		$data['view'] = 'templates/home';
		$data['title'] = 'Dashboard';
		
		$data['total_barang'] = $this->db->count_all('tb_barang');
		$data['total_customer'] = $this->db->count_all('tb_customer');

		$data['so_pending'] = $this->db->where('status', 0)->count_all_results('tb_order');
		// $data['so_approved'] = $this->db->where('status', 1)->count_all_results('tb_order');

		$this->load->view('templates/header.php',$data);
		$this->load->view('templates/index.php',$data);
		$this->load->view('templates/footer.php');
	}


}
