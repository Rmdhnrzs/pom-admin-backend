<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'controllers/api/Api_Controller.php';

class Gudang_Api extends Api_Controller {
    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }
    private function getPriceType($tipe_po)
    {
        $defaultType = 'reguler';
        $priceType = [
            'reguler' => [
                'tb_barang.retail',
                'tb_barang.grosir',
                'tb_barang.grosir_10',
                'tb_barang.het_jawa',
                'tb_barang.indo_barat',
            ],
            'special_price' => [
                'tb_barang.special_price'
            ],
            'barang_x' => [
                'tb_barang.barang_x'
            ]
        ];

        $type = isset($tipe_po) ? $tipe_po : $defaultType;
        $columns = join(', ', $priceType[$type]);
        $condition = '('.join(' or ', array_map(fn($column) => "$column > 0", $priceType[$type])).')';

        return [
            'columns' => $columns,
            'condition' => $condition,
        ];
    }
    public function index() {
		if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

		$perusahaan_id = $this->current_user->perusahaan_id;
		$nama_artikel = $this->input->get('nama_artikel');
		// reguler, special price, barang x
        $tipe_po = $this->input->get('tipe_po');
        
        ['columns' => $columns, 'condition' => $condition] = $this->getPriceType($tipe_po);
        
		$data['barang'] = $this->db->query("SELECT tb_barang.id, tb_barang.kode_artikel, tb_barang.nama_artikel, tb_barang.stok, tb_barang.kelipatan, tb_barang.satuan, tb_barang.size, tb_perusahaan.nama as nama_perusahaan, tb_perusahaan.id as id_perusahaan, $columns from tb_barang join tb_perusahaan on tb_perusahaan.id = ? where tb_barang.deleted_at is null and tb_barang.nama_artikel like ? and $condition order by id desc", [$perusahaan_id, "%$nama_artikel%"])->result();
		
		$this->response([
            'success' => true,
            'message' => 'Successfully get items data',
            'data' => $data['barang'],
        ], 200);
    }
}