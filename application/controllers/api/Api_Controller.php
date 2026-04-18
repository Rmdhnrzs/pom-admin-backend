<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->handleCors();
    }

    public function index()
    {
        /*
        $user = $this->session->userdata();
        if (!$user || empty($user['login'])) {
            return $this->response([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        return $this->response([
            'status' => true,
            'data' => [
                'id'             => $user['id'],
                'name'           => $user['name'],
                'username'       => $user['username'],
                'role_id'        => $user['role_id'],
                'perusahaan_id'  => $user['perusahaan_id'],
                'nama_perusahaan'=> $user['nama_perusahaan'],
            ]
        ], 200);
        */
    }

    private function handleCors()
    {
        $allowedOrigins = [
            $this->config->item('frontend_host'),
            'http://192.168.17.164:5173',
            'http://localhost', 
            'http://localhost:5173',
            'http://localhost/pom-mobile',
            'https://globalindo-group.com/pom-mobile',
            'https://globalindo-group.com/pom',
            'https://globalindo-group.com/pom-web',
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
        }

        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization");
        header("Access-Control-Allow-Credentials: true");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit();
        }
    }

    private function unauthorized($message)
    {
        $this->response([
            'status' => false,
            'message' => $message
        ], 401);
        exit;
    }

    protected function checkAuth()
    {
        //JWT React
        $token = $this->get_token();
        if ($token) {
            $result = $this->jwt_lib->verify($token);

            if (!$result['status']) {
                return $this->unauthorized('Token invalid / expired');
            }

            $this->current_user = (object) $result['data'];
            return;
        }

        /*
        $session = $this->session->userdata();

        if (!empty($session['login'])) {
            $this->current_user = (object) $session;
            return;
        }
        */

        return $this->unauthorized('Unauthorized');
    }

    private function get_token()
    {

        
        $headers = apache_request_headers();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        if (preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            return $matches[1];
        }
       
        if (isset($_COOKIE['token'])) {
            return $_COOKIE['token'];
        }
        return null;
    }

    protected function getUser()
    {
        // kalau JWT
        if (isset($this->current_user)) {
            return (object) $this->current_user;
        }

        /*
        $session = $this->session->userData();
        if (!empty($session['login'])) {
            return (object) $session;
        }
        */

        return null;
    }

    /*
    protected function checkCsrf()
    {
        if ($this->input->method() !== 'get') {
            if (!verify_csrf_token()) {
                return $this->unauthorized('Invalid CSRF Token');
            }
        }
    }
    */

    protected function response($data, $status_code = 200)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header($status_code);
        $this->output->set_output(json_encode($data));
    }
}
