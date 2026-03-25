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

  .upload-box {
    border: 2px dashed #dcdcdc;
    padding: 12px;
    border-radius: 10px;
    background: #fafafa;
  }

  .upload-box:hover {
    background: #f1f5f9;
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
</style>
<div class="card shadow-sm custom-card">

  <div class="card-header custom-header d-flex justify-content-between align-items-center">

    <div class="d-flex align-items-center">
      <div class="icon-box mr-3 d-flex align-items-center justify-content-center">
        <i class="fas fa-warehouse text-primary"></i>
      </div>
      <div>
        <h5 class="mb-0">Manajemen Gudang</h5>
        <small>Import & monitoring stok barang</small>
      </div>
    </div>

  </div>

  <div class="card-body">
  <div class="upload-box mb-3">
  <form class="input-group" id="formImpor" method="post" enctype="multipart/form-data">
    
    <div class="custom-file">
      <input id="excel_file" class="custom-file-input" type="file" name="excel_file" accept=".xlsx,.xls,.csv">
      <label for="excel_file" class="custom-file-label">Pilih file Excel...</label>
    </div>

    <div class="input-group-append">
      <button type="submit" class="btn btn-info">
        <i class="fa fa-upload"></i> Upload
      </button>
    </div>

  </form>
</div>
<div class="table-responsive">
  <table class="table table-striped" id="datatable" style="width: 100%;">
    <thead>
      <tr>
        <th>No</th>
        <th>Kode Artikel</th>
        <th>Nama Artikel</th>
        <th class="text-center">Stok</th>
        <th>Terakhir Update</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (isset($barang)):
        $no = 1;
        foreach ($barang as $row):
          ?>
          <tr>
            <td><?php echo $no++ ?></td>
            <td><?php echo $row->kode_artikel ?></td>
            <td><?php echo $row->nama_artikel ?></td>
            <td class="text-center">
              <span class="badge badge-info">
                <?= $row->stok ?>
              </span>
            </td>
            <td>
              <?php echo $row->updated_stok_at ? date('d-m-Y, H:i', strtotime($row->updated_stok_at)) : '-' ?>
            </td>
          </tr>
          <?php
        endforeach;
      endif;
      ?>
    </tbody>
  </table>
  <div class="modal fade" id="modalPreviewImpor" tabindex="-1" aria-labelledby="modalPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="modalPreviewLabel">
            <i class="fas fa-table me-2"></i> Preview Import — Item Cocok
          </h5>
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <!-- Loading state -->
          <div id="previewLoading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Membaca file Excel...</p>
          </div>

          <!-- Content (hidden until loaded) -->
          <div id="previewContent" class="d-none">
            <div class="alert alert-info d-flex align-items-center mb-3" id="previewSummary"></div>
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                  <tr>
                    <th>No</th>
                    <th>Kode Artikel</th>
                    <th>Nama Barang</th>
                    <th>Stok DB (lama)</th>
                    <th>Stok Excel (baru)</th>
                    <th>Status Stok</th>
                    <th>Ketersediaan</th>
                  </tr>
                </thead>
                <tbody id="previewTableBody"></tbody>
              </table>
            </div>
          </div>

          <!-- Empty state -->
          <div id="previewEmpty" class="d-none text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Tidak ada item yang cocok antara Excel dan database.</p>
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-between align-items-start">
          <div class="me-2">
            <p>
              <strong>Keterangan:</strong><br>
              <span class="bg-success">&nbsp;&nbsp;&nbsp;</span> Data stok sama dan ada di kedua tempat (Database dan Excel)<br>
              <span class="bg-danger">&nbsp;&nbsp;&nbsp;</span> Data tidak ada di Excel tapi ada di Database <br>
              <span class="bg-warning">&nbsp;&nbsp;&nbsp;</span> Data ada di kedua tempat dan stoknya berbeda <br>
              Ketika dikonfirmasi, Stok di Database akan terupdate dengan yang di Excel
            </p>
          </div>
          <div class="d-flex gap-4">
            <button type="button" id="btnCancel" class="btn btn-secondary mr-2" data-dismiss="modal">
              Batal
            </button>
            <form action="<?php echo base_url('gudang/confirm_impor') ?>" id="formConfirm">
              <!-- <input type="hidden" name="<#php echo $this->security->get_csrf_token_name() ?>"
                          value="<#php echo $this->security->get_csrf_hash() ?>"> -->
              <button type="submit" class="btn btn-success" id="btnConfirm" disabled>
                <i class="fas fa-check me-1"></i> Konfirmasi & Simpan
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('#formImpor').on('submit', function (e) {
    e.preventDefault();

    const fileInput = $('#excel_file')[0];
    if (!fileInput.files.length) return;

    // Reset modal state
    $('#previewLoading').removeClass('d-none');
    $('#previewContent').addClass('d-none');
    $('#previewEmpty').addClass('d-none');
    $('#btnConfirm').prop('disabled', true);

    // Open modal immediately (shows spinner)
    $('#modalPreviewImpor').modal('show');

    // Send file via AJAX
    const formData = new FormData(this);
    formData.append('excel_file', fileInput.files[0]);

    $.ajax({
      url: '<?php echo base_url('gudang/impor') ?>',
      method: 'POST',
      data: formData,
      processData: false,  // required for FormData
      contentType: false,  // required for FormData
      dataType: 'json',
      success: function (data) {
        $('#previewLoading').addClass('d-none');

        if (!data.success) {
          alert(data.message ?? 'Terjadi kesalahan.');
          $('#modalPreviewImpor').modal('hide');
          return;
        }

        const matched = data.matched;

        if (!matched.length) {
          $('#previewEmpty').removeClass('d-none');
          return;
        }

        // Build table rows
        let rows = '';
        let changedCount = 0;

        $.each(matched, function (i, row) {
          const rowClass = !row.is_exist ? 'table-primary'
            : row.in_db_only ? 'table-danger'
              : row.changed ? 'table-warning'
                : 'table-success';

          const statusBadge = row.in_db_only ? '<span class="badge text-white bg-secondary">Tidak Berubah</span>'
            : !row.is_exist ? '<span class="badge text-white bg-primary">Baru</span>'
              : row.changed ? '<span class="badge bg-warning text-dark">Stok Berubah</span>'
                : '<span class="badge text-white bg-secondary">Sama</span>';

          const ketersediaanBadge = row.in_db_only ? '<span class="badge text-white bg-danger">Tidak ada di Excel</span>'
            : !row.is_exist ? '<span class="badge text-white bg-primary">Tidak ada di DB</span>'
              : '<span class="badge text-white bg-success">Ada di keduanya</span>';

          const newStok = row.in_db_only ? '-'
            : row.changed ? `<strong class="text-danger">${row.stok_excel}</strong>`
              : row.stok_excel;
          
          if (row.changed) {
            changedCount++;
          }

          rows += `
        <tr class="${rowClass}">
            <td>${i + 1}</td>
            <td><code>${row.kode_artikel}</code></td>
            <td>${row.in_db_only ? row.nama_db : row.nama_excel}</td>
            <td>${row.stok_db}</td>
            <td>${newStok}</td>
            <td>${statusBadge}</td>
            <td>${ketersediaanBadge}</td>
        </tr>`;
        });

        $('#previewTableBody').html(rows);

        $('#previewSummary').html(
          `<i class="fas fa-info-circle me-2"></i>&nbsp;
                  <strong>${matched.length}</strong>&nbsp;item cocok ditemukan &mdash;
                  <strong class="text-danger">&nbsp;${changedCount}</strong>&nbsp;item stoknya akan berubah.`
        );

        $('#previewContent').removeClass('d-none');
        $('#btnConfirm').prop('disabled', false);
      },
      error: function (error) {
        console.error(error.responseText);
        alert('Gagal membaca respons dari server.');
        $('#modalPreviewImpor').modal('hide');
      }
    });
  });
  $('#formConfirm').on('submit', function (e) {
    e.preventDefault();

    $('#btnConfirm').prop('disabled', true).html(
      '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...'
    );
    $('#btnCancel').prop('disabled', true);
    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (data) {
        if (data.success) {
          $('#modalPreviewImpor').modal('hide');
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data stok berhasil diperbarui.',
            confirmButtonText: 'OK'
          }).then(function () {
            window.location.href = data.redirect;
          });
        } else {
          $('#btnConfirm').prop('disabled', false).html(
            '<i class="fas fa-check me-1"></i> Konfirmasi & Simpan'
          );
          $('#btnCancel').prop('disabled', false);
          Swal.fire({
            icon: 'error',
            title: 'Gagal impor stok!',
            text: data.message || 'Terjadi kesalahan.',
          });
        }
      },
      error: function (error) {
        console.error(error.responseText);
        $('#btnConfirm').prop('disabled', false).html(
          '<i class="fas fa-check me-1"></i> Konfirmasi & Simpan'
        );
        $('#btnCancel').prop('disabled', false);
        Swal.fire({
          icon: 'error',
          title: 'Error impor stok',
          text: 'Gagal menghubungi server.',
        });
      }
    });
  });
  $('#excel_file').on('change', function () {
    const fileName = $(this)[0].files[0]?.name || 'File Excel';
    $(this).next('.custom-file-label').text(fileName);
  });
</script>