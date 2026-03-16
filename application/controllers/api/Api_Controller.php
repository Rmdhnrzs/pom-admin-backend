<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Api_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->_set_cors();
    }

    private function _set_cors() {
        $allowed_origins = ['http://192.168.17.105:5173', 'http://localhost:5173', '*'];
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if (in_array("*", $allowed_origins)) {
            header("Access-Control-Allow-Origin: *");
        } elseif (in_array($origin, $allowed_origins)) {
            header("Access-Control-Allow-Origin: $origin");
        }
        
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');
        
        // Handle preflight request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
    protected function checkAuth()
    {
        if ($this->session->userdata('login') != true) {
            return $this->response([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
    }
    protected function response($data, $status_code = 200)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header($status_code);
        $this->output->set_output(json_encode($data));
    }
}