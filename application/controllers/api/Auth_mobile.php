<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_mobile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Login Page';
        $data['perusahaan'] = $this->db->query("SELECT * FROM tb_perusahaan order by id")->result();
        $this->load->view('mobile/header.php', $data);
        $this->load->view('mobile/login.php', $data);
        $this->load->view('mobile/footer.php');
    }

    public function login()
    {
        if ($this->input->method() !== 'post') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $perusahaan_id = $this->input->post('perusahaan');

        if (!$username || !$password || !$perusahaan_id) {
            return $this->response([
                'status' => false,
                'message' => 'Username, password, and perusahaan are required'
            ], 400);
        }

        $where = array(
            'username' => $username,
            'password' => md5($password)
        );
        $user = $this->db->get_where('tb_user', $where);

        if ($user->num_rows() > 0) {
            $perusahaan = $this->db->get_where('tb_perusahaan', array('id' => $perusahaan_id))->row();
            
            if (!$perusahaan) {
                return $this->response([
                    'status' => false,
                    'message' => 'Perusahaan not found'
                ], 404);
            }

            $user_data = $user->row();
            $data = [
                'id' => $user_data->id,
                'name' => $user_data->nama,
                'username' => $user_data->username,
                'role_id' => $user_data->id_role,
                'perusahaan_id' => $perusahaan->id,
                'nama_perusahaan' => $perusahaan->nama,
                'login' => true,
            ];
            $this->session->set_userdata($data);

            return $this->response([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'id' => $user_data->id,
                    'name' => $user_data->nama,
                    'role_id' => $user_data->id_role,
                    'perusahaan_id' => $perusahaan->id,
                    'nama_perusahaan' => $perusahaan->nama
                ]
            ], 200);
        } else {
            return $this->response([
                'status' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        return $this->response([
            'status' => true,
            'message' => 'Logout successful'
        ], 200);
    }
    //  ganti password
    public function gantiPass()
    {
        if ($this->input->method() !== 'post') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $id_user = $this->session->userdata('id');
        $password = $this->input->post('pass');

        if (!$password) {
            return $this->response([
                'status' => false,
                'message' => 'Password is required'
            ], 400);
        }

        if (!$id_user) {
            return $this->response([
                'status' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $data = array('password' => md5($password));
        $where = array('id' => $id_user);
        $this->db->update('tb_user', $data, $where);

        return $this->response([
            'status' => true,
            'message' => 'Password berhasil di ganti'
        ], 200);
    }

    public function getPerusahaan()
    {
        $perusahaan = $this->db->query("SELECT * FROM tb_perusahaan order by id")->result();
        return $this->response([
            'status' => true,
            'data' => $perusahaan
        ], 200);
    }

    private function response($data, $status_code = 200)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header($status_code);
        $this->output->set_output(json_encode($data));
    }
}
