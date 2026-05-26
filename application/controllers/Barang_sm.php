<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
class Barang_sm extends CI_Controller {
    public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('login') == false) {
			redirect(base_url('auth'));
		}
	}
    public function index() {
        $data['view'] = 'admin/barang_sm';
		$data['title'] = 'DBSM';
		$data['perusahaan'] = $this->db->query("SELECT * from tb_perusahaan order by id")->result();
		$data['customer'] = $this->db->query("SELECT 
		tc.id, 
		tc.no_pelanggan, 
		tc.nama_customer,
		COUNT(tbs.id) as total_artikel
		FROM tb_customer as tc 
		join tb_perusahaan as tp on tc.id_perusahaan = tp.id 
		left join tb_barang_sm as tbs on tc.id = tbs.id_customer
		group by tc.id
		order by tc.id desc")->result();
		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/index.php', $data);
		$this->load->view('templates/footer.php');
    }

	public function show() {
		$id_customer = $this->input->get("id_customer");
		$customer = $this->db->query("SELECT * FROM tb_customer WHERE id = $id_customer")->row();

		$barang_sm = $this->db->query("SELECT tb.*, tbs.id as id_bsm FROM tb_barang as tb JOIN tb_barang_sm AS tbs ON tbs.id_barang = tb.id AND tbs.id_customer = $id_customer order by tbs.id desc")->result();
		
		$artikel = $this->db->query("SELECT tb.* FROM tb_barang AS tb LEFT JOIN tb_barang_sm AS tbs ON tb.id = tbs.id_barang AND tbs.id_customer = $id_customer WHERE tbs.id is NULL order by tb.id desc")->result();

		header('Content-Type: application/json');
		$response = json_encode([
			'customer' => $customer,
			'artikel' => $artikel,
			'barang_sm' => $barang_sm,
		]);
		echo $response;
	}

	public function store() {
		$id_customer = (int)$this->input->post("id_customer");
		$id_barang = (int)$this->input->post("id_barang");

		$data = [
			'id_customer' => $id_customer,
			'id_barang' => $id_barang
		];

		$this->db->insert("tb_barang_sm", $data);
	}

	public function destroy() {
		$id_bsm = $this->input->get("id_bsm");
		var_dump($id_bsm);
		$this->db->where("id", $id_bsm)->delete("tb_barang_sm");
	}
}