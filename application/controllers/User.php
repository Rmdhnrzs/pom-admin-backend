<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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
		$data['view'] = 'admin/user';
		$data['title'] = 'Data User';
		$data['user'] = $this->db->query("SELECT * from tb_user where status = 1  order by id desc")->result();
		$this->load->view('templates/header.php', $data);
		$this->load->view('templates/index.php', $data);
		$this->load->view('templates/footer.php');
	}

	public function getdata()
	{
		$id_user = $this->input->get('id_user');
		$artikel = $this->db->get_where('tb_user', array('id' => $id_user))->row();
		header('Content-Type: application/json');
		echo json_encode($artikel ? $artikel : array());
	}

	public function simpan()
	{
		$data = array(
			'nama' => $this->input->post('NamaLengkap'),
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),
			'id_role' => $this->input->post('role')
		);
		$this->db->insert('tb_user', $data);
		tampil_alert('success', 'BERHASIL', 'Data User berhasil di tambah');
		redirect('User');
	}

	public function update()
	{
		$id_user = $this->input->post('id_user');
		$data = array(
			'nama' => $this->input->post('nama_update'),
			'username' => $this->input->post('username'),
			'id_role' => $this->input->post('role')
		);
		$this->db->update('tb_user', $data, array('id' => $id_user));
		tampil_alert('success', 'DI UPDATE', 'Data User berhasil di update');
		redirect('User');
	}

	public function reset()
	{
		$id_user = $this->input->post('id_user');
		$data['password'] = md5("password");
		$this->db->update('tb_user', $data, array('id' => $id_user));
		tampil_alert('success', 'DI RESET', 'Password user berhasil di Reset');
		redirect('User');
	}

	public function hapus_data($id)
	{
		$this->db->query("UPDATE tb_user set status = 0 where id = '$id'");
		tampil_alert('success', 'DI HAPUS', 'Data User berhasil di hapus');
		redirect('User');
	}

	public function cek_username()
	{
		$kode = $this->input->post('kode');
		$result = $this->db->get_where('tb_user', array('username' => $kode, 'status' => 1))->row();
		$response = array('exist' => ($result !== null));
		echo json_encode($response);
	}
}
