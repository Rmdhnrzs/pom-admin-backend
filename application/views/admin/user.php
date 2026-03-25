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

  .action-group {
    display: flex;
    justify-content: center;
    gap: 4px;
  }

  .btn-action {
    padding: 4px 10px;
    border-radius: 6px;
  }

  .badge {
    font-size: 11px;
    padding: 5px 8px;
    border-radius: 6px;
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
        <i class="fas fa-user text-primary"></i>
      </div>
      <div>
        <h5 class="mb-0">Manajemen User</h5>
        <small>Pengaturan akun pengguna sistem</small>
      </div>
    </div>

    <button type="button"
      class="btn btn-success btn-sm"
      data-toggle="modal"
      data-target="#modal_tambah">
      <i class="fas fa-plus"></i> Tambah
    </button>

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
          <th>Nama Lengkap</th>
          <th>Username</th>
          <th class="text-center">Role</th>
          <th class="text-center">Menu</th>
        </tr>
      </thead>

      <tbody>
        <?php $no = 1;
        foreach ($user as $k) { ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $k->nama ?></td>
            <td><?= $k->username ?></td>
            <td class="text-center">
              <?php
              if ($k->id_role == 1) {
                echo "<span class='badge badge-warning'>Administrator</span>";
              } else if ($k->id_role == 2) {
                echo "<span class='badge badge-danger'>Sales</span>";
              } else {
                echo "<span class='badge badge-secondary'>Tidak ada</span>";
              }
              ?>
            </td>
            <td class="text-center">
              <div class="action-group">
                <button type="button"
                  class="btn btn-warning btn-sm btn-action"
                  data-toggle="modal"
                  data-target="#exampleModalCenter"
                  onclick="getdetail('<?= $k->id ?>')">
                  <i class="fas fa-edit"></i>
                </button>

                <button type="button"
                  class="btn btn-info btn-sm btn-action"
                  data-toggle="modal"
                  data-target="#modal_reset"
                  onclick="getreset('<?= $k->id ?>')">
                  <i class="fas fa-key"></i>
                </button>

                <button
                  class="btn btn-danger btn-sm btn-action btn_delete"
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
<!-- tambah barang -->
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
  <form action="<?= base_url('User/simpan') ?>" method="POST">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="exampleModalLongTitle">Tambah User Baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label for="">Nama Lengkap:</label>
            <input type="text" name="NamaLengkap" class="form-control form-control-sm" required>
          </div>
          <div class="form-group">
            <label for="">Username :</label>
            <input type="text" name="username" id="username_add" class="form-control form-control-sm" required>
            <small class="text-danger">( Untuk admin Harus sama dg pengguna di easy accounting )</small>
          </div>
          <div class="form-group">
            <label for="">Password :</label>
            <input type="password" name="password" class="form-control form-control-sm" required>
          </div>
          <div class="form-group">
            <label for="">Role :</label>
            <select name="role" class="form-control form-control-sm" required>
              <option value="">- Pilih Role -</option>
              <option value="1">Administrator</option>
              <option value="2">Sales</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success btn-sm">Simpan</button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- end tambah -->

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
  <form action="<?= base_url('User/update') ?>" method="POST">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="exampleModalLongTitle">Update Data User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="">Nama Lengkap:</label>
            <input type="text" name="nama_update" id="NamaLengkap" class="form-control form-control-sm" required>
            <input type="hidden" name="id_user" id="id_user_update" class="form-control form-control-sm">
          </div>
          <div class="form-group">
            <label for="">Username :</label>
            <input type="text" name="username" id="username" class="form-control form-control-sm" required>
            <small class="text-danger">( Untuk admin Harus sama dg pengguna di easy accounting )</small>
          </div>
          <div class="form-group">
            <label for="">Role :</label>
            <select name="role" id="role" class="form-control form-control-sm" required>
              <option value="">- Pilih Role -</option>
              <option value="1">Administrator</option>
              <option value="2">Sales</option>
            </select>
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
<!-- Modal -->
<div class="modal fade" id="modal_reset" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
  <form action="<?= base_url('User/reset') ?>" method="POST">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="exampleModalLongTitle">Reset Password User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="">Nama Lengkap:</label>
            <input type="text" id="NamaLengkap_r" class="form-control form-control-sm" readonly>
            <input type="hidden" name="id_user" id="id_user_r" class="form-control form-control-sm">
          </div>
          <div class="form-group">
            <label for="">Username :</label>
            <input type="text" name="username" id="username_r" class="form-control form-control-sm" readonly>
          </div>
          <hr>
          Noted : Password user akan menjadi default = "password"

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm">Reset</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
  function getreset(id) {
    // Menggunakan Ajax untuk mengambil data artikel dari server
    $.ajax({
      url: '<?= base_url('User/getdata') ?>',
      type: 'GET',
      data: {
        id_user: id
      },
      success: function(response) {
        // Mengisi form dengan data artikel
        if (response) {
          $('#id_user_r').val(response.id);
          $('#NamaLengkap_r').val(response.nama);
          $('#username_r').val(response.username);

        }
      },
      error: function(xhr, status, error) {
        console.log(error);
      }
    });
  }

  function getdetail(id) {
    // Menggunakan Ajax untuk mengambil data artikel dari server
    $.ajax({
      url: '<?= base_url('User/getdata') ?>',
      type: 'GET',
      data: {
        id_user: id
      },
      success: function(response) {
        // Mengisi form dengan data artikel
        if (response) {
          $('#id_user_update').val(response.id);
          $('#NamaLengkap').val(response.nama);
          $('#username').val(response.username);
          $('#role').val(response.id_role);
        }
      },
      error: function(xhr, status, error) {
        console.log(error);
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
        location.href = "<?php echo base_url('User/hapus_data/') ?>" + id;
      }
    })
  })
</script>
<script>
  $(document).ready(function() {
    // Fungsi untuk melakukan pemeriksaan kode barang saat pengguna mengetikkan
    $("#username_add, #username").on("blur", function() {
      var Username = $(this).val();

      // Mengirimkan kode barang ke server untuk memeriksa keberadaannya di database
      $.ajax({
        url: "<?php echo base_url('User/cek_username'); ?>",
        method: "POST",
        data: {
          kode: Username
        },
        dataType: "json",
        success: function(response) {
          if (response.exist) {
            // Jika kode sudah ada di database, tampilkan pesan
            Swal.fire(
              'Username sudah ada',
              'Harap cek kembali / gunakan username yang lain',
              'info'
            );
            $('#username_add').css({
              'border': '1px solid red'
            });
            $('#username_add').val('');
            $('#username').val('');
          }
        }
      });
    });
  });
</script>