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
  transition: 0.3s;
}

.dashboard-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
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


.bg-barang { background: #4e73df; }
.bg-customer { background: #1cc88a; }
.bg-stok { background: #3a3d33}
.bg-approve { background: #f6c23e; }
.bg-done { background: #36b9cc; }
</style>

<div class="card shadow-sm custom-card">
  <div class="card-body">

    <h3 class="title-home mb-3">
      Selamat datang, <?= $this->session->userdata('name') ?>
    </h3>
    <hr>

    <div class="row">

      <div class="col-md-4 mb-3">
        <div class="dashboard-card bg-barang">
          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Total Barang/Artikel</div>
              <div class="count"><?= number_format($total_barang) ?></div>
            </div>
            <i class="fa fa-box"></i>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-3">
        <div class="dashboard-card bg-stok">
          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Total Stok/Kuantitas</div>
              <div class="count"><?= number_format($total_stok) ?></div>
            </div>
            <i class="fa fa-asterisk"></i>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-3">
        <div class="dashboard-card bg-customer">
          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Total Customer</div>
              <div class="count"><?= number_format($total_customer) ?></div>
            </div>
            <i class="fa fa-users"></i>
          </div>
        </div>
      </div>
      
      <?php if($this->session->userdata('role_id') != 3): ?>
      <div class="col-md-4 mb-3">
        <div class="dashboard-card bg-approve">
          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Menunggu Approve PO</div>
              <div class="count"><?= number_format($so_pending) ?></div>
            </div>
            <i class="fa fa-clock"></i>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <!-- 
      <div class="col-md-3 mb-3">
        <div class="dashboard-card bg-done">
          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Sudah Approve</div>
              <div class="count"><?= $so_approved ?></div>
            </div>
            <i class="fa fa-check-circle"></i>
          </div>
        </div>
      </div> -->

    </div>

  </div>
</div>