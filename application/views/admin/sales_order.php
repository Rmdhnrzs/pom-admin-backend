<style>
  td {
    font-size: 11px;
    text-align: left;
  }

  tr {
    font-size: 14px;
  }

  .btn_tambah {
    margin-bottom: 10px;
  }
  .modal-content {
    border-radius: 12px;
    border: none;
  }

  .modal-header {
    border-bottom: none;
  }

  .modal-footer {
    border-top: none;
  }

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

  .action-group {
    display: flex;
    justify-content: center;
  }

  .btn-action {
    padding: 4px 10px;
    border-radius: 6px;
  }
</style>
<div class="card shadow-sm custom-card">

  <!-- Header -->
  <div class="card-header custom-header d-flex justify-content-between align-items-center">
    <!-- Kiri -->
    <div class="d-flex align-items-center">
      <div class="icon-box mr-3 d-flex align-items-center justify-content-center">
        <i class="fas fa-file-invoice text-primary"></i>
      </div>
      <div>
        <h5 class="mb-0">Daftar Sales Order</h5>
        <small>Manajemen data pesanan customer</small>
      </div>
    </div>
    <!-- Kanan -->
    <div>
      <button id="btnExport" 
        class="btn btn-info btn-sm" 
        data-toggle="modal" 
        data-target=".exportSo" 
        disabled>
        <i class="fa fa-download"></i> Export
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
            <th class="text-center">
              <input type="checkbox" id="check_btn">
            </th>
            <th>PT</th>
            <th>Customer</th>
            <th>Tipe Harga</th>
            <th>Jenis</th>
            <th>Sales</th>
            <th>No. PO</th>
            <th>Tgl PO</th>
            <th>Status</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($detail as $d) : ?>
          <tr>
            <td class="text-center">
              <input type="checkbox" class="centang" value="<?= $d->id ?>">
            </td>
            <td><?= strtoupper($d->nama_perusahaan) ?></td>
            <td><?= $d->nama_customer ?></td>
            <td><?= $d->tipe_harga ?></td>
            <td><?= jenis_so($d->jenis) ?></td>
            <td><?= $d->sales ?></td>
            <td><?= $d->referensi ?></td>
            <td><?= $d->tanggal_dibuat ?></td>
            <td><?= status_so($d->status) ?></td>
            <td class="text-center">
              <div class="action-group">
                <a href="<?= base_url('Order/detail/' . $d->id) ?>" 
                  class="btn btn-info btn-sm btn-action">
                  <i class="fa fa-eye"></i>
                </a>
              </div>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>

      </table>
    </div>
</div>

<div class="modal fade exportSo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <form id="formExport" action="<?= base_url('Order/export_all') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Export Sales Order</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <label for="">No. Pesanan :</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <?php
              date_default_timezone_set('Asia/Jakarta');
              $tahunBulan = date('Y-m', strtotime('tomorrow'));
              // Format nomor urutan dengan tahun dan bulan
              $nomorUrutan = "SO-$tahunBulan";
              ?>
              <span class="input-group-text" id="basic-addon1"><?= $nomorUrutan ?>-</span>
            </div>
            <input type="text" name="no_urut" id="no_urut" class="form-control" value="0000" maxlength="4" minlength="4" required>
          </div>
          <small class="text-danger">( Mengikuti No. Pesanan di Easy Accounting )</small>
          <div class="form-group">
            <label for="">Est. Tgl. Kirim :</label>
            <input type="date" name="tgl_kirim" id="tgl_kirim" class="form-control form-control-sm" autocomplete="off">
          </div>
          <hr>
          <p><b>Info :</b>
            <small id="info">Data yang sudah di export, akan di pindahkan ke halaman History.</small>
          </p>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id_order" id="id_order" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success btn-sm" id="export-button"><i class="fa fa-save"></i> Export</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $("#check_btn").change(function() {
    $('.centang').prop('checked', $(this).prop('checked'));
    cekTombolEkspor();
  });

  $(".centang").change(function() {
    cekTombolEkspor();
  });


  function cekTombolEkspor() {
    var numberOfChecked = $('.centang:checked').length;
    var numberOfCheckbox = $('.centang').length;
    if (numberOfChecked == 0) {
      $("#btnExport").attr("disabled", true);
    } else {
      $("#btnExport").removeAttr("disabled");
      if (numberOfCheckbox == numberOfChecked) {
        $('#check_btn').prop('checked', true);
      } else {
        $('#check_btn').prop('checked', false);
      }
    }
  };


  $('#export-button').click(function() {
    event.preventDefault(); // Menghentikan eksekusi default (submit) dari tombol
    var urut = $('#no_urut').val();
    var id = [];
    $('.centang').each(function() {
      if ($(this).is(":checked")) {
        id.push($(this).val());
      }
    });
    $("#id_order").val(id);
    var tgl_kirim = $('#tgl_kirim').val();
    if (urut === "" || urut === "0000") {
      Swal.fire(
        'GAGAL',
        'Nomor urut harus diisi atau tidak boleh 0000',
        'error'
      );
    } else {
      // Jalankan submit di sini
      $('#formExport').submit(); // Ganti 'form-id' dengan ID formulir Anda
      $('.exportSo').modal('hide'); // Ganti 'modal-id' dengan ID modal sesuai dengan kode Anda
      Swal.fire(
        'BERHASIL',
        'Data berhasil di export',
        'success'
      ).then((result) => {
        if (result.isConfirmed) {
          window.location.reload();
        }
      });

    }

  });
</script>

<script type="text/javascript">
  $("#perusahaan").val(<?= $id_perusahaan ?>);
  $("#perusahaan").change(function(){
    const perusahaan = $("#perusahaan").val();
    $.ajax({
      url: "<?= base_url('Order') ?>",
      method: "POST",
      dataType: "TEXT",
      data: {
        perusahaan: perusahaan
      },
      success: function(data) {
        location.reload();
      }
    });
    
  })
</script>