<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_mobile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {       
        redirect($this->config->item('frontend_host'));
    }

    // public function login()
    // {
    //     $username = $this->input->post('username');
    //     $password = $this->input->post('password');
    //     $perusahaan = $this->input->post('perusahaan');
    //     $where = array(
    //         'username' => $username,
    //         'password' => md5($password)
    //     );
    //     $user = $this->db->get_where('tb_user', $where);

    //     // jika usernya ada
    //     if ($user->num_rows() > 0) {
    //         $perusahaan = $this->db->query("SELECT * from tb_perusahaan where id = '$perusahaan' order by id limit 1")->row();
    //         $user = $user->row();
    //         $data = [
    //             'id' => $user->id,
    //             'name' => $user->nama,
    //             'username' => $user->username,
    //             'role_id' => $user->id_role,
    //             'perusahaan_id' => $perusahaan->id,
    //             'nama_perusahaan' => $perusahaan->nama,
    //             'login' => true,
    //         ];
    //         $this->session->set_userdata($data);
    //         if ($user->id_role == 1) {
    //             redirect(base_url('Dashboard'));
    //         } elseif ($user->id_role == 2) {
    //             redirect(base_url('Sales_order'));
    //         }
            
    //     } else {
    //         tampil_alert('error', 'Gagal !', 'Password atau Username yang anda masukkan salah');
    //         redirect(base_url('Auth_mobile'));
    //     }
    // }
    // public function logout()
    // {
    //     $this->session->sess_destroy();
    //     tampil_alert('success', 'Berhasil', 'Anda telah logout');
    //     redirect(base_url('Auth_mobile'));
    // }
    // //  ganti password
    // public function gantiPass()
    // {
    //     $id_user = $this->session->userdata('id');
    //     $password = $this->input->post('pass');
    //     $data = array(
    //         'password' => md5($password)
    //     );
    //     $where = array(
    //         'id'    => $id_user
    //     );
    //     $this->db->update('tb_user', $data, $where);
    //     tampil_alert('success', 'BERHASIL', 'Password berhasil di ganti');
    //     redirect(base_url('Auth_mobile'));
    // }
}
