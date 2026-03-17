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

  .data-barang {
    position: relative;
    padding: 10px;
    border: 1px solid #ccc;
    transition: background-color 0.3s ease-in-out;
  }

  .btn_edit,
  .btn_delete {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    background-color: rgba(0, 0, 0, 0.3);
    border: none;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
  }

  .btn_edit {
    right: 0;
  }

  .btn_delete {
    right: 40px;
  }

  .data-barang:hover {
    background-color: #f5f5f5;
  }

  .data-barang:hover .btn_edit,
  .data-barang:hover .btn_delete {
    opacity: 1;
  }
</style>
<div class="card shadow">
  <h5 class="card-header text-white bg-info">Gudang</h5>
  <form class="my-2 input-group w-50" id="formImpor" method="post" enctype="multipart/form-data">
    <div class="custom-file">
      <input id="excel_file" class="custom-file-input" type="file" name="excel_file" accept=".xlsx,.xls,.csv">
      <label for="excel_file" class="custom-file-label">File Excel</label>
    </div>
    <div class="input-group-append">
      <button type="submit" class="btn btn-info">Upload</button>
    </div>
  </form>
  <table class="table table-striped" id="datatable" style="width: 100%;">
    <thead>
      <tr>
        <th>No</th>
        <th>Kode Artikel</th>
        <th>Nama Artikel</th>
        <th>Stok</th>
        <th>Waktu terakhir impor</th>
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
            <td><?php echo $row->stok ?></td>
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