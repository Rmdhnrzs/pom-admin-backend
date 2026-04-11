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
        <h5 class="mb-0">Daftar Barang</h5>
        <small>Manajemen data produk dan harga</small>
      </div>
    </div>
    <!-- Kanan -->
     <div>
      <button type="button" class="btn btn-light btn-sm mr-2" data-toggle="modal" data-target="#modal_import">
        <i class="fas fa-file-import"></i> Import Barang
      </button>
      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal_tambah">
        <i class="fas fa-plus"></i> Tambah Barang
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
            <th>Kode</th>
            <th>Barang</th>
            <th>Keterangan</th>
            <th>Size</th>
            <th>Satuan</th>
            <th>Kelipatan</th>
            <th>Retail</th>
            <th>Grosir</th>
            <th>Grosir_10</th>
            <th>HET_Jawa</th>
            <th>Indo_Barat</th>
            <th>SP</th>
            <th>Brg X</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($barang as $k) { ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= strtoupper($k->nama_perusahaan) ?></td>
            <td>
              <?= $k->kode_artikel ?><br>
              <?php if ($k->kategori == 1) { ?>
                <span class="badge badge-danger badge-sm">Barang X</span>
              <?php } ?>
            </td>
            <td><?= $k->nama_artikel ?></td>
            <td><?= $k->keterangan ?></td>
            <td><?= $k->size ?></td>
            <td><?= $k->satuan ?></td>
            <td><?= $k->kelipatan ?></td>
            <td>Rp <?= number_format($k->retail) ?></td>
            <td>Rp <?= number_format($k->grosir) ?></td>
            <td>Rp <?= number_format($k->grosir_10) ?></td>
            <td>Rp <?= number_format($k->het_jawa) ?></td>
            <td>Rp <?= number_format($k->indo_barat) ?></td>
            <td>Rp <?= number_format($k->special_price) ?></td>
            <td>Rp <?= number_format($k->barang_x) ?></td>
            <td>
              <div class="action-group">
                <button type="button" class="btn btn-warning btn-sm btn_edit"
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
 <?php $this->load->view('modal_barang'); ?>
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
      url: '<?= base_url('Barang/getdata') ?>',
      type: 'GET',
      data: {
        id_barang: id
      },
      success: function(response) {
        // Mengisi form dengan data artikel
        if (response) {
          $('#id_barang').val(response.id_barang);
          $('#perusahaan').val(response.perusahaan.toUpperCase());
          $('#kode').val(response.kode);
          $('#keterangan').val(response.keterangan);
          $('#barang').val(response.nama);
          $('#satuan_input').val(response.satuan);
          $('#kelipatan').val(response.kelipatan);
          $('#size_edit').val(response.size);
          $('#kategori').val(response.kategori);
          $('#retail').val(formatRupiah(response.retail));
          $('#grosir').val(formatRupiah(response.grosir));
          $('#grosir_10').val(formatRupiah(response.grosir_10));
          $('#het_jawa').val(formatRupiah(response.het_jawa));
          $('#indo_barat').val(formatRupiah(response.indo_barat));
          $('#sp').val(formatRupiah(response.special_price));
          $('#barang_x').val(formatRupiah(response.barang_x));
          if (response.kategori === '1') { // Membandingkan dengan string "1"
            $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp").prop("readonly", true);
            $("#barang_x").prop("readonly", false);
          } else {
            $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp").prop("readonly", false);
            $("#barang_x").prop("readonly", true);
          }
        }
      },
      error: function(error) {
        console.log(error.responseText);
      }
    });
  }
  $(document).ready(function() {
    // Tambahkan event listener ke input retail, grosir, grosir_10, het_jawa, indo_barat, dan sp
    $('#retail, #grosir, #grosir_10, #het_jawa, #indo_barat, #sp,#barang_x, #retail_add, #grosir_add, #grosir_10_add, #het_jawa_add, #indo_barat_add, #sp_add, #barang_x_add').on('keyup', function() {
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
        location.href = "<?php echo base_url('Barang/hapus_data/') ?>" + id;
      }
    })
  })
</script>
<script>
  $(document).ready(function() {
    $("#kode_add").on("blur", function() {
      var kodeBarang = $(this).val().trim();
      var idPerusahaan = $("#perusahaan_add").val();

      if (!kodeBarang) return;

      if (!idPerusahaan) {
        Swal.fire({
          icon: 'warning',
          title: 'Perusahaan belum dipilih',
          text: 'Silakan pilih perusahaan terlebih dahulu sebelum mengisi kode barang.'
        });
        $('#kode_add').val('');
        return;
      }

      $.ajax({
        url: "<?php echo base_url('Barang/check_kode_exist'); ?>",
        method: "POST",
        data: {
          kode: kodeBarang,
          id_perusahaan: idPerusahaan
        },
        dataType: "json",
        success: function(response) {
          if (response.exist) {
            Swal.fire(
              'Kode Artikel sudah ada',
              'Kode ini sudah digunakan pada perusahaan yang dipilih.',
              'info'
            );
            $('#kode_add').css({ 'border': '1px solid red' });
            $('#kode_add').val('');
          } else if (response.pernah_ada) {
            Swal.fire({
              icon: 'warning',
              title: 'Perhatian',
              text: 'Kode ini pernah digunakan sebelumnya pada perusahaan ini dan sudah dihapus. Yakin ingin menggunakan kode ini?',
              showCancelButton: true,
              confirmButtonText: 'Ya, Lanjut',
              cancelButtonText: 'Batal'
            }).then((result) => {
              if (!result.isConfirmed) {
                $('#kode_add').val('');
                $('#kode_add').css({ 'border': '1px solid orange' });
              } else {
                $('#kode_add').css({ 'border': '' });
              }
            });
          } else {
            $('#kode_add').css({ 'border': '' });
          }
        },
        error: function(xhr) {
          console.log(xhr.responseText);
          Swal.fire('Error', xhr.responseText || 'Gagal memeriksa kode barang.', 'error');
        }
      });
    });

    $("#kategori_add").on("change", function() {
      var kategori = $(this).val();
      if (kategori === "1") {
        $("#retail_add,#grosir_add,#grosir_10_add,#het_jawa_add,#indo_barat_add,#sp_add").prop("readonly", true);
        $("#retail_add,#grosir_add,#grosir_10_add,#het_jawa_add,#indo_barat_add,#sp_add,#barang_x_add").val('');
        $("#barang_x_add").prop("readonly", false);
      } else {
        $("#retail_add,#grosir_add,#grosir_10_add,#het_jawa_add,#indo_barat_add,#sp_add").prop("readonly", false);
        $("#retail_add,#grosir_add,#grosir_10_add,#het_jawa_add,#indo_barat_add,#sp_add,#barang_x_add").val('');
        $("#barang_x_add").prop("readonly", true);
      }
    });

    $("#kategori").on("change", function() {
      var kategori = $(this).val();
      if (kategori === "1") {
        $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp").prop("readonly", true);
        $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp,#barang_x").val('');
        $("#barang_x").prop("readonly", false);
      } else {
        $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp").prop("readonly", false);
        $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp,#barang_x").val('');
        $("#barang_x").prop("readonly", true);
      }
    });
  });
</script>

