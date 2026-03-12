<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		echo date("ymdhis")."-".uniqid();
		echo " | ";
		echo time();
	}

	public function get()
	{
		$data = $this->db->query("SELECT tb_barang.kode_artikel, tb_barang.retail FROM tb_barang where retail > 0 order by kode_artikel")->result();
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get2()
	{
		$this->load->library('cart');
		$data = $this->cart->contents();
		// $data = $this->db->query("SELECT * FROM tb_customer")->result();
		$data2 = "0001";
		$data2 = $data2 + 1;
		echo json_encode($data2);
	}

	public function lihat(){
		$string = "0";
		
		$string2 = substr($string, 0,2);
		if ($string2 == '0.') {
			echo (int)$string;
		} else {
			echo "Tidak Aktif";
		}
	}

	public function add()
	{
		$temperature = $this->input->post('temperature'); 
		$humidity = $this->input->post('humidity'); 
		$id_alat = $this->input->post('id_alat'); 
		$data = array(
			'created_date' => date("Y-m-d H:i:s"),
			'temperature' => $temperature,
			'humidity' => $humidity,
			'id_alat' => $id_alat
		);

		$set = $this->db->insert('tb_arduino',$data);
		
	}

	public function pecah_qty(){

		$artikels = array('artikel_a','artikel_b','artikel_c');
		$qty = 28;
		$urutan = 0;
		$artikel_a = 0;
		$artikel_b = 0;
		$artikel_c = 0;
		for ($i=0; $i < $qty; $i++) { 
			$$artikels[$urutan] += 1;
			
			if ($urutan == 2) {
				$urutan = 0;
			} else {
				$urutan++;}
		}

		echo "Qty = $qty<br>";
		echo "HBKL A = $artikel_a <br>";
		echo "HBKL B = $artikel_b <br>";
		echo "HBKL C = $artikel_c <br>";

	}

	public function card()
	{
		
		$this->load->library('pagination');
		$config = array();
		$config["base_url"] = base_url() . "Test/card/";
        $config["total_rows"] =$this->db->count_all("tb_barang");
		
        $per_page = 10;
        $uri_segmen = 3;
        $config["per_page"] = $per_page;

        

		// bootstrap link
		$config['full_tag_open'] = '<ul class="pagination">';        
		$config['full_tag_close'] = '</ul>';        
		$config['first_link'] = 'First';        
		$config['last_link'] = 'Last';        
		$config['first_tag_open'] = '<li class="page-item"><span class="page-link">';        
		$config['first_tag_close'] = '</span></li>';        
		$config['prev_link'] = '&laquo';        
		$config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';        
		$config['prev_tag_close'] = '</span></li>';        
		$config['next_link'] = '&raquo';        
		$config['next_tag_open'] = '<li class="page-item"><span class="page-link">';        
		$config['next_tag_close'] = '</span></li>';        
		$config['last_tag_open'] = '<li class="page-item"><span class="page-link">';        
		$config['last_tag_close'] = '</span></li>';        
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';        
		$config['cur_tag_close'] = '</a></li>';        
		$config['num_tag_open'] = '<li class="page-item"><span class="page-link">';        
		$config['num_tag_close'] = '</span></li>';

		$this->pagination->initialize($config);


        $from = ($this->uri->segment($uri_segmen)) ? $this->uri->segment($uri_segmen) : 0;
        $data["cards"] = $this->db->query("SELECt * FROM tb_barang order by nama_artikel limit $from,$per_page")->result();
        $data["links"] = $this->pagination->create_links();

        $this->load->view('card', $data);
	}


}
