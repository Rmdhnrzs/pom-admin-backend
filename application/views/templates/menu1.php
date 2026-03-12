<ul class="nav nav-fill nav-pills">
  <li class="nav-item">
    <a class="nav-link <?= ($title == 'Customer') ? 'active' : '' ; ?>" href="<?= base_url('Sales_order/customer') ?>"><i class="fas fa-store"></i></a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= ($title == 'Produk') ? 'active' : '' ; ?>" href="<?= base_url('Sales_order/list_produk') ?>"><i class="fas fa-plus"></i></a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= ($title == 'Keranjang') ? 'active' : '' ; ?>" href="<?= base_url('Keranjang') ?>"><i class="fas fa-shopping-cart"></i></a>
  </li>
  
  <li class="nav-item dropdown ml-5">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
      <a class="dropdown-item" href="#">Profil</a>
      <a class="dropdown-item" href="#">History PO</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">Logout</a>
    </div>
  </li>
</ul>