<style>
/* bagian navbar */
.navbar {
  padding: 14px 25px;
  min-height: 70px;
}

.navbar .nav-link {
  font-size: 15px;
  font-weight: 500;
}

.navbar .nav-link i {
  font-size: 16px;
}

.navbar .nav-link span {
  font-size: 14px;
}

.navbar-dark .navbar-nav .nav-link {
  color: rgba(255, 255, 255, 0.85);
}

.navbar-dark .navbar-nav .nav-link:hover {
  color: #ffffff;
}

.navbar-dark .navbar-nav .nav-item.active .nav-link {
  color: #ffffff;
  font-weight: 600;
}

/* bagian dropdown */
.navbar .dropdown-menu {
  border-radius: 10px;
  border: none;
  padding: 8px;
  margin-top: 10px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  animation: fadeDown 0.2s ease;
}

@keyframes fadeDown {
  from {
    opacity: 0;
    transform: translateY(-5px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dropdown-item {
  font-size: 13px;
  border-radius: 6px;
  padding: 8px 12px;
  transition: 0.2s;
}

.dropdown-item:hover {
  background: #f1f5ff;
  color: #0d6efd;
}

.dropdown-item:active {
  background: #0d6efd;
  color: #fff;
}

.dropdown-item i {
  width: 18px;
}

.dropdown-menu {
  min-width: 180px;
}
</style>
<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-3" style="background: #007BFF;">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <?php $role_id = $this->session->userdata('role_id') ?>
              <li class="nav-item <?= ($title == 'Dashboard') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('Dashboard') ?>"><i class="fas fa-chart-line"></i> Dashboard</a>
              </li>
              <?php if ($role_id == 1) : ?>
                <li class="nav-item dropdown <?= ($title == 'Data Barang' || $title == 'Data Customer') ? 'active' : '' ?>">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-database"></i> Data Master
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item <?= ($title == 'Data Barang') ? 'active' : '' ?>" href="<?= base_url('Barang') ?>">Barang</a>
                    <a class="dropdown-item <?= ($title == 'Data Customer') ? 'active' : '' ?>" href="<?= base_url('Customer') ?>">Customer</a>
                  </div>
                </li>
                <li class="nav-item dropdown <?= ($title == 'Sales Order' || $title == 'history Order') ? 'active' : '' ?>">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cart-plus"></i> Sales Order
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item <?= ($title == 'Sales Order') ? 'active' : '' ?>" href="<?= base_url('Order') ?>"> Sales Order</a>
                    <a class="dropdown-item <?= ($title == 'history Order') ? 'active' : '' ?>" href="<?= base_url('Order/history') ?>">History</a>
                  </div>
                </li>
                <li class="nav-item <?= ($title == 'Data User') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('User') ?>"><i class="fas fa-user"></i> User</a>
                </li>
              <?php endif; ?>
              <li class="nav-item <?= ($title == 'Gudang') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('Gudang') ?>">
                  <i class="fas fa-file-excel"></i> Gudang
                </a>
              </li>
              <?php if ($role_id == 3) : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= base_url('Barang') ?>">
                  <i class="fas fa-list"></i> Barang
                </a>
              </li>
              <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
              <div class="topbar-divider d-none d-sm-block"></div>

              <!-- Nav Item - User Information -->
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $this->session->userdata('name') ?></span>
                  <!-- <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg"> -->
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#passwordModal">
                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                    Ubah Password
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </nav>

        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <?php
          if ($this->session->flashdata('type')) { ?>
            <script>
              var type = "<?= $this->session->flashdata('type'); ?>"
              var title = "<?= $this->session->flashdata('title'); ?>"
              var text = "<?= $this->session->flashdata('text'); ?>"
              Swal.fire(title, text, type)
            </script>
          <?php } ?>
          <?php if (empty($view)) {
            echo "<img class='d-block w-100' src='assets/img/hicoop.jpg'></img>";
          } else {
            $this->load->view($view);
          }
          ?>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Pilih "Logout" untuk keluar </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Logout</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Password Modal-->
  <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('Auth/gantiPass') ?>" method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Silahkan masukkan password baru anda.</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input type="password" name="pass" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Batal</button>
            <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
          </div>
        </div>
      </form>

    </div>
  </div>