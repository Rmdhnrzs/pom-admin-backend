<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'controllers/api/Api_Controller.php';
class Sales_order_api extends Api_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('cart');
        $this->checkAuth();
    }

    public function list_produk()
    {
        if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        // if($this->checkAuth()) return;

        $id_customer = $this->input->get('id_customer');
        $tipe_customer = $this->input->get('tipe_customer');
        $tipe_po = $this->input->get('tipe_po');
        $perusahaan_id = $this->current_user->perusahaan_id;

        if (!isset($id_customer)) {
            return $this->response([
                'status' => false,
                'message' => 'Customer not selected'
            ], 400);
        }

        $tipe = $this->getPriceType($tipe_customer, $tipe_po);

        $produk = $this->db->query("SELECT id, TRIM(kode_artikel) as kode_artikel, nama_artikel, satuan, $tipe as harga, size, stok, kelipatan from tb_barang where $tipe > 0 and status=1 and id_perusahaan = '$perusahaan_id' order by kode_artikel")->result();

        return $this->response([
            'status' => true,
            'data' => $produk
        ], 200);
    }

    private function getPriceType($tipe_customer, $tipe_po)
    {
        if ($tipe_po == 1) {
            switch ($tipe_customer) {
                case 'RETAIL':
                    return 'retail';
                case 'GROSIR':
                    return 'grosir';
                case 'GROSIR+10':
                    return 'grosir_10';
                case 'HET JAWA':
                    return 'het_jawa';
                case 'INDO BARAT':
                    return 'indo_barat';
                default:
                    return 'retail';
            }
        } elseif ($tipe_po == 2) {
            return 'special_price';
        } elseif ($tipe_po == 3) {
            return 'barang_x';
        }
        return 'retail';
    }

    public function customer()
    {
        if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $id_perusahaan = $this->current_user->perusahaan_id;
        $customer = $this->db->query("SELECT * from tb_customer where id_perusahaan = '$id_perusahaan' order by nama_customer")->result();

        return $this->response([
            'status' => true,
            'data' => $customer
        ], 200);
    }

    public function history()
    {
        if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        // $this->checkAuth();

        $tanggal = $this->input->get('tanggal');
        if (!$tanggal) {
            $tanggal = date("Y-m-d");
        }

        $pt = $this->current_user->perusahaan_id;
        $id_user = $this->current_user->id;

        $data_history = $this->db->query("SELECT tb_order.id, tb_order.status, tb_customer.nama_customer, tb_order.tanggal_dibuat, tb_order.jenis as tipe_po from tb_order join tb_customer on tb_customer.id = tb_order.id_customer where tb_order.id_user = '$id_user' and date(tanggal_dibuat) = '$tanggal' and tb_order.id_perusahaan = '$pt' order by tb_order.tanggal_dibuat desc")->result();
        
        return $this->response([
            'status' => true,
            'tanggal' => $tanggal,
            'data' => $data_history
        ], 200);
    }
    public function history_detail()
    {
        if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        // $this->checkAuth();

        $id = $this->input->get('id');

        if (!$id) {
            return $this->response([
                'status' => false,
                'message' => 'Order ID is required'
            ], 400);
        }

        $data_order = $this->db->query("SELECT tb_customer.nama_customer, tb_order.tanggal_dibuat, tb_order.alasan, tb_order.jenis, tb_order.no_faktur, tb_order.referensi, tb_order.diskon, tb_order.catatan, tb_order.status from tb_order join tb_customer on tb_order.id_customer = tb_customer.id where tb_order.id = '$id' order by tb_order.id desc")->row();

        if (!$data_order) {
            return $this->response([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $data_order_detail = $this->db->query("SELECT tb_barang.kode_artikel, tb_barang.nama_artikel, tb_barang.satuan, tb_order_detail.qty, tb_order_detail.harga, tb_order_detail.diskon_barang from tb_order join tb_order_detail on tb_order_detail.id_order = tb_order.id join tb_barang on tb_barang.id = tb_order_detail.id_barang where tb_order.id = '$id' order by tb_order.tanggal_dibuat")->result();

        return $this->response([
            'status' => true,
            'data' => [
                'nama_customer' => $data_order->nama_customer,
                'diskon_faktur' => $data_order->diskon,
                'tanggal_po' => $data_order->tanggal_dibuat,
                'tipe_po' => $data_order->jenis,
                'no_faktur' => $data_order->no_faktur,
                'referensi' => $data_order->referensi,
                'catatan' => $data_order->catatan,
                'alasan' => $data_order->alasan,
                'status' => $data_order->status,
                'total_harga' => array_sum(array_map(function ($item) {
                    $total = $item->harga * $item->qty;
                    $diskon = 0;
                    if (strpos($item->diskon_barang, "+") !== false) {
                        $explode_diskon = explode('+', $item->diskon_barang);
                        foreach ($explode_diskon as $value) {
                            $diskon = intval($value);
                            $total -= $total * $diskon / 100;
                        }
                    } else {
                        $diskon = intval($item->diskon_barang);
                        $total -= $total * $diskon / 100;
                    }
                    return round($total);
                }, $data_order_detail)),
                'details' => array_map(function ($item) {
                    $subtotal = $item->harga * $item->qty;
                    $diskon = 0;
                    if (strpos($item->diskon_barang, "+") !== false) {
                        $explode_diskon = explode('+', $item->diskon_barang);
                        foreach ($explode_diskon as $value) {
                            $diskon = intval($value);
                            $subtotal -= $subtotal * $diskon / 100;
                        }
                    } else {
                        $diskon = intval($item->diskon_barang);
                        $subtotal -= $subtotal * $diskon / 100;
                    }
                    return array_merge(
                        (array)$item,
                        ['qty' => (int)$item->qty],
                        ['harga' => (int)$item->harga],
                        ['subtotal' => round($subtotal)]
                    );
                }, $data_order_detail),
            ]
        ], 200);
    }
}