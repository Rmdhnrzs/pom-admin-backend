<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->handleCors();
    }

    public function index()
    {
        return $this->response($this->session->userdata(), 200);
    }

    private function handleCors()
    {
        $allowedOrigins = [
            "http://localhost:5173",
            "http://192.168.17.132:5173"
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
    protected function checkAuth()
    {
        if ($this->session->userdata('login') !== true) {
            $this->response([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
            return true;
        }
        return false;
    }
    protected function response($data, $status_code = 200)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_status_header($status_code);
        $this->output->set_output(json_encode($data));
    }
}