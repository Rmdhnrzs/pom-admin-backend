<style>
  td {
    font-size: 11px;
    text-align: left;
  }

  tr {
    font-size: 14px;
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

  .custom-select-filter {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 6px 10px;
    font-size: 12px;
    transition: all 0.2s ease;
  }

  .custom-select-filter:focus {
    border-color: #2c7be5;
    box-shadow: 0 0 0 2px rgba(44,123,229,0.1);
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
  
  .bg-total-po { background: #369af7; }
  .bg-approved-po { background: #5fc282; }
  .bg-rejected-po { background: #c2473c; }
</style>

<div class="card shadow-sm custom-card">

  <div class="card-header custom-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <div class="icon-box mr-3 d-flex align-items-center justify-content-center">
        <i class="fas fa-file-alt text-primary"></i>
      </div>
      <div>
        <h5 class="mb-0">History Sales Order</h5>
        <small>Riwayat data pesanan customer</small>
      </div>
    </div>
  </div>
  <div class="row mt-2">
    
    <div class="col-md-4 mb-3">
      <div class="dashboard-card bg-total-po">
        <div class="d-flex justify-content-between">
          <div>
            <div class="label">Total PO selesai</div>
            <div class="count"><?= number_format($total_po) ?></div>
          </div>
          <i class="fa fa-clipboard-list"></i>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="dashboard-card bg-approved-po">
        <div class="d-flex justify-content-between">
          <div>
            <div class="label">Total PO diproses</div>
            <div class="count"><?= number_format($total_approved_po) ?></div>
          </div>
          <i class="fa fa-check-circle"></i>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="dashboard-card bg-rejected-po">
        <div class="d-flex justify-content-between">
          <div>
            <div class="label">Total PO ditolak</div>
            <div class="count"><?= number_format($total_rejected_po) ?></div>
          </div>
          <i class="fa fa-times-circle"></i>
        </div>
      </div>
    </div>

  </div>
  <div class="card-body">

    <form action="<?= base_url('Order/history') ?>" method="get">
      <div class="d-flex align-items-center mb-3" style="gap:10px; flex-wrap:wrap;">
        
        <span style="font-size:12px; color:#666;">
          <i class="fa fa-filter"></i> Filter:
        </span>

        <select 
          name="s" 
          class="form-control form-control-sm custom-select-filter" 
          style="width:200px;">
          <option value="">Semua Status</option>
          <option value="1" <?= ($status == "1" ? 'selected' : '') ?>>Diproses</option>
          <option value="2" <?= ($status == "2" ? 'selected' : '') ?>>Ditolak</option>
        </select>

        <input 
          type="month" 
          name="bulan" 
          class="form-control form-control-sm custom-select-filter"
          style="width:180px;"
          value="<?= $bulan ?>">

        <button type="submit" class="btn btn-primary btn-sm">
          <i class="fa fa-search"></i> Tampilkan
        </button>
      </div>

      <small class="text-muted d-block mb-3">
        Menampilkan history periode <b><?= date('F Y', strtotime($bulan . '-01')) ?></b>
      </small>
    </form>

    <div class="table-responsive">
      <table class="table table-striped" id="datatable" style="width: 100%;">
        <thead>
          <tr>
            <th>Perusahaan</th>
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
            <td><?= strtoupper($d->perusahaan) ?></td>
            <td><?= $d->nama_customer ?></td>
            <td><?= $d->tipe_harga ?></td>
            <td><?= jenis_so($d->jenis) ?></td>
            <td><?= $d->sales ?></td>
            <td><?= $d->referensi ?></td>
            <td><?= date('d-m-Y', strtotime($d->tanggal_dibuat)) ?></td>
            <td><?= status_so($d->status) ?></td>
            <td class="text-center">
              <div class="action-group">
                <a href="<?= base_url('Order/detailHistory/' . $d->id) ?>" 
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
</div>