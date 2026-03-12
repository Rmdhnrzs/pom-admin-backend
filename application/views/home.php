<div class="isi d-none">

  <ul class="nav bg-white fixed-top justify-content-end shadow" style="font-size:120%">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= ucfirst($this->session->userdata('name')) ?> <i class="fas fa-lg fa-user-circle"></i>
      </a>
      <div class="dropdown-menu" style="margin-right:10px" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="<?= base_url('Profil') ?>">Profil</a>
        <!-- <div class="dropdown-divider"></div> -->
        <a class="dropdown-item" href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">Logout</a>
      </div>
    </li>
  </ul>

  <div class="input-group mb-3" style="margin-top: 60px;">
    <div class="input-group-prepend">
      <span class="input-group-text" id="inputGroup-sizing-default" style="border-radius: 20px 0px 0px 20px"><i class="fas fa-search"></i></span>
    </div>
    <input type="text" class="form-control" style="border-radius: 0px 20px 20px 0px;" placeholder="Cari..">
  </div>

  <div class="card bg-success text-white mb-3">
    <div class="card-body">
      <h3>Hi, <?= ucfirst($this->session->userdata('name')) ?></h3>
      <p>Selamat datang kembali di aplikasi Purchase Order Mobile (POM)</p>
    </div>
  </div>

  <h1><?= generateKodePo(0) ?></h1>

  <div class="card mb-3">
    <div class="card-body">
      <p>Anda sedang mengelola perushaan <b><?= $nama_perusahaan ?></b>, jika ingin mengganti perusahaan lain harap login ulang atau <a href="<?= base_url('Auth_mobile/logout') ?>">Klik Disini</a></p>
    </div>
  </div>

  <div class="container" style="font-size: 75%;">
    <div class="row">
      <div class="col-3 text-center">
        <div class="card p-2 mb-3 card" style="border-radius: 15px" onclick="location.href = '<?= base_url('sales_order/customer') ?>';">
          <img class="w-100" src="<?= base_url('assets/icon/plus.png') ?>">
          
        </div>
        <p>Input PO</p>
      </div>
      <div class="col-3 text-center">
        <div class="card p-2 mb-3 card" style="border-radius: 15px" onclick="location.href = '<?= base_url('sales_order/history') ?>';">
          <img class="w-100" src="<?= base_url('assets/icon/history.png') ?>">
        </div>
          <p>History</p>
      </div>
      <!-- <div class="col-3 text-center">
      <div onclick="location.href = '<?= base_url('sales_order/history') ?>';">
        <img class="w-75" src="<?= base_url('assets/icon/information.png') ?>">
        <p>Info</p>
      </div>
    </div>
    <div class="col-3 text-center">
      <div onclick="location.href = '<?= base_url('sales_order/history') ?>';">
        <img class="w-75" src="<?= base_url('assets/icon/more.png') ?>">
        <p>Lainnya</p>
      </div>
    </div> -->
    </div>
  </div>

  <div class="card" onclick="AppInventor.setWebViewString('https://youtu.be/8ZFjmzX8YMc')">
    <!-- <div class="card-header">Video Tutorial</div> -->
    <div class="card-body"><img class="w-100" src="<?= base_url('assets/img/thumbnail.jpg') ?>"></div>
    <div class="card-footer bg-light"><i class="fab fa-youtube"></i> Tonton video tutorialnya dulu yuk !</div>
  </div>
  
  <!-- <div class="mb-5" style="font-size: 75%">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Info !</strong> Harap menonton video tutorial terlebih dahulu, supaya tidak terjadi kesalahan pada saat penginputan
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Info !</strong> Harap segera mengganti password anda segera, untuk menghindari penyalahgunaan akses.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div> -->
</div>