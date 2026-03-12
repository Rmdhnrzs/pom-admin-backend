<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mobile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cek_versi()
    {
        $data = [
            'versi' => "1.0",
            'ready' => true,
            'message' => "Sedang maintenance, harap coba beberapa saat lagi !",
        ];
        
        // Mengatur header Content-Type
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
