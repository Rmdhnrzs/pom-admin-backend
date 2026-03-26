<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'controllers/api/Api_Controller.php';
class Auth_Mobile_Api extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();  
        $this->load->library('form_validation');
    }

    public function index()
    {
        return $this->response($this->session->userdata(), 200);
    }
    # Coba tambahkan ini sementara di atas fungsi login() untuk verifikasi
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
        $perusahaan_id = $this->input->post('perusahaan_id');
        
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

            $expiry = time() + $this->config->item('sess_expiration'); // CI session expiry config
            $user_data = $user->row();
            $data = [
                'id' => $user_data->id,
                'name' => $user_data->nama,
                'username' => $user_data->username,
                'role_id' => $user_data->id_role,
                'perusahaan_id' => $perusahaan->id,
                'nama_perusahaan' => $perusahaan->nama,
                'login' => true,
                'expiry' => $expiry,
            ];
            $payload = [
                'id' => $user_data->id,
                'name' => $user_data->nama,
                'username' => $user_data->username,
                'role_id' => $user_data->id_role,
                'perusahaan_id' => $perusahaan->id,
                'nama_perusahaan' => $perusahaan->nama, 
            ];

            $token = $this->jwt_lib->generate($payload);
            // $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            /*
            setcookie(
                'token',
                $token,
                [
                    'expires' => time() + 86400,
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]
            );
            */
            // $csrf_token = generate_csrf_token();
            return $this->response([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token 
                // 'csrf_token' => $csrf_token
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
        // hapus cookie JWT
        /*
        setcookie(
            'token',
            '',
            [
                'expires' => time() - 3600,
                'path' => '/',
            ]
        );
        */

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

        $this->checkAuth();
        $id_user = $this->current_user->id;
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

    public function profile()
    {
        $this->checkAuth();

        return $this->response([
            'status' => true,
            'data' => [
                'id'              => $this->current_user->id,
                'name'            => $this->current_user->name,
                'username'        => $this->current_user->username,
                'role_id'         => $this->current_user->role_id,
                'perusahaan_id'   => $this->current_user->perusahaan_id,
                'nama_perusahaan' => $this->current_user->nama_perusahaan,
            ]
        ], 200);
    }
}
