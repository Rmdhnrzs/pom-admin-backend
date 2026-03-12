<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Login Page';
        $this->load->view('templates/header.php', $data);
        $this->load->view('auth/login.php');
        $this->load->view('templates/footer.php');
    }

    public function login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $where = array(
            'username' => $username,
            'password' => md5($password)
        );
        $user = $this->db->get_where('tb_user', $where);

        // jika usernya ada
        if ($user->num_rows() > 0) {
            $user = $user->row();
            $data = [
                'id' => $user->id,
                'name' => $user->username,
                'role_id' => $user->id_role,
                'login' => true,
            ];
            $this->session->set_userdata($data);
            if ($user->id_role == 1) {
                redirect(base_url('Dashboard'));
            } elseif ($user->id_role == 2) {
                redirect(base_url('Sales_order/customer'));
            }
            
        } else {
            $this->session->set_flashdata('gagal', " Username / password salah. !");
            redirect(base_url('auth'));
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', '<div class="alert alert-success" 
        role="alert"> You have been logout!</div>');
        redirect('auth');
    }
    //  ganti password
    public function gantiPass()
    {
        $id_user = $this->session->userdata('id');
        $password = $this->input->post('pass');
        $data = array(
            'password' => md5($password)
        );
        $where = array(
            'id'    => $id_user
        );
        $this->db->update('tb_user', $data, $where);
        tampil_alert('success', 'BERHASIL', 'Password berhasil di ganti');
        redirect(base_url(''));
    }
}