<script>
  $(document).ready(function() {

    $('#import_perusahaan').on('change', function() {
      if ($('#file_input')[0].files.length > 0) {
        $('#file_input').trigger('change');
      }
    });

    $('#modal_import').on('hidden.bs.modal', function() {
      $('#file_input').val('');
      $('#import_perusahaan').val('');

      $('#preview_excel').addClass('d-none');
      $('#summary_import').addClass('d-none');
      $('#warning_import').addClass('d-none');
      $('#loading_import').addClass('d-none');

      $('#preview_body').html('');
      $('#sum_total, #sum_nochange, #sum_update, #sum_insert, #sum_error').text('0');

      $('#btn_import').prop('disabled', false).text('Import').attr('title', '');
    });

    $('#file_input').on('change', function() {
      let file = this.files[0];
      let id_perusahaan = $('#import_perusahaan').val();

      if (!file) return;

      const maxSize = 5 * 1024 * 1024;
      if (file.size > maxSize) {
        Swal.fire({
          icon: 'warning',
          title: 'File terlalu besar',
          text: 'Ukuran file maksimal 5 MB',
          confirmButtonColor: '#d33',
          confirmButtonText: 'OK'
        });
        $('#file_input').val('');
        return;
      }

      if (!id_perusahaan) {
        Swal.fire({
          icon: 'warning',
          title: 'Perusahaan belum dipilih',
          text: 'Silakan pilih perusahaan terlebih dahulu sebelum upload file',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        });
        $('#file_input').val('');
        return;
      }

      $('#loading_import').removeClass('d-none');
      $('#preview_excel').addClass('d-none');
      $('#summary_import').addClass('d-none');
      $('#warning_import').addClass('d-none');

      let formData = new FormData();
      formData.append('file', file);
      formData.append('id_perusahaan', id_perusahaan);

      $.ajax({
        url: '<?= base_url("Barang/preview_import") ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        success: function(res) {
          $('#loading_import').addClass('d-none');

          if (!res.success) {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: res.error || 'Terjadi kesalahan saat preview import.',
              confirmButtonColor: '#d33',
              confirmButtonText: 'OK'
            });
            return;
          }

          let html = '';
          let summary = res.data.summary || {};
          let selectedPerusahaan = $('#import_perusahaan option:selected').text();

          res.data.items.forEach(function(item) {
            if (item.status !== 'insert' && item.status !== 'update' && item.status !== 'error') return;

            let badge = '';
            let rowClass = '';

            if (item.status === 'insert') {
              badge = '<span class="badge badge-danger">Baru</span>';
              rowClass = 'table-danger';
            } else if (item.status === 'update') {
              badge = '<span class="badge badge-warning">Update</span>';
              rowClass = 'table-warning';
            } else if (item.status === 'error') {
              let errMsg = (item.errors || []).join(', ');
              badge = '<span class="badge badge-dark">Error</span><br><small class="text-danger">' + errMsg + '</small>';
              rowClass = '';
            }

            html += `
              <tr class="${rowClass}">
                <td>${badge}</td>
                <td>${item.kode || '-'}</td>
                <td>${selectedPerusahaan || '-'}</td>
                <td>${item.excel.nama || '-'}</td>
                <td>${item.excel.keterangan || '-'}</td>
                <td>${item.excel.size || '-'}</td>
                <td>${item.excel.satuan || '-'}</td>
                <td>${item.excel.kelipatan || 1}</td>
                <td>${formatRupiah(item.excel.retail || 0)}</td>
                <td>${formatRupiah(item.excel.grosir || 0)}</td>
                <td>${formatRupiah(item.excel.grosir_10 || 0)}</td>
                <td>${formatRupiah(item.excel.het_jawa || 0)}</td>
                <td>${formatRupiah(item.excel.indo_barat || 0)}</td>
                <td>${formatRupiah(item.excel.special_price || 0)}</td>
                <td>${formatRupiah(item.excel.barang_x || 0)}</td>
              </tr>
            `;
          });

          if (html === '') {
            html = '<tr><td colspan="15" class="text-center text-muted">Tidak ada data baru atau perubahan</td></tr>';
          }

          let errCount = (summary.error || 0) + (summary.duplicate || 0);

          $('#preview_body').html(html);
          $('#preview_excel').removeClass('d-none');

          $('#sum_total').text(summary.total || 0);
          $('#sum_nochange').text(summary.no_change || 0);
          $('#sum_update').text(summary.update || 0);
          $('#sum_insert').text(summary.insert || 0);
          $('#sum_error').text(errCount);
          $('#summary_import').removeClass('d-none');

          if (errCount > 0) {
            $('#btn_import')
              .prop('disabled', true)
              .attr('title', 'Tidak bisa import, ada ' + errCount + ' data bermasalah');
          } else {
            $('#btn_import')
              .prop('disabled', false)
              .attr('title', '');
          }

          $('#warning_import').removeClass('d-none');
        },

        error: function(xhr) {
          $('#loading_import').addClass('d-none');
          console.log(xhr.responseText);
          Swal.fire({
            icon: 'error',
            title: 'Server Error',
            text: xhr.responseText || 'Gagal menghubungi server'
          });
        }
      });
    });

    function doImport(form) {
      let formData = new FormData(form);
      $('#btn_import').prop('disabled', true).text('Importing...');

      $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
          $('#btn_import').prop('disabled', false).text('Import');

          if (!res.success) {
            Swal.fire('Error', res.error || 'Import gagal', 'error');
            return;
          }

          Swal.fire({
            icon: 'success',
            title: 'Import Berhasil',
            html: '<b>' + (res.data.inserted || 0) + '</b> data ditambahkan<br><b>' + (res.data.updated || 0) + '</b> data diupdate'
          }).then(() => {
            location.reload();
          });
        },
        error: function(xhr) {
          $('#btn_import').prop('disabled', false).text('Import');
          console.log(xhr.responseText);
          Swal.fire('Error', xhr.responseText || 'Server error', 'error');
        }
      });
    }

    $('#form_import').on('submit', function(e) {
      e.preventDefault();
      doImport(this);
    });

  });
</script>
<script>
  // Aktifkan semua tooltip di halaman
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
