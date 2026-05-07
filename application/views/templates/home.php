<style>
.title-home {
  color: #2c7be5;
  font-weight: 600;
}

.dashboard-card {
  border-radius: 12px;
  border: none;
  padding: 20px;
  color: #fff;
  position: relative;
  overflow: hidden;
  transition: all 0.25s ease;
  text-decoration: none !important;
  display: block;
}

.dashboard-card-link {
  cursor: pointer;
}

.dashboard-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.10);
  color: #fff;
}

.dashboard-card i {
  font-size: 28px;
  opacity: 0.9;
}

.dashboard-card .count {
  font-size: 26px;
  font-weight: bold;
}

.dashboard-card .label {
  font-size: 13px;
  opacity: 0.9;
}

.dashboard-card::after {
  content: '';
  position: absolute;
  right: -20px;
  bottom: -20px;
  width: 80px;
  height: 80px;
  background: rgba(255,255,255,0.1);
  border-radius: 50%;
}

.bg-barang { 
  background: #4e73df; 
}

.bg-customer { 
  background: #1cc88a; 
}

.bg-stok { 
  background: #369af7; 
}

.bg-approve { 
  background: #f6c23e; 
}

.bg-done { 
  background: #36b9cc; 
}

.card-arrow {
  position: absolute;
  right: 15px;
  bottom: 15px;
  font-size: 14px !important;
  opacity: 0.8;
}
</style>

<div class="card shadow-sm custom-card">
  <div class="card-body">

    <h3 class="title-home mb-3">
      Selamat datang, <?= $this->session->userdata('name') ?>
    </h3>

    <hr>

    <div class="row">

      <!-- BARANG -->
      <div class="col-md-4 mb-3">
        <a href="<?= base_url('Barang') ?>" class="dashboard-card dashboard-card-link bg-barang">

          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Total Barang/Artikel</div>
              <div class="count">
                <?= number_format($total_barang) ?>
              </div>
            </div>

            <i class="fa fa-box"></i>
          </div>

          <i class="fa fa-arrow-right card-arrow"></i>

        </a>
      </div>

      <!-- CUSTOMER -->
      <div class="col-md-4 mb-3">
        <a href="<?= base_url('Customer') ?>" class="dashboard-card dashboard-card-link bg-customer">

          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Total Customer</div>
              <div class="count">
                <?= number_format($total_customer) ?>
              </div>
            </div>

            <i class="fa fa-users"></i>
          </div>

          <i class="fa fa-arrow-right card-arrow"></i>

        </a>
      </div>

      <!-- STOK -->
      <div class="col-md-4 mb-3">
        <div class="dashboard-card bg-stok">

          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Total Stok/Kuantitas</div>
              <div class="count">
                <?= number_format($total_stok) ?>
              </div>
            </div>

            <i class="fa fa-asterisk"></i>
          </div>

        </div>
      </div>

      <!-- APPROVE -->
      <?php if($this->session->userdata('role_id') != 3): ?>
      <div class="col-md-4 mb-3">
        <a href="<?= base_url('Order') ?>" class="dashboard-card dashboard-card-link bg-approve">

          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Menunggu Approve PO</div>
              <div class="count">
                <?= number_format($so_pending) ?>
              </div>
            </div>

            <i class="fa fa-clock"></i>
          </div>

          <i class="fa fa-arrow-right card-arrow"></i>

        </a>
      </div>
      <?php endif; ?>

    </div>

  </div>
</div>