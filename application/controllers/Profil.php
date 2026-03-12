<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {
	public function __construct(){
		parent::__construct();

		if ($this->session->userdata('login') == false) {
			redirect(base_url('auth_mobile'));
		}
	}

	public function index()
	{
		$data['view'] = 'profil';
		$data['title'] = 'Profil';
		$id_user = $this->session->userdata('id');
		$data['user'] = $this->db->query("SELECT id,nama,username,no_telp,email from tb_user where id='$id_user' order by id")->row();
		$this->load->view('mobile/header.php',$data);
		$this->load->view('mobile/mobile.php',$data);
		$this->load->view('mobile/footer.php');
	}

	public function edit_password()
	{
		$user_id = $this->session->userdata('id');
		$old_password = md5($this->input->post('old_password'));
		$new_password = md5($this->input->post('new_password'));


		$cek_user = $this->db->query("SELECT id from tb_user where id = '$user_id' and password = '$old_password'");

		if ($cek_user->num_rows() == 0) {
			$data['status'] = 0;
			$data['message'] = "Password lama anda salah !";
		} else {
			$data = array(
				'password' => $new_password,
			);
			$where = array(
				'id' => $user_id,
				'password' => $old_password,
			);
			$update = $this->db->update('tb_user',$data,$where);

			$data['status'] = 1;
			$data['message'] = "Password berhasil diupdate !";
		}

		echo json_encode($data);

		
	}


}
