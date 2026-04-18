<style>
  td {
    font-size: 11px;
    text-align: left;
  }

  tr {
    font-size: 13px;
  }

  .btn_tambah {
    margin-bottom: 10px;
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

  .summary-box {
    background: #fafafa;
    border-top: 1px solid #ececec;
    padding: 14px 18px;
  }

  .status-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 0;
  }

  .badge-stock {
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 999px;
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

<style type="text/css" media="print">
  .print-table {
    background-color: transparent;
  }

  .print-table th,
  .print-table td {
    border: 1px solid #dee2e6;
    padding: 0.3rem;
  }

  .print-table.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
  }
</style>

<div class="card shadow-sm custom-card mb-3">
  <!-- HEADER -->
  <div class="navbar-header">
    <div class="navbar-wrap">
      <div class="navbar-left">
        <a href="<?= base_url('Order/history') ?>" class="back-btn-clean" title="Kembali">
          <i class="fa fa-arrow-left"></i>
        </a>

        <!-- <div class="icon-box">
          <i class="fas fa-file-invoice text-primary"></i>
        </div> -->

        <div>
          <h5 class="navbar-title">Detail Order <?= $order->id ?> | <?= $order->nama_customer ?></h5>
          <p class="navbar-subtitle">Informasi sales order dan detail item pesanan</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ACTION -->
  <div class="action-row">
    <button onclick="exportSo('<?php echo $d->id_order; ?>')" data-toggle="modal" data-target=".exportSo" class="btn btn-info btn-sm">
      <i class="fa fa-download"></i> Export
    </button>
    <button onclick="printContent()" target="_blank" class="btn btn-primary btn-sm">
      <i class="fa fa-print"></i> Print
    </button>
  </div>

  <div class="areaPrint">
    <div class="info-grid">
      <div class="text-center mb-3">
        <h4 class="mb-1">PT VISTA MANDIRI GEMILANG</h4>
        <h5 class="mb-1"><b>SALES ORDER</b></h5>
        <div><span class="badge badge-pill badge-info px-3 py-2">(<?= jenis_so($order->jenis) ?>)</span></div>
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
                <td>Type Customer</td>
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
                <td>Nama Sales</td>
                <td>: <?= $order->sales ?></td>
              </tr>
              <tr>
                <td>Referensi</td>
                <td>: <?= $order->referensi ?></td>
              </tr>
              <tr>
                <td>Tanggal PO</td>
                <td>: <?= $order->tanggal_dibuat ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="section-box">
      <div class="section-title">Detail Item Order</div>
      <div class="table-responsive">
        <table class="table table-striped print-table">
          <thead>
            <tr>
              <th style="width:5%">No</th>
              <th style="width:13%">Kode</th>
              <th>Artikel</th>
              <th style="width:8%" class="text-center">Size</th>
              <th style="width:7%" class="text-center">QTY</th>
              <th style="width:8%" class="text-center">Satuan</th>
              <th style="width:12%" class="text-right">Harga Satuan</th>
              <th style="width:7%" class="text-center">Margin</th>
              <th style="width:13%" class="text-right">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 0;
            $subtotal = 0;
            foreach ($detail as $d) :
              $no++;
            ?>
              <tr>
                <td><?= $no ?></td>
                <td><?= $d->kode_artikel ?></td>
                <td><?= $d->nama_artikel ?></td>
                <td class="text-center">
                  <?= get_size($d->kode_artikel) ?>
                </td>
                <td class="text-center"><?= $d->qty ?></td>
                <td class="text-center"><?= $d->satuan ?></td>
                <td class="text-right">Rp <?= number_format($d->harga) ?></td>
                <td class="text-center"><?= $d->diskon_barang ?></td>
                <td class="text-right">Rp <?= number_format(hitung_diskon($d->harga, $d->diskon_barang) * $d->qty) ?></td>
              </tr>
            <?php
              $subtotal += hitung_diskon($d->harga, $d->margin) * $d->qty;
              $diskon_p = $subtotal * $order->diskon / 100;
              $grandtotal = $subtotal - $diskon_p;
            endforeach ?>
            <tr>
              <td colspan="7" class="text-right"><strong>SubTotal :</strong></td>
              <td></td>
              <td class="text-right"><strong> Rp. <?= number_format($subtotal) ?></strong></td>
            </tr>
            <tr>
              <td colspan="7" class="text-right"><strong>Diskon :</strong></td>
              <td class="text-center"><?= $order->diskon ?>%</td>
              <td class="text-right">Rp. <?= number_format($subtotal * $order->diskon / 100) ?></td>
            </tr>
            <tr>
              <td colspan="7" class="text-right"><strong>GrandTotal :</strong></td>
              <td></td>
              <td class="text-right"><strong>Rp. <?= number_format($grandtotal) ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="summary-box">
    <div class="status-badges">
      <?php
      $badge_class = $grandtotal >= $order->minimum_order ? 'success' : 'danger';
      $badge_text = $grandtotal >= $order->minimum_order ? 'SUDAH MEMENUHI MIN. PO' : 'BELUM MEMENUHI MIN. PO';
      echo "<span class='badge badge-$badge_class badge-stock'>$badge_text</span>";
      ?>
    </div>
  </div>
</div>

<div class="modal fade exportSo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <form id="formExport" action="<?= base_url('Order/exportSo') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="exampleModalLabel">Export SO</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <label for="">No. Faktur :</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">SO-<?= date('Y-m') ?>-</span>
            </div>
            <input type="text" name="no_urut" id="no_urut" class="form-control" value="0000" maxlength="4" minlength="4" required>
          </div>
          <small class="text-danger">( Mengikuti No. faktur di easy accounting )</small>
          <div class="form-group">
            <label for="">Est. Tgl. Kirim :</label>
            <input type="date" name="tanggal" id="tgl_kirim" class="form-control form-control-sm" autocomplete="off">
          </div>
          <hr>
          <p><b>Info :</b>
            <small>Data yang sudah di export, akan di pindahkan ke halaman History.</small>
          </p>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id_order" id="id_order" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success btn-sm" id="export-button"><i class="fa fa-save"></i> Export</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <form action="<?= base_url('Order/update_po') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="exampleModalLabel">Edit PO </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <table class="table responsive">
            <thead>
              <tr>
                <th style="width:5%">No</th>
                <th style="width:13%">Kode</th>
                <th>Artikel</th>
                <th style="width:8%">QTY</th>
                <th style="width:8%">Satuan</th>
                <th class="text-right">Harga Satuan</th>
                <th style="width:7%" class="text-center">Margin</th>
                <th class="text-center">Total</th>
              </tr>
            </thead>
            <tbody id="artikelList"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id_order" id="id_order" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  function goBack() {
    window.history.back();
  }
</script>
<script>
  function printContent() {
    var printContents = document.querySelector('.areaPrint').innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    var printTable = document.querySelector('.print-table');
    if (printTable) {
      printTable.classList.add('table', 'table-striped', 'print-table');
    }

    window.print();
    document.body.innerHTML = originalContents;
  }
</script>
<script>
  function exportSo(id) {
    $('#id_order').val(id);
  }

  function getdetail(id) {
    $('#id_order').val(id);
    $.ajax({
      url: '<?= base_url('Order/getDataPo') ?>',
      type: 'GET',
      data: {
        detail: id
      },
      success: function(response) {
        var artikelList = $('#artikelList');
        artikelList.empty();

        if (response.length > 0) {
          $.each(response, function(index, artikel) {
            var diskon = artikel.diskon_barang;
            var total_harga = diskonDetail(artikel.harga, diskon) * artikel.qty;
            var row = '<tr>' +
              '<td> <input type="hidden" name="id_detail[]" value=' + artikel.id + ' />' + (index + 1) + '</td>' +
              '<td>' + artikel.kode_artikel + '</td>' +
              '<td>' + artikel.nama_artikel + '</td>' +
              '<td><input type="number" name="qty_update[]" min="0" class="form-control form-control-sm qty-input" value=' + artikel.qty + ' required /></td>' +
              '<td>' + artikel.satuan + '</td>' +
              '<td class="text-right"> <input type="hidden" class="harga" value=' + artikel.harga + ' />' + formatRupiah(artikel.harga) + '</td>' +
              '<td class="text-center "> <input type="hidden" class="diskonHarga" value=' + artikel.diskon_barang + ' />' + artikel.diskon_barang + '</td>' +
              '<td class="text-right total_harga">' + formatRupiah(total_harga) + '</td>' +
              '</tr>';
            artikelList.append(row);
          });
          $('.qty-input').on('input', function() {
            updateTotalHarga($(this));
          });
        } else {
          var emptyRow = '<tr><td colspan="5">Tidak ada artikel yang ditemukan.</td></tr>';
          artikelList.append(emptyRow);
        }
      },
      error: function(xhr, status, error) {
        console.log(error);
      }
    });
  }

  function updateTotalHarga(inputElement) {
    var row = inputElement.closest('tr');
    var hargaCell = row.find('.harga');
    var diskonCell = row.find('.diskonHarga');
    var totalHargaCell = row.find('.total_harga');
    var harga = hargaCell.val();
    var diskonKedua = diskonCell.val();
    var qty = parseInt(inputElement.val());
    var diskonAmount = diskonDetail(harga, diskonKedua);
    var totalHarga = diskonAmount * qty;
    totalHargaCell.text(formatRupiah(totalHarga));
  }

  function formatRupiah(angka) {
    var numberString = angka.toString();
    var split = numberString.split('.');

    var rupiah = split[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    var desimal = split[1] != undefined ? parseFloat('0.' + split[1]).toString().substring(2) : '';

    return 'Rp ' + rupiah + (desimal !== '' ? ',' + (desimal === '00' ? '' : desimal) : '');
  }

  function diskonDetail(harga_satuan, diskon) {
    var diskon_parts = diskon.split('+');
    var harga_diskon = harga_satuan;

    for (var i = 0; i < diskon_parts.length; i++) {
      var diskon_decimal = parseFloat(diskon_parts[i].replace('%', ''));
      harga_diskon -= harga_diskon * (diskon_decimal / 100);
    }

    var hasil_bulat = harga_diskon.toFixed(2);

    return parseFloat(hasil_bulat);
  }
</script>
<script>
  document.getElementById('export-button').addEventListener('click', function(event) {
    event.preventDefault();
    var urut = $('#no_urut').val();
    var tgl_kirim = $('#tgl_kirim').val();
    if (urut === "" || urut === "0000") {
      Swal.fire(
        'GAGAL',
        'Nomor urut harus diisi atau tidak boleh 0000',
        'error'
      );
    } else {
      document.getElementById('formExport').submit();
      $('.exportSo').modal('hide');
      Swal.fire(
        'BERHASIL',
        'Data berhasil di export',
        'success'
      );
    }
  });
</script>