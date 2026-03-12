<?php
class Model_barang extends CI_Model {


	function get_satuan($id){
		$barang = $this->db->query("SELECT satuan FROM tb_barang where kode_barang = '$id'")->row();
		if ($barang) {
			$barang = $barang->satuan;
		} else {
			$barang = "-";
		}
		return $barang;
	}



}