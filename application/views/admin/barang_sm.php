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

.table tbody tr {
  transition: all 0.2s ease-in-out;
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

.card-header {
  padding: 14px 20px;
  border-bottom: none;
}

.card-header h5 {
  font-weight: 600;
}

.card-header small {
  opacity: 0.8;
}

.btn_tambah {
  margin-bottom: 10px;
}

.btn_import {
  margin-right: 10px;
}

.btn_edit,
.btn_delete {
  border-radius: 6px;
  padding: 4px 8px;
}

.badge {
  font-size: 10px;
  padding: 4px 7px;
  border-radius: 6px;
}

.data-barang {
  padding: 10px;
  border-radius: 6px;
  transition: background 0.2s ease-in-out;
}

.data-barang:hover {
  background-color: #f5f5f5;
}

.form-control-sm {
  font-size: 12px;
}

label {
  font-size: 12px;
  font-weight: 500;
  margin-bottom: 4px;
}

#summary_import .card {
  border-radius: 8px;
  transition: 0.2s;
}

#summary_import .card:hover {
  transform: translateY(-2px);
}

.table-responsive::-webkit-scrollbar {
  width: 6px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background-color: #ccc;
  border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background-color: #999;
}

.custom-header {
  background: #f1f1f1;
  border-radius: 10px 10px 0 0;
  padding: 14px 18px;
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

.btn-import {
  background: #dcdcdc;
  color: #333;
  border: none;
  border-radius: 6px;
  padding: 6px 14px;
  font-weight: 500;
}

.btn-import:hover {
  background: #cfcfcf;
}

.btn-success {
  border-radius: 6px;
  padding: 6px 14px;
  font-weight: 500;
}
.custom-header {
  box-shadow: inset 0 -1px 0 #e0e0e0;
}

.action-group {
  display: flex;
  gap: 6px;
  justify-content: center;
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

.btn-action i {
  font-size: 13px;
}

.btn-warning.btn-action:hover {
  background: #e0a800;
}

.btn-danger.btn-action:hover {
  background: #c82333;
}

.table-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
  margin-bottom: 14px;
  padding: 0 18px;
}

.table-toolbar-left,
.table-toolbar-right {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Tombol Download */
.btn-download-table {
  background: #eef4ff;
  color: #2c7be5;
  border: 1px solid #cfe0ff;
  border-radius: 8px;
  padding: 6px 14px;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s ease-in-out;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-download-table:hover {
  background: #dceaff;
  color: #1a68d1;
  text-decoration: none;
}

/* Tombol Print */
.btn-print-table {
  background: #fff8ee;
  color: #e67e22;
  border: 1px solid #fcd9a8;
  border-radius: 8px;
  padding: 6px 14px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease-in-out;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-print-table:hover {
  background: #fdebd0;
  color: #ca6f1e;
}

.dataTables_filter {
  margin: 0 !important;
}

.dataTables_filter label {
  margin: 0;
  font-size: 12px;
  font-weight: 500;
}

.dataTables_filter input {
  margin-left: 6px !important;
  border: 1px solid #ced4da;
  border-radius: 8px;
  padding: 6px 10px;
  font-size: 12px;
  min-width: 220px;
}

.dataTables_filter input:focus {
  border-color: #2c7be5;
  outline: none;
  box-shadow: 0 0 0 0.15rem rgba(44, 123, 229, 0.15);
}

.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }

/* Keep thead visible when scrolling */
.sticky-top { position: sticky; top: 0; z-index: 1; }

@media (max-width: 768px) {
  .table-toolbar {
    flex-direction: column;
    align-items: stretch;
  }

  .table-toolbar-right {
    width: 100%;
  }

  .dataTables_filter input {
    min-width: 100%;
    margin-left: 0 !important;
    margin-top: 6px;
  }
}
</style>

<div class="card shadow-sm custom-card">
  <!-- Header -->
  <div class="card-header custom-header d-flex justify-content-between align-items-center">
    <!-- Kiri -->
    <div class="d-flex align-items-center">
      <div class="icon-box mr-3 d-flex align-items-center justify-content-center">
        <i class="fas fa-box text-primary"></i>
      </div>
      <div>
        <h5 class="mb-0">Barang Slow moving</h5>
        <small>Manajemen data produk yang kurang laku</small>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped" id="datatable" style="width: 100%;">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode</th>
          <th>Toko</th>
          <th>Total Artikel</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; foreach ($customer as $t) { ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= strtoupper($t->no_pelanggan) ?></td>
          <td><?= $t->nama_customer ?></td>
          <td><?= $t->total_artikel ?></td>
          <td>
            <div class="action-group">
              <button 
                type="button" 
                class="btn btn-warning btn-sm btn_edit"
                data-toggle="modal"
                data-target="#exampleModal"
                onclick="getdetail('<?= $t->id ?>')"
                >
                <i class="fas fa-edit"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <!-- detail modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1">
  <div class="modal-dialog modal-lg"> <!-- modal-lg for wider modal -->
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold">
          <i class="fas fa-store mr-2"></i>Detail Toko
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <!-- Customer Info Card -->
        <div class="card border-left-primary shadow-sm mb-3">
          <div class="card-body py-2">
            <table class="table table-sm table-borderless mb-0">
              <tr>
                <td class="font-weight-bold text-muted" style="width: 80px;">Nama</td>
                <td class="text-muted" style="width: 10px;">:</td>
                <td id="nama_toko" class="font-weight-bold"></td>
              </tr>
              <tr>
                <td class="font-weight-bold text-muted">Kode</td>
                <td class="text-muted">:</td>
                <td id="kode_toko"></td>
              </tr>
              <tr>
                <td class="font-weight-bold text-muted">Alamat</td>
                <td class="text-muted">:</td>
                <td id="alamat_toko"></td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Add Slow Moving -->
        <div class="card border-left-success shadow-sm mb-3">
          <div class="card-body py-2">
            <h6 class="font-weight-bold text-success mb-2">
              <i class="fas fa-plus-circle mr-1"></i>Tambah Data Slow Moving
            </h6>
            <form id="formBarangSm">
              <input type="hidden" name="id_customer" id="id_customer" value="">
              <div class="input-group">
                <select class="custom-select" name="id_artikel" id="artikel">
                  <option value="">--- Pilih Artikel ---</option>
                </select>
                <div class="input-group-append">
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus mr-1"></i>Tambah
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Slow Moving List -->
        <div class="card shadow-sm">
          <div class="card-body py-2">
            <h6 class="font-weight-bold text-dark mb-2">
              <i class="fas fa-list mr-1"></i>Daftar Barang Slow Moving
            </h6>
            <div style="max-height: 250px; overflow-y: auto;">
              <table class="table table-striped table-hover table-sm mb-0">
                <thead class="thead-dark sticky-top">
                  <tr>
                    <th style="width: 40px;">No</th>
                    <th>Kode Artikel</th>
                    <th>Nama Artikel</th>
                    <th style="width: 80px;" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody id="list_sm">
                  <!-- rows injected here -->
                </tbody>
              </table>
            </div>
            <!-- scrollable table -->
            <div id="sm-spinner" class="text-center py-3 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0">Memuat data...</p>
            </div>
          </div>
        </div>

      </div>

      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i>Close
        </button>
      </div>

    </div>
  </div>
  </div>
  <!-- detail modal end -->
</div>

<script>
  let detailXhr = null;
  // submission handler
  $("#formBarangSm").on('submit', function (e) {
    e.preventDefault();

    const id_customer = $("#id_customer").val();
    const id_barang = $("#artikel").val();
    const submitBtn = $("#formBarangSm button[type=submit]");

    if (!id_customer || !id_barang) {
      Swal.fire({
        icon: 'warning',
        title: 'Perhatian!',
        text: 'Pilih artikel terlebih dahulu.',
        confirmButtonColor: '#f6c23e',
      });
      return;
    }

    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');
    Swal.fire({
        title: 'Menyimpan...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
      url: '<?= base_url('Barang_sm/store') ?>',
      type: 'POST',
      data: { 
        id_customer,
        id_barang,
      },
      success: function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data barang slow moving berhasil ditambahkan.',
            confirmButtonColor: '#1cc88a',
            timer: 2000,
            timerProgressBar: true,
        });
        // reset select2 & reload table
        $('#artikel').val(null).trigger('change');
        getdetail(id_customer);
      },
      error: function(error){
        console.error(error);
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: 'Terjadi kesalahan, silakan coba lagi.',
          confirmButtonColor: '#e74a3b',
        });
      }, 
      complete: function() {
        submitBtn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i>Tambah');
      }
    });
  });

  // abort when internet is slow and trying to load every detail data
  $('#exampleModal').on('hidden.bs.modal', function() {
    if (detailXhr) {
        detailXhr.abort();
        detailXhr = null;
    }

    // reset modal state
    $('#sm-spinner').addClass('d-none');
    $('#list_sm').empty();
    $('#artikel').val(null).trigger('change');
  });

  // load detail data
  function getdetail(id) {
    if (detailXhr) {
      detailXhr.abort();
      detailXhr = null;
    }

    $("#sm-spinner").removeClass("d-none");
    $('#list_sm').empty();
    const submitBtn = $("#formBarangSm button[type=submit]");
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

    detailXhr = $.ajax({
      url: '<?= base_url('Barang_sm/show') ?>',
      type: 'GET',
      data: { id_customer: id },
      success: function(response) {
        $('#artikel').select2({
          theme: 'bootstrap4',
          placeholder: '--- Pilih Artikel ---',
          allowClear: true,
          dropdownParent: $('#exampleModal')
        });
        
        const artikelData = response.artikel;
        const toko = response.customer;
        $("input[name=id_customer]").prop("value", toko.id);
        $("#nama_toko").text(toko.nama_customer);
        $("#kode_toko").text(toko.no_pelanggan);
        $("#alamat_toko").text(toko.alamat);
        
        // select fill
        let artikel = $("#artikel");
        artikel.empty();
        artikel.append('<option value="">--- Pilih Artikel ---</option>');
        $.each(artikelData, function (i, item) {
          artikel.append(
            $('<option>', {
              value: item.id,
              text: `(${item.kode_artikel}) ${item.nama_artikel}`
            })
          )
        });

        $("#sm-spinner").addClass("d-none");
        loadTableSm(response.barang_sm);
      },
      error: function(error) {
        console.log(error);
      },
      complete: function(){
        detailXhr = null;
        submitBtn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i>Tambah');
      }
    });
  }

  function loadTableSm(smData) {
    let listSm = $("#list_sm");
    if (!smData || !smData.length) {
      listSm.append(
        `
          <tr>
            <td class="text-center" colspan="4">Barang belum ditambahkan</td>
          </tr>
        `
      );
      return;
    }
    // table fill
    listSm.empty();
    $.each(smData, function(i, item) {
      listSm.append(
        `
          <tr>
            <td>${i + 1}</td>
            <td>${item.kode_artikel}</td>
            <td>${item.nama_artikel}</td>
            <td class="text-center">
              <button class="btn btn-danger btn-sm" onclick="deleteSm(${item.id_bsm})">
                  <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        `
      )
    });
  }

  function deleteSm(id) {
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
        Swal.fire({
          title: 'Menyimpan...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });

        $.ajax({
          url: '<?= base_url('Barang_sm/destroy') ?>',
          type: 'GET',
          data: { id_bsm: id },
          success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data barang slow moving berhasil dihapus.',
                confirmButtonColor: '#1cc88a',
                timer: 2000,          // auto close after 2s
                timerProgressBar: true,
            });
            // reset select2 & reload table
            $('#artikel').val(null).trigger('change');
            const idToko = $("#id_customer").val();
            getdetail(idToko);
          },
        });
      }
    });
  }
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
        location.href = "<?php echo base_url('Barang/hapus_data/') ?>" + id;
      }
    })
  })
</script>