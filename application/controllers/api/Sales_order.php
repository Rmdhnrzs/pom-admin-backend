<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'controllers/api/Api_Controller.php';
class Sales_order extends Api_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('cart');
    }

    public function index()
    {
        $this->checkAuth();

        $nama_perusahaan = $this->session->userdata('nama_perusahaan');
        $nama = $this->session->userdata('name');

        return $this->response([
            'status' => true,
            'data' => [
                'nama_perusahaan' => $nama_perusahaan,
                'nama' => $nama
            ]
        ], 200);
    }

    public function list_produk()
    {
        if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $this->checkAuth();

        $id_customer = $this->input->get('id_customer');
        $tipe_customer = $this->input->get('tipe_customer');
        $tipe_po = $this->input->get('tipe_po');
        $perusahaan_id = $this->session->userdata('perusahaan_id');

        if (!isset($id_customer)) {
            return $this->response([
                'status' => false,
                'message' => 'Customer not selected'
            ], 400);
        }

        $tipe = $this->getPriceType($tipe_customer, $tipe_po);

        $produk = $this->db->query("SELECT id, kode_artikel, nama_artikel, satuan, $tipe as harga, size from tb_barang where $tipe > 0 and status=1 and id_perusahaan = '$perusahaan_id' order by kode_artikel")->result();

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

    public function get_cart()
    {
        if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $this->checkAuth();

        $cart = $this->cart->contents();
        return $this->response([
            'status' => true,
            'total_items' => count($cart),
            'data' => $cart
        ], 200);
    }

    public function delete_cart()
    {
        if ($this->input->method() !== 'post') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $this->checkAuth();

        $rowid = $this->input->post('rowid');

        if (!$rowid) {
            return $this->response([
                'status' => false,
                'message' => 'Row ID is required'
            ], 400);
        }

        $this->cart->remove($rowid);

        return $this->response([
            'status' => true,
            'message' => 'Item removed from cart'
        ], 200);
    }

    public function reset_cart()
    {
        if ($this->input->method() !== 'post') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $this->checkAuth();

        $this->cart->destroy();

        return $this->response([
            'status' => true,
            'message' => 'Cart cleared'
        ], 200);
    }

    public function proses()
    {
        if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $this->checkAuth();

        $cart = $this->cart->contents();
        $items = [];

        foreach ($cart as $c) {
            $items[] = $c['id'];
        }

        return $this->response([
            'status' => true,
            'data' => $items
        ], 200);
    }

    public function add_cart()
    {
        if ($this->input->method() !== 'post') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $this->checkAuth();

        $id_produk = $this->input->post('id_produk');
        $tipe_po = $this->session->userdata('tipe_po');
        $id_customer = $this->session->userdata('id_customer');

        if (!$id_produk) {
            return $this->response([
                'status' => false,
                'message' => 'Product ID is required'
            ], 400);
        }

        $data_customer = $this->db->query("SELECT * from tb_customer where id = '$id_customer'")->row();

        if (!$data_customer) {
            return $this->response([
                'status' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        if ($tipe_po == "1") {
            $diskon = $data_customer->margin;
        } else {
            $diskon = "0%";
        }

        $keranjang = $this->cart->contents();
        foreach ($keranjang as $k) {
            if ($k['id'] == $id_produk) {
                return $this->response([
                    'status' => false,
                    'message' => 'Item ini sudah ada di keranjang!'
                ], 409);
            }
        }

        $data = array(
            'id' => $id_produk,
            'qty' => 1,
            'price' => 0,
            'name' => " ",
            'diskon' => $diskon,
        );

        $this->cart->insert($data);

        return $this->response([
            'status' => true,
            'message' => 'Item berhasil ditambahkan!'
        ], 200);
    }

    public function customer()
    {
        if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $this->checkAuth();

        $id_perusahaan = $this->session->userdata('perusahaan_id');
        $customer = $this->db->query("SELECT * from tb_customer where id_perusahaan = '$id_perusahaan' order by nama_customer")->result();

        return $this->response([
            'status' => true,
            'data' => $customer
        ], 200);
    }

    public function simpan_customer()
    {
        if ($this->input->method() !== 'post') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $this->checkAuth();

        $id_customer = $this->input->post('id_customer');
        $tipe_customer = $this->input->post('tipe_customer');
        $nama_customer = $this->input->post('nama_customer');
        $alamat_customer = $this->input->post('alamat_customer');
        $tipe_po = $this->input->post('tipe_po');

        if (!$id_customer || !$tipe_customer || !$tipe_po) {
            return $this->response([
                'status' => false,
                'message' => 'Required fields missing'
            ], 400);
        }

        $data = array(
            'id_customer' => $id_customer,
            'nama_customer' => $nama_customer,
            'tipe_customer' => $tipe_customer,
            'alamat_customer' => $alamat_customer,
            'tipe_po' => $tipe_po,
        );

        $this->session->set_userdata($data);
        $this->cart->destroy();

        return $this->response([
            'status' => true,
            'message' => 'Customer selected successfully'
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

        $this->checkAuth();

        $tanggal = $this->input->get('tanggal');
        if (!$tanggal) {
            $tanggal = date("Y-m-d");
        }

        $pt = $this->session->userdata('perusahaan_id');
        $id_user = $this->session->userdata('id');

        $data_history = $this->db->query("SELECT tb_order.id, tb_order.status, tb_customer.nama_customer, tb_order.tanggal_dibuat, tb_order.jenis from tb_order join tb_customer on tb_customer.id = tb_order.id_customer where tb_order.id_user = '$id_user' and date(tanggal_dibuat) = '$tanggal' and tb_order.id_perusahaan = '$pt' order by tb_order.tanggal_dibuat desc")->result();

        return $this->response([
            'status' => true,
            'tanggal' => $tanggal,
            'data' => $data_history
        ], 200);
    }

    public function history_detail($id)
    {
        if ($this->input->method() !== 'get') {
            return $this->response([
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $this->checkAuth();

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

        $data_order_detail = $this->db->query("SELECT tb_barang.kode_artikel, tb_barang.satuan, tb_order_detail.qty, tb_order_detail.harga, tb_order_detail.diskon_barang from tb_order join tb_order_detail on tb_order_detail.id_order = tb_order.id join tb_barang on tb_barang.id = tb_order_detail.id_barang where tb_order.id = '$id' order by tb_order.tanggal_dibuat")->result();

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
                'details' => $data_order_detail
            ]
        ], 200);
    }
}