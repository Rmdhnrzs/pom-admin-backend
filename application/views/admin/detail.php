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

  .navbar-header {
    background: #fff;
    padding: 16px 20px;
    border-bottom: 1px solid #e9ecef;
  }

  .navbar-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .navbar-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .back-btn-clean {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    border: 1px solid #dfe3e8;
    background: #fff;
    color: #2c7be5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    flex-shrink: 0;
  }

  .back-btn-clean:hover {
    text-decoration: none;
    color: #1b5fc1;
    background: #f8fbff;
  }

  .icon-box {
    width: 38px;
    height: 38px;
    background: #e7f1ff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .navbar-title {
    font-size: 18px;
    font-weight: 700;
    color: #212529;
    margin: 0;
    line-height: 1.2;
  }

  .navbar-subtitle {
    font-size: 12px;
    color: #7a7a7a;
    margin: 2px 0 0;
  }

  .action-row {
    background: #fff;
    padding: 12px 20px;
    border-bottom: 1px solid #eceff3;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
  }

  .action-row .btn {
    border-radius: 8px;
  }

  .info-grid {
    padding: 18px;
    padding-bottom: 6px;
  }

  .info-card {
    background: #fafafa;
    border: 1px solid #ececec;
    border-radius: 10px;
    padding: 14px 16px;
    height: 100%;
  }

  .info-card-title {
    font-size: 12px;
    font-weight: 700;
    color: #6c757d;
    text-transform: uppercase;
    margin-bottom: 10px;
    letter-spacing: .3px;
  }

  .info-table {
    width: 100%;
    margin-bottom: 0;
  }

  .info-table td {
    border: none;
    padding: 4px 0;
    font-size: 12px;
    vertical-align: top;
  }

  .info-table td:first-child {
    width: 120px;
    color: #666;
  }

  .section-box {
    padding: 0 18px 18px;
  }

  .section-title {
    font-size: 13px;
    font-weight: 700;
    color: #495057;
    text-transform: uppercase;
    margin-bottom: 10px;
    letter-spacing: .4px;
  }

  .note-box {
    border: 1px solid #ececec;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
  }

  .note-box .table {
    margin-bottom: 0;
  }

  .summary-box {
    background: #fafafa;
    border-top: 1px solid #ececec;
    padding: 14px 18px;
  }

  .summary-note {
    font-size: 12px;
    color: #666;
    line-height: 1.7;
  }

  .summary-note b {
    color: #333;
  }

  .status-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 10px;
  }

  .badge-stock {
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 999px;
  }

  .badge-soft-info {
    background: #e7f1ff;
    color: #2c7be5;
    font-weight: 600;
  }

  .text-code {
    font-family: monospace;
    font-size: 12px;
    font-weight: 600;
  }

  .note-empty {
    padding: 12px 14px;
    color: #888;
    font-size: 12px;
    margin: 0;
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

  @media (max-width: 768px) {
    .navbar-header,
    .action-row,
    .info-grid,
    .section-box,
    .summary-box {
      padding-left: 14px;
      padding-right: 14px;
    }

    .action-row {
      justify-content: flex-start;
    }

    .info-table td:first-child {
      width: 105px;
    }

    .navbar-title {
      font-size: 16px;
    }
  }
</style>

<div class="card shadow-sm custom-card mb-3">
  <!-- HEADER / NAVBAR -->
  <div class="navbar-header">
    <div class="navbar-wrap">
      <div class="navbar-left">
        <a href="<?= ($order->status == 0) ? base_url('Order') : base_url('Order/history') ?>" class="back-btn-clean" title="Kembali">
          <i class="fa fa-arrow-left"></i>
        </a>

        <!-- <div class="icon-box">
          <i class="fas fa-file-invoice text-primary"></i>
        </div> -->

        <div>
          <h5 class="navbar-title">Detail Order</h5>
          <p class="navbar-subtitle">Informasi order, item barang, dan status pemesanan</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ACTION DI BAWAH HEADER -->
  <div class="action-row">
    <a href="<?= base_url('Order/edit/' . $order->id) ?>" class="btn btn-warning btn-sm <?= ($order->status == 1) ? 'd-none' : '' ?>">
      <i class="fa fa-edit"></i> Edit
    </a>
    <button onclick="openFiles('<?php echo $order->file ?>')" class="btn btn-info btn-sm <?= (is_null($order->file)) ? 'd-none' : '' ?>">
      <i class="fa fa-download"></i> Lampiran
    </button>
    <button onclick="printContent()" class="btn btn-primary btn-sm">
      <i class="fa fa-print"></i> Print
    </button>
  </div>

  <div class="areaPrint">
    <div class="info-grid">
      <div class="text-center mb-3">
        <h4 class="mb-1"><?= strtoupper($order->nama_perusahaan) ?></h4>
        <h5 class="mb-1"><b>SALES ORDER</b></h5>
        <div><span class="badge badge-pill badge-info px-3 py-2"><?= jenis_so($order->jenis) ?></span></div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="info-card">
            <div class="info-card-title">Informasi Customer</div>
            <table class="info-table">
              <tr>
                <td>Nama Customer</td>
                <td>: <?= $order->nama_customer ?></td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>: <?= $order->alamat ?></td>
              </tr>
              <tr>
                <td>Tipe Customer</td>
                <td>: <?= $order->tipe_harga ?></td>
              </tr>
            </table>
          </div>
        </div>

        <div class="col-md-6 mb-3">
          <div class="info-card">
            <div class="info-card-title">Informasi Order</div>
            <table class="info-table">
              <tr>
                <td>No. PO</td>
                <td>: <?= $order->referensi ?></td>
              </tr>
              <tr>
                <td>Tanggal PO</td>
                <td>: <?= $order->tanggal_dibuat ?></td>
              </tr>
              <tr>
                <td>Nama Sales</td>
                <td>: <?= $order->sales ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="section-box">
      <div class="section-title">Keterangan Artikel</div>
      <div class="note-box">
        <table class="table table-striped table-sm mb-0">
          <?php
          $noKet = 0;
          $hasKeterangan = false;
          foreach($detail as $d) {
            if($d->keterangan != '') {
              $hasKeterangan = true; ?>
              <tr>
                <td width="5%"><?= ++$noKet ?></td>
                <td width="18%" class="text-code"><?= $d->kode_artikel ?></td>
                <td width="27%"><?= $d->nama_artikel ?></td>
                <td><?= $d->keterangan ?></td>
              </tr>
          <?php }
          } ?>

          <?php if (!$hasKeterangan): ?>
            <tr>
              <td colspan="4" class="note-empty">Tidak ada keterangan artikel.</td>
            </tr>
          <?php endif; ?>
        </table>
      </div>
    </div>

    <div class="section-box">
      <div class="section-title">Detail Item Order</div>
      <div class="table-responsive">
        <table class="table table-striped table-hover border-bottom" style="width:100%">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th width="13%">Kode</th>
              <th>Artikel</th>
              <th class="text-center" width="8%">Size</th>
              <th class="text-center" width="7%">QTY</th>
              <th class="text-center" width="7%">Stok</th>
              <th class="text-center" width="8%">Satuan</th>
              <th class="text-right" width="12%">Harga Satuan</th>
              <th class="text-center" width="7%">Margin</th>
              <th class="text-right" width="13%">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 0;
            $subtotal = 0;
            $stokMinus = false;
            $diskonValue = (float) str_replace('%', '', $order->diskon);

            foreach ($detail as $d) :
              $no++;
              $total = hitung_diskon($d->harga, $d->diskon_barang) * $d->qty;
              $subtotal += $total;

              if (($d->stok - $d->qty) < 0) {
                $stokMinus = true;
              }
            ?>
              <tr>
                <td><?= $no ?></td>
                <td><span class="text-code"><?= $d->kode_artikel ?></span></td>
                <td><?= $d->nama_artikel ?></td>
                <td class="text-center"><?= $d->size ?></td>
                <td class="text-center"><?= $d->qty ?></td>
                <td class="text-center">
                  <span class="badge badge-pill badge-soft-info px-3">
                    <?= number_format($d->stok, 0, ',', '.') ?>
                  </span>
                </td>
                <td class="text-center"><?= $d->satuan ?></td>
                <td class="text-right">Rp <?= rupiah($d->harga) ?></td>
                <td class="text-center"><?= $d->diskon_barang ?></td>
                <td class="text-right">Rp <?= rupiah($total) ?></td>
              </tr>
            <?php endforeach; ?>

            <?php
            $diskon_p = $subtotal * $diskonValue / 100;
            $grandtotal = $subtotal - $diskon_p;
            ?>

            <tr>
              <td colspan="8" class="text-right"><strong>SubTotal :</strong></td>
              <td></td>
              <td class="text-right"><strong>Rp <?= rupiah($subtotal) ?></strong></td>
            </tr>
            <tr>
              <td colspan="8" class="text-right"><strong>Diskon :</strong></td>
              <td class="text-center"><?= $order->diskon ?></td>
              <td class="text-right">Rp <?= rupiah($diskon_p) ?></td>
            </tr>
            <tr>
              <td colspan="8" class="text-right"><strong>Grand Total :</strong></td>
              <td></td>
              <td class="text-right"><strong>Rp <?= rupiah($grandtotal) ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="summary-box d-flex justify-content-between align-items-start flex-wrap" style="gap:16px;">
    <div style="flex:1; min-width:280px;">
      <div class="summary-note">
        <b>Catatan :</b><br>
        <?= nl2br(htmlspecialchars($order->catatan)) ?>

        <?php if ($order->status == 2): ?>
          <br><br>
          <b>Alasan Tolak :</b><br>
          <?= nl2br(htmlspecialchars($order->alasan)) ?>
        <?php endif; ?>
      </div>

      <div class="status-badges">
        <?php
        $min_po_badge =  $grandtotal >= $order->minimum_order ? 'success' : 'danger';
        $min_po_text =  $grandtotal >= $order->minimum_order ? 'SUDAH MEMENUHI MIN. PO' : 'BELUM MEMENUHI MIN. PO';
        $min_stok_badge = $stokMinus ? 'danger' : 'success';
        $min_stok_text = $stokMinus ? 'JUMLAH KUANTITAS MELEBIHI JUMLAH STOK' : 'JUMLAH KUANTITAS MEMENUHI STOK';
        ?>
        <span class="badge badge-<?= $min_po_badge ?> badge-stock"><?= $min_po_text ?></span>
        <span class="badge badge-<?= $min_stok_badge ?> badge-stock"><?= $min_stok_text ?></span>
      </div>
    </div>

    <div class="<?= ($order->status == 1 || $order->status == 2) ? 'd-none' : '' ?>" style="display:flex; gap:8px; flex-wrap:wrap; align-items:flex-start;">
      <button onclick="exportSo('<?= $order->id ?>')" data-toggle="modal" data-target=".exportSo" class="btn btn-success btn-sm">
        <i class="fa fa-check"></i> Proses
      </button>
      <button data-toggle="modal" data-target="#tolakPo" class="btn btn-danger btn-sm">
        <i class="fa fa-times"></i> Tolak
      </button>
    </div>
  </div>
</div>

<!-- Modal Export SO -->
<div class="modal fade exportSo" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formExport" action="<?= base_url('Order/exportSo') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Export SO</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <label>No. Pesanan :</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <?php
              date_default_timezone_set('Asia/Jakarta');
              $tanggalHariIni = new DateTime();
              if ($tanggalHariIni->format('d') == $tanggalHariIni->format('t')) {
                $tanggalHariIni->add(new DateInterval('P1M'));
              }
              $tahunBulan = $tanggalHariIni->format('Y-m');
              $id_perusahaan = $order->id_perusahaan;
              $nomorUrutan = ($id_perusahaan == 2) ? "SO-PKP-$tahunBulan" : "SO-$tahunBulan";
              ?>
              <span class="input-group-text"><?= $nomorUrutan ?>-</span>
            </div>
            <input type="text" name="no_urut" id="no_urut" class="form-control" value="0000" maxlength="4" minlength="4" required>
          </div>
          <small class="text-danger">( Mengikuti No. Pesanan di Easy Accounting )</small>

          <div class="form-group mt-3">
            <label>Est. Tgl. Kirim :</label>
            <input type="date" name="tanggal" id="tgl_kirim" class="form-control form-control-sm" autocomplete="off">
          </div>

          <hr>
          <p><b>Info :</b> <small>Data yang sudah di export akan dipindahkan ke halaman History.</small></p>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id_order" id="id_order" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success btn-sm" id="export-button">
            <i class="fa fa-save"></i> Export
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Tolak PO -->
<div class="modal fade" id="tolakPo" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= base_url('Order/tolak_po') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Tolak PO</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>No. PO</label>
            <input type="text" class="form-control" value="<?= $order->referensi ?>" disabled />
          </div>
          <div class="form-group">
            <label>Alasan tolak</label>
            <textarea name="alasan" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <input type="hidden" name="id" value="<?= $order->id ?>" />
          <div class="form-group text-right">
            <button type="submit" class="btn btn-warning">Tolak PO</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  function openFiles(files) {
    var fileArray = files.split(',');
    fileArray.forEach((file, i) => {
      var trimedFile = file.trim();
      if (file) {
        var url = '<?= base_url('Order/viewLampiran/') ?>' + trimedFile;
        window.open(url, '_blank');
      }
    });
  }

  function printContent() {
    var printContents = document.querySelector('.areaPrint').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
  }

  function exportSo(id) {
    $('#id_order').val(id);
  }

  document.getElementById('export-button').addEventListener('click', function(event) {
    event.preventDefault();
    var urut = $('#no_urut').val();

    if (urut === "" || urut === "0000") {
      Swal.fire('GAGAL', 'Nomor urut harus diisi atau tidak boleh 0000', 'error');
    } else {
      document.getElementById('formExport').submit();
      $('.exportSo').modal('hide');
      Swal.fire('BERHASIL', 'Data berhasil di export', 'success').then((result) => {
        if (result.isConfirmed) {
          window.location.reload();
        }
      });
    }
  });
</script>