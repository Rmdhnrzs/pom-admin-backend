<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'controllers/api/Api_Controller.php';
class Companies extends Api_Controller {
    public function __construct()
    {
        parent::__construct();
        // $this->checkAuth();
    }
    public function index()
    {
        $data['perusahaan'] = $this->db->query("SELECT * FROM tb_perusahaan order by id")->result();
        $this->response([
            'success' => true,
            'message' => 'Successfully get companies data',
            'data' => $data['perusahaan'],
        ], 200);
    }
}