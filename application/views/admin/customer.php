<style>
.table td {
  font-size: 12px;
  vertical-align: middle;
}

.table thead th {
  font-size: 13px;
  font-weight: 600;
  white-space: nowrap;
  position: sticky;
  top: 0;
  z-index: 2;
  background: #fff;
  border-bottom: 2px solid #dee2e6;
}

.table tbody tr:hover {
  background-color: #f8fafc;
}

.table-responsive {
  max-height: calc(100vh - 260px);
  overflow-y: auto;
  border-radius: 8px;
}

.custom-card {
  border-radius: 12px;
  border: none;
  overflow: hidden;
}

.custom-header {
  background: #f1f1f1;
  border-radius: 10px 10px 0 0;
  padding: 14px 18px;
  box-shadow: inset 0 -1px 0 #e0e0e0;
}

.icon-box {
  width: 36px;
  height: 36px;
  background: #e7f1ff;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.custom-header h5 {
  font-weight: 600;
  color: #2c7be5;
}

.custom-header small {
  color: #888;
  font-size: 12px;
}

.btn-action {
  width: 34px;
  height: 34px;
  padding: 0;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.action-group {
  display: flex;
  gap: 6px;
  justify-content: center;
}

<style>
.modal-content {
  border-radius: 12px;
  border: none;
  overflow: hidden;
}

.modal-header {
  padding: 14px 18px;
  border-bottom: none;
}

.modal-title {
  font-weight: 600;
  font-size: 15px;
}

.modal-body label {
  font-size: 12px;
  font-weight: 500;
  margin-bottom: 4px;
}

.form-control-sm {
  font-size: 12px;
}

.modal-footer {
  border-top: none;
}
</style>

<div class="card shadow-sm custom-card">

  <!-- Header -->
  <div class="card-header custom-header d-flex justify-content-between align-items-center">
    
    <!-- kiri -->
    <div class="d-flex align-items-center">
      <div class="icon-box mr-3 d-flex align-items-center justify-content-center">
        <i class="fas fa-users text-primary"></i>
      </div>
      <div>
        <h5 class="mb-0">Daftar Customer</h5>
        <small>Manajemen data customer & penjualan</small>
      </div>
    </div>

    <!-- kanan -->
    <div>
      <button type="button" 
        class="btn btn-success btn-sm" 
        data-toggle="modal" 
        data-target="#modal_tambah">
        <i class="fas fa-plus"></i> Tambah Customer
      </button>
    </div>

  </div>
  <div class="card-body">

    <div class="d-flex justify-content-between mb-3">
      <div id="dt-buttons"></div>
      <div id="dt-search"></div>
    </div>

    <div class="table-responsive">
      <table class="table table-striped" id="datatable" style="width: 100%;">
        <thead>
          <tr>
            <th>No</th>
            <th>PT</th>
            <th>No. Pelanggan</th>
            <th>Nama Customer</th>
            <th>Area</th>
            <th>Min Order</th>
            <th>Tipe Harga</th>
            <th>Margin</th>
            <th>Sales</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($customer as $k) { ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= strtoupper($k->nama_perusahaan) ?></td>
            <td><?= $k->no_pelanggan ?></td>
            <td><?= $k->nama_customer ?></td>
            <td><?= $k->area ?></td>
            <td>Rp <?= number_format($k->minimum_order) ?></td>
            <td><?= $k->tipe_harga ?></td>
            <td><?= $k->margin ?></td>
            <td><?= (!empty($k->sales) ? $k->sales : "<span class='text-danger'>Belum ada</span>") ?></td>
            <td>
              <div class="action-group">
                <button type="button"
                  class="btn btn-warning btn-sm btn-action btn_edit"
                  data-toggle="modal"
                  data-target="#exampleModalCenter"
                  onclick="getdetail('<?= $k->id ?>')">
                  <i class="fas fa-edit"></i>
                </button>

                <button class="btn btn-danger btn-sm btn-action btn_delete"
                  data-id="<?= $k->id ?>">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
<!-- tambah barang -->
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="modal_tambah" tabindex="-1" data-backdrop="static">
  <form action="<?= base_url('Customer/simpan') ?>" method="POST">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Tambah Customer</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">

          <div class="form-group">
            <label>Perusahaan</label>
            <select name="perusahaan" class="form-control form-control-sm" required>
              <option value="">-- Pilih Perusahaan --</option>
              <?php foreach ($perusahaan as $p) { ?>
                <option value="<?= $p->id ?>"><?= $p->nama ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6">

              <div class="form-group">
                <label>No Pelanggan</label>
                <input type="text" name="no_pelanggan" id="no_pelanggan" class="form-control form-control-sm" required>
                <small class="text-danger">(dari easy accounting)</small>
              </div>

              <div class="form-group">
                <label>Nama Customer</label>
                <input type="text" name="customer" id="customer_add" class="form-control form-control-sm" required>
              </div>

              <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control form-control-sm" rows="3" required></textarea>
              </div>

              <div class="form-group">
                <label>Area</label>
                <select name="area" class="form-control form-control-sm" required>
                  <option value="">- Pilih Area -</option>
                  <option>SUMATRA</option>
                  <option>JAKARTA</option>
                  <option>JAWA BARAT</option>
                  <option>JAWA TENGAH</option>
                  <option>JAWA TIMUR</option>
                  <option>BALI</option>
                  <option>NTB</option>
                  <option>KALIMANTAN 2</option>
                </select>
              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">
                <label>Minimum Order</label>
                <input type="text" name="minimum_order" id="min_order_add" class="form-control form-control-sm" required>
              </div>

              <div class="form-group">
                <label>Tipe Harga</label>
                <select name="tipe_harga" class="form-control form-control-sm" required>
                  <option value="">- Pilih Harga -</option>
                  <option>GROSIR</option>
                  <option>GROSIR+10</option>
                  <option>RETAIL</option>
                  <option>HET JAWA</option>
                  <option>INDO BARAT</option>
                  <option>SPECIAL PRICE</option>
                </select>
              </div>

              <div class="form-group">
                <label>Termasuk Pajak</label>
                <select name="termasuk_pajak" class="form-control form-control-sm" required>
                  <option value="">- Pilih -</option>
                  <option value="1">Ya</option>
                  <option value="0">Tidak</option>
                </select>
              </div>

              <div class="form-group">
                <label>Margin</label>
                <input type="text" name="margin" class="form-control form-control-sm">
              </div>

              <div class="form-group">
                <label>Sales</label>
                <select name="sales" class="form-control form-control-sm">
                  <option value="">- Pilih Sales -</option>
                  <?php foreach ($sales as $s) { ?>
                    <option value="<?= $s->id ?>"><?= $s->nama ?></option>
                  <?php } ?>
                </select>
              </div>

            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success btn-sm">Simpan</button>
        </div>

      </div>
    </div>
  </form>
</div>
<!-- end tambah -->

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
  <form action="<?= base_url('Customer/update') ?>" method="POST">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Edit Customer</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Perusahaan :</label>
            <input id="perusahaan" type="text" class="form-control" disabled>
          </div>
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label for="">No. Pelanggan:</label>
                <input type="text" name="no_pelanggan" id="no_pelanggan_edit" class="form-control form-control-sm" required>
                <small class="text-danger">( No pelanggan dari easy accounting )</small>
              </div>
              <div class="form-group">
                <label for="">Nama Customer:</label>
                <input type="text" name="customer" id="customer" class="form-control form-control-sm" required>
                <input type="hidden" name="id_cust" id="id_cust" class="form-control form-control-sm">
              </div>
              <div class="form-group">
                <label for="">Alamat :</label>
                <textarea name="alamat" id="alamat" class="form-control form-control-sm" required rows="5"></textarea>
              </div>
              <div class="form-group">
                <label for="">Area :</label>
                <select name="area" id="area" class="form-control form-control-sm" required>
                  <option value="">- Pilih Area -</option>
                  <option value="SUMATRA">SUMATRA</option>
                  <option value="JAKARTA">JAKARTA</option>
                  <option value="JAWA BARAT">JAWA BARAT</option>
                  <option value="JAWA TENGAH">JAWA TENGAH</option>
                  <option value="JAWA TIMUR">JAWA TIMUR</option>
                  <option value="BALI">BALI</option>
                  <option value="NTB">NTB</option>
                  <option value="KALIMANTAN 2">KALIMANTAN 2</option>
                </select>
              </div>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-5">
              <div class="form-group">
                <label for="">Min Order :</label>
                <input type="text" name="minimum_order" id="min_order" class="form-control form-control-sm" required>
              </div>
              <div class="form-group">
                <label for="">Tipe Harga :</label>
                <select name="tipe_harga" id="tipe_harga" class="form-control form-control-sm" required>
                  <option value="">- Pilih Harga -</option>
                  <option value="GROSIR">GROSIR</option>
                  <option value="GROSIR+10">GROSIR+10</option>
                  <option value="RETAIL">RETAIL</option>
                  <option value="HET JAWA">HET JAWA</option>
                  <option value="INDO BARAT">INDO BARAT</option>
                  <option value="SPECIAL PRICE">SPECIAL PRICE</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Termasuk Pajak :</label>
                <select name="termasuk_pajak" id="termasuk_pajak" class="form-control form-control-sm" required>
                  <option value="">- Silahkan Pilih -</option>
                  <option value="1">Ya</option>
                  <option value="0">Tidak</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Margin :</label>
                <input type="text" name="margin" id="margin" class="form-control form-control-sm">
              </div>
              <div class="form-group">
                <label for="">Sales :</label>
                <select name="sales" id="sales_edit" class="form-control form-control-sm">
                  <option value="">- Pilih Sales -</option>
                  <?php
                  foreach ($sales as $s) :
                  ?>
                    <option value="<?= $s->id ?>"><?= $s->nama ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
          </div>
        </div>
      </div>
  </form>
</div>

<script>
  function formatRupiah(angka) {
    var number_string = angka.toString().replace(/[^,\d]/g, ""),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? "." : "";
      rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return "Rp " + rupiah;
  }

  function getdetail(id) {
    // Menggunakan Ajax untuk mengambil data artikel dari server
    $.ajax({
      url: '<?= base_url('Customer/getdata') ?>',
      type: 'GET',
      data: {
        id_cust: id
      },
      success: function(response) {
        // Mengisi form dengan data artikel
        if (response) {
          $('#id_cust').val(response.id_cust);
          $('#perusahaan').val(response.perusahaan.toUpperCase());
          $('#no_pelanggan_edit').val(response.no_pelanggan);
          $('#customer').val(response.nama_customer);
          $('#alamat').val(response.alamat);
          $('#area').val(response.area);
          $('#tipe_harga').val(response.tipe_harga);
          $('#margin').val(response.margin);
          $('#sales_edit').val(response.id_sales);
          $('#termasuk_pajak').val(response.termasuk_pajak);
          $('#min_order').val(formatRupiah(response.min_order));
        }
      },
      error: function(xhr, status, error) {
        console.log(error);
      }
    });
  }
  $(document).ready(function() {
    // Tambahkan event listener ke input retail, grosir, grosir_10, het_jawa, indo_barat, dan sp
    $('#min_order').on('keyup', function() {
      var angka = $(this).val().replace(/[Rp.,]/g, ''); // Hilangkan karakter 'Rp', '.' dan ',' dari nilai input
      var rupiah = formatRupiah(angka); // Ubah nilai menjadi format rupiah
      $(this).val(rupiah); // Set nilai input menjadi format rupiah
    });
    $('#min_order_add').on('keyup', function() {
      var angka = $(this).val().replace(/[Rp.,]/g, ''); // Hilangkan karakter 'Rp', '.' dan ',' dari nilai input
      var rupiah = formatRupiah(angka); // Ubah nilai menjadi format rupiah
      $(this).val(rupiah); // Set nilai input menjadi format rupiah
    });
  });
</script>

<script>
  $('.btn_delete').click(function(e) {
    const id = $(this).data('id');
    e.preventDefault();
    Swal.fire({
      title: 'Hapus Data',
      text: "Apakah anda yakin untuk Menghapusnya ?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Batal',
      confirmButtonText: 'Yakin'
    }).then((result) => {
      if (result.isConfirmed) {
        location.href = "<?php echo base_url('Customer/hapus_data/') ?>" + id;
      }
    })
  })
</script>
<script>
  $(document).ready(function() {
    // Fungsi untuk melakukan pemeriksaan kode barang saat pengguna mengetikkan
    $("#customer_add, #customer").on("blur", function() {
      var kodeBarang = $(this).val();
      if (kodeBarang != "") {
        // Mengirimkan kode barang ke server untuk memeriksa keberadaannya di database
        $.ajax({
          url: "<?php echo base_url('Customer/cek_customer'); ?>",
          method: "POST",
          data: {
            kode: kodeBarang
          },
          dataType: "json",
          success: function(response) {
            if (response.exist) {
              // Jika kode sudah ada di database, tampilkan pesan
              Swal.fire(
                'Nama Customer sudah ada',
                'Harap cek kembali / gunakan nama yang lain',
                'info'
              );
              $('#customer_add').css({
                'border': '1px solid red'
              });
              $('#customer_add').val('');
              $('#customer_add').focus();
            }
          }
        });
      }

    });

    // cek no pelanggan
    // $("#no_pelanggan, #no_pelanggan_edit").on("blur", function() {
    //   var kodeBarang = $(this).val();
    //   if (kodeBarang != "") {
    //     // Mengirimkan kode barang ke server untuk memeriksa keberadaannya di database
    //     $.ajax({
    //       url: "<?php echo base_url('Customer/no_pelanggan'); ?>",
    //       method: "POST",
    //       data: {
    //         kode: kodeBarang
    //       },
    //       dataType: "json",
    //       success: function(response) {
    //         if (response.exist) {
    //           // Jika kode sudah ada di database, tampilkan pesan
    //           Swal.fire(
    //             'No Pelanggan sudah ada',
    //             'Harap cek kembali / gunakan nama yang lain',
    //             'info'
    //           );
    //           $('#no_pelanggan').css({
    //             'border': '1px solid red'
    //           });
    //           $('#no_pelanggan').val('');
    //           $('#no_pelanggan').focus();
    //         }
    //       }
    //     });
    //   }

    // });
  });
</script>