<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'controllers/api/Api_Controller.php';
class Keranjang_Api extends Api_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
    }

    // -----------------------------------------------------------------------
    // HELPER: resolve price column from tipe_po + tipe_customer
    // -----------------------------------------------------------------------
    private function _resolve_tipe($tipe_po, $tipe_customer) {
        if ($tipe_po == 1) {
            $map = [
                'RETAIL'     => 'retail',
                'GROSIR'     => 'grosir',
                'GROSIR+10'  => 'grosir_10',
                'HET JAWA'   => 'het_jawa',
                'INDO BARAT' => 'indo_barat',
            ];
            return $map[$tipe_customer] ?? null;
        }
        if ($tipe_po == 2) return 'special_price';
        if ($tipe_po == 3) return 'barang_x';
        return null;
    }

    // -----------------------------------------------------------------------
    // POST /keranjang/proses
    // Checkout: save order + order details from FormData
    //
    // Body (multipart/form-data):
    //   id_customer         : int
    //   tipe_po             : int (1|2|3)
    //   catatan             : string (optional)
    //   referensi           : string (optional)
    //   lampiran            : file   (optional)
    //   items[0][id_barang] : int
    //   items[0][qty]       : int
    //   items[0][diskon]    : string e.g. "10%" (optional, defaults "0%")
    //   items[1][id_barang] : int
    //   ...
    // -----------------------------------------------------------------------
	private function sub_total ($harga, $qty, $diskonStr) {
		$total = $harga * $qty;
		$diskon = 0;
		if (strpos($diskonStr, "+") !== false) {
			$explode_diskon = explode('+', $diskonStr);
			foreach ($explode_diskon as $value) {
				$diskon = intval($value);
				$total -= $total * $diskon / 100;
			}
		} else {
			$diskon = intval($diskonStr);
			$total -= $total * $diskon / 100;
		}
		return $total;
	}
    public function proses() {
        $id_customer   = $this->input->post('id_customer');
        $tipe_po       = $this->input->post('tipe_po');
        $catatan       = $this->input->post('catatan');
        $referensi     = $this->input->post('referensi');
        $items         = $this->input->post('items');   // array of cart rows
        $id_user       = $this->input->post('id_user');
        $id_perusahaan = $this->input->post('perusahaan_id');
        $diskon_faktur = '0%';

        // -- Validate required fields --
        if (!$id_customer || !$tipe_po) {
            return $this->response([
                'status'  => false,
                'message' => 'id_customer dan tipe_po wajib diisi.',
            ], 400);
        }

        if (empty($items) || !is_array($items)) {
            return $this->response([
                'status'  => false,
                'message' => 'Items tidak boleh kosong.',
            ], 400);
        }

        // -- Resolve tipe_customer from DB (never trust client for this) --
        $customer = $this->db->query(
            "SELECT tipe_harga FROM tb_customer WHERE id = ?",
            [$id_customer]
        )->row();

        if (!$customer) {
            return $this->response([
                'status'  => false,
                'message' => 'Customer tidak ditemukan.',
            ], 404);
        }

        $tipe = $this->_resolve_tipe($tipe_po, $customer->tipe_harga);
        if (!$tipe) {
            return $this->response([
                'status'  => false,
                'message' => 'Tipe customer atau tipe PO tidak valid.',
            ], 400);
        }

        // -- Validate each item and pre-fetch prices from DB --
        $resolved_items = [];
        foreach ($items as $index => $item) {
            $id_barang = $item['id_barang'] ?? null;
            $qty       = intval($item['qty']) ?? null;
            $diskon    = $item['diskon'] ?? '0%';
			
            if (!$id_barang || !$qty || (int)$qty <= 0) {
                return $this->response([
                    'status'  => false,
                    'message' => "Item index $index tidak valid: id_barang dan qty wajib diisi.",
                ], 400);
            }

            $produk = $this->db->query(
                "SELECT id, $tipe AS harga FROM tb_barang WHERE id = ?",
                [$id_barang]
            )->row();

            if (!$produk) {
                return $this->response([
                    'status'  => false,
                    'message' => "Barang dengan id $id_barang tidak ditemukan.",
                ], 404);
            }
            $resolved_items[] = [
                'id_barang' => $id_barang,
                'qty'       => (int)$qty,
                'harga'     => $this->sub_total($produk->harga, $qty, $diskon),  // always from DB
                'diskon'    => $diskon,
            ];
        }

        if (!$referensi) {
            $referensi = generateKodePo($id_perusahaan);
        }

        // -- File upload (optional) --
        $filename = null;
        if (!empty($_FILES['lampiran']['name'])) {
            $upload_name = date('ymdHis') . '_' . uniqid();
            $config = [
                'upload_path'   => 'assets/file/',
                'allowed_types' => 'jpeg|jpg|png|pdf|doc|docx',
                'max_size'      => 5000,
                'file_name'     => $upload_name,
                'overwrite'     => true,
            ];
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('lampiran')) {
                return $this->response([
                    'status'  => false,
                    'message' => 'Upload file gagal.',
                    'error'   => strip_tags($this->upload->display_errors()),
                ], 422);
            }
            $filename = $this->upload->data('file_name');
        }

        // -- DB Transaction --
        $this->db->trans_start();

        $this->db->insert('tb_order', [
            'id_customer'    => $id_customer,
            'id_user'        => $id_user,
            'id_perusahaan'  => $id_perusahaan,
            'jenis'          => $tipe_po,
            'tanggal_dibuat' => date('Y-m-d H:i:s'),
            'diskon'         => $diskon_faktur,
            'referensi'      => $referensi,
            'no_faktur'      => '-',
            'catatan'        => $catatan,
            'file'           => $filename,
            'status'         => 0,
        ]);
        $id_order = $this->db->insert_id();

        foreach ($resolved_items as $item) {
            $this->db->insert('tb_order_detail', [
                'id_order'      => $id_order,
                'id_barang'     => $item['id_barang'],
                'qty'           => $item['qty'],
                'harga'         => $item['harga'],
                'diskon_barang' => $item['diskon'],
            ]);
        }

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            return $this->response([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat menyimpan order.',
            ], 500);
        }

        return $this->response([
            'status'    => true,
            'message'   => 'Order berhasil dibuat.',
            'id_order'  => $id_order,
            'referensi' => $referensi,
        ], 201);
    }
}