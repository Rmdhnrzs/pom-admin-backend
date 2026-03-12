<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keranjang extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if ($this->session->userdata('login') == false) {
			redirect(base_url('auth_mobile'));
		}
		$this->load->library('cart');
	}

	public function index()
	{
		$id_customer = $this->session->userdata('id_customer');
		$tipe_customer = $this->session->userdata('tipe_customer');
		$nama_customer = $this->session->userdata('nama_customer');
		$tipe_po = $this->session->userdata('tipe_po');
		if (!isset($id_customer)) {
			redirect(base_url('sales_order/customer'));
		}
		$data['view'] = 'keranjang';
		$data['title'] = 'Keranjang';
		$diskon_faktur = $this->session->userdata('diskon_faktur');
		if (isset($diskon_faktur)) {
			$diskon_faktur = $diskon_faktur;
		} else {
			$diskon_faktur = "0%";
		}
		
		$data['diskon_faktur'] = $diskon_faktur;
		$keranjang = $this->cart->contents();
		$jumlah_keranjang = count($keranjang);
		if ($jumlah_keranjang == 0) {
			redirect(base_url('sales_order/list_produk'));
		}
		$subtotal = 0;
		$data_list = [];
		foreach ($keranjang as $k) {
			$id_barang = $k['id'];
			if($tipe_po == 1) {
				if ($tipe_customer == 'RETAIL') {
		          $tipe = "retail";
		        } elseif ($tipe_customer == 'GROSIR') {
		          $tipe = "grosir";
		        } elseif ($tipe_customer == 'GROSIR+10') {
		          $tipe = "grosir_10";
		        } elseif ($tipe_customer == 'HET JAWA') {
		          $tipe = "het_jawa";
		        } elseif ($tipe_customer == 'INDO BARAT') {
		          $tipe = "indo_barat";
		        }
			} elseif($tipe_po == 2) {
				$tipe = "special_price";
			} elseif($tipe_po == 3) {
				$tipe = "barang_x";
			}
			$data_produk = $this->db->query("SELECT id, kode_artikel, nama_artikel, satuan, $tipe as harga, size from tb_barang where id = '$id_barang' ")->row();
			$harga = $data_produk->harga;
			$diskon = $k['diskon'];
			$harga_diskon = hitung_diskon($harga,$diskon);
			$qty = $k['qty'];
			$total_harga = $harga_diskon * $qty;
			$subtotal += $total_harga;
			$nama_artikel = $data_produk->nama_artikel;
			$kode_artikel = $data_produk->kode_artikel;
			$satuan = $data_produk->satuan;
			$size = $data_produk->size;
			$data_list[] = (object) array(
				'rowid' => $k['rowid'],
				'id' => $id_barang,
				'kode_artikel' => $kode_artikel,
				'nama_artikel' => $nama_artikel,
				'harga' => $harga,
				'satuan' => $satuan,
				'size' => $size,
				'qty' => $qty,
				'diskon' => $diskon,
			);
		}

		$data['list'] = $data_list;
		$data['subtotal'] = $subtotal;
		$grandtotal = hitung_diskon($subtotal,$diskon_faktur);
		$data['grandtotal'] = $grandtotal;
		$data['tipe_po'] = $tipe_po;
		$data['nama_customer'] = $nama_customer;
		$data['diskon_faktur'] = $diskon_faktur;
		$this->load->view('mobile/header.php',$data);
		$this->load->view('mobile/mobile.php',$data);
		$this->load->view('mobile/footer.php');
	}

	public function edit_item(){
		$rowid = $this->input->post('rowid');
		$qty = $this->input->post('qty');
		$diskon = $this->input->post('diskon');
		if ($diskon) {
			$data = array(
		        'rowid' => $rowid,
		        'qty'   => $qty,
		        'diskon'=> $diskon
			);
		} else {
			$data = array(
		        'rowid' => $rowid,
		        'qty'   => $qty
			);
		}
		
		$update = $this->cart->update($data);

		if ($update) {
			$data['sukses'] = true;
		}
		echo json_encode($data);
	}

	public function edit_diskon_faktur(){
		$diskon = $this->input->post('diskon_faktur');
		$data = array('diskon_faktur' => $diskon);
		$update = $this->session->set_userdata($data);
		redirect(base_url('keranjang'));
	}

	public function delete_cart($id)
	{
		$cart = $this->cart->remove($id);
		redirect(base_url('keranjang'));
	}

	public function proses()
	{
		$catatan = $this->input->post('catatan');
		$id_customer = $this->session->userdata('id_customer');
		$id_user = $this->session->userdata('id');
		$tipe_customer = $this->session->userdata('tipe_customer');
		$tipe_po = $this->session->userdata('tipe_po');
		$id_perusahaan = $this->session->userdata('perusahaan_id');
		$diskon_faktur = "0%";
		$referensi = $this->input->post('referensi');
		$keranjang = $this->cart->contents();
		$filename = date("ymdHis")."_".uniqid();

		if ($referensi == null) {
			$referensi = generateKodePo($id_perusahaan);
		}

		// upload file
		$config['upload_path']          = 'assets/file/';
        $config['allowed_types']        = 'jpeg|jpg|png|pdf|doc|docx';
        $config['max_size']             = 5000;
        $config['file_name']            = $filename;
        $config['overwite']             = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('lampiran')) {
        	$data['error'] = $this->upload->display_errors();
        } else {
        	$filename = $this->upload->data('file_name');
			if (isset($diskon_faktur)) {
				$diskon_faktur = $diskon_faktur;
			} else {
				$diskon_faktur = "0%";
			}

			$this->db->trans_start();

			//insert ke tb_order
			$data_order = array(
				'id_customer' => $id_customer,
				'id_user' => $id_user,
				'id_perusahaan' => $id_perusahaan,
				'jenis' => $tipe_po,
				'tanggal_dibuat' => date('Y-m-d H:i:s'),
				'diskon' => $diskon_faktur,
				'referensi' => $referensi,
				'no_faktur' => "-",
				'catatan' => $catatan,
				'file' => $filename,
				'status' => 0,
			);
			$this->db->insert('tb_order',$data_order);
			$id_order = $this->db->insert_id();

			// insert ke tb_order_detail
			foreach ($keranjang as $k) {
				$id_barang = $k['id'];
				if($tipe_po == 1) {
					if ($tipe_customer == 'RETAIL') {
			          $tipe = "retail";
			        } elseif ($tipe_customer == 'GROSIR') {
			          $tipe = "grosir";
			        } elseif ($tipe_customer == 'GROSIR+10') {
			          $tipe = "grosir_10";
			        } elseif ($tipe_customer == 'HET JAWA') {
			          $tipe = "het_jawa";
			        } elseif ($tipe_customer == 'INDO BARAT') {
			          $tipe = "indo_barat";
			        }
				} elseif($tipe_po == 2) {
					$tipe = "special_price";
				} elseif($tipe_po == 3) {
					$tipe = "barang_x";
				}
				$data_produk = $this->db->query("SELECT id, kode_artikel, nama_artikel, satuan, $tipe as harga from tb_barang where id = '$id_barang' ")->row();
				$harga = $data_produk->harga;
				$diskon = $k['diskon'];
				$qty = $k['qty'];
				$data_order_detail = array(
					'id_order' => $id_order,
					'id_barang' => $k['id'],
					'qty' => $qty,
					'harga' => $harga,
					'diskon_barang' => $diskon
				);

				$this->db->insert('tb_order_detail',$data_order_detail);
			}

			$data = array('id_customer','nama_customer','tipe_customer','alamat_customer','tipe_po','diskon_faktur');
			$this->session->unset_userdata($data);

			if($this->db->trans_complete()){
				$data['status'] = '1';
			}

		}


		echo json_encode($data);
	}

	public function subtotal(){
		$tipe_customer = $this->session->userdata('tipe_customer');
		$tipe_po = $this->session->userdata('tipe_po');
		$diskon_faktur = "0%";
		if (isset($diskon_faktur)) {
			$diskon_faktur = $diskon_faktur;
		} else {
			$diskon_faktur = "0%";
		}
		
		$keranjang = $this->cart->contents();
		$subtotal = 0;
		foreach ($keranjang as $k) {
			$id_barang = $k['id'];
			if($tipe_po == 1) {
				if ($tipe_customer == 'RETAIL') {
		          $tipe = "retail";
		        } elseif ($tipe_customer == 'GROSIR') {
		          $tipe = "grosir";
		        } elseif ($tipe_customer == 'GROSIR+10') {
		          $tipe = "grosir_10";
		        } elseif ($tipe_customer == 'HET JAWA') {
		          $tipe = "het_jawa";
		        } elseif ($tipe_customer == 'INDO BARAT') {
		          $tipe = "indo_barat";
		        }
			} elseif($tipe_po == 2) {
				$tipe = "special_price";
			} elseif($tipe_po == 3) {
				$tipe = "barang_x";
			}
			$data_produk = $this->db->query("SELECT id, kode_artikel, nama_artikel, satuan, $tipe as harga from tb_barang where id = '$id_barang' ")->row();
			$harga = $data_produk->harga;
			$diskon = $k['diskon'];
			$harga_diskon = hitung_diskon($harga,$diskon);
			$qty = $k['qty'];
			$total_harga = $harga_diskon * $qty;
			$subtotal += $total_harga;
		}

		$data['total'] = "<b>Rp. ".rupiah($subtotal)."</b>";
		// $data['grandtotal'] = "Rp. ".rupiah(hitung_diskon($subtotal,$diskon_faktur));
		echo json_encode($data);
	}
}