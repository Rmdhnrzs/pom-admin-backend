<style>
  /* Gaya default */
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
</style>
<style type="text/css" media="print">
  /* Tambahkan gaya cetak khusus di sini */
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

  /* Gaya lainnya sesuai kebutuhan Anda */
</style>



<div class="card shadow">
  <header>
    <h5 class="card-header text-white bg-info">Detail Order <?= $order->id ?> | <?= $order->nama_customer ?></h5>
  </header>
  <div class="areaPrint">
    <div class="card-body">
      <h4 class="text-center">PT VISTA MANDIRI GEMILANG</h4>
      <h4 class="text-center"><b>SALES ORDER</b></h4>
      <h5 class="text-center"><b>(<?= jenis_so($order->jenis) ?>)</b></h5>
      <div class="row">
        <div class="col-md-6">
          <table class="table">
            <tr>
              <td style="width: 30%;"><b>Nama Customer</b></td>
              <td>: <?= $order->nama_customer ?></td>
            </tr>
            <tr>
              <td style="width: 30%;"><b>Alamat</b></td>
              <td>: <?= $order->alamat ?></td>
            </tr>
            <tr>
              <td style="width: 30%;"><b>Type Customer</b></td>
              <td>: <?= $order->tipe_harga ?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table">
            <tr>
              <td style="width: 30%;"><b>Nama Sales</b></td>
              <td>: <?= $order->sales ?></td>
            </tr>
            <tr>
              <td style="width: 30%;"><b>Referensi</b></td>
              <td>: <?= $order->referensi ?></td>
            </tr>
            <tr>
              <td style="width: 30%;"><b>Tanggal PO</b></td>
              <td>: <?= $order->tanggal_dibuat ?></td>
            </tr>
          </table>
        </div>
      </div>
      <table class="table table-striped">
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
          <tr>
            <td colspan="9" class="text-right">
              <?php
              $badge_class = $grandtotal >= $order->minimum_order ? 'success' : 'danger';
              $badge_text = $grandtotal >= $order->minimum_order ? 'SUDAH MEMENUHI MIN. PO' : 'BELUM MEMENUHI MIN. PO';
              echo "<span class='badge badge-$badge_class'>$badge_text</span>";
              ?>

            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer">
    <footer>
      <button onclick="printContent()" target="_blank" class="btn btn-secondary btn-sm float-right"><i class="fa fa-print"></i> Print</button>
      <button onclick="exportSo('<?php echo $d->id_order; ?>')" data-toggle="modal" data-target=".exportSo" class="btn btn-info btn-sm float-right mr-3"><i class="fa fa-download"></i> Export</button>
      <a href="<?= base_url('Order/history') ?>" class="btn btn-danger  btn-sm float-right mr-3"><i class="fa fa-times-circle"></i> Close</a>
    </footer>
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
  // Fungsi untuk mengarahkan ke halaman sebelumnya
  function goBack() {
    window.history.back();
  }
</script>
<script>
  function printContent() {
    var printContents = document.querySelector('.areaPrint').innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    // Tambahkan kelas print-table pada elemen tabel
    var printTable = document.querySelector('.print-table');
    if (printTable) {
      printTable.classList.add('table', 'table-striped', 'print-table');
    }

    window.print();

    // Kembalikan isi body asli
    document.body.innerHTML = originalContents;
  }
</script>
<script>
  function exportSo(id) {
    $('#id_order').val(id);
  }

  function getdetail(id) {
    $('#id_order').val(id);
    // Menggunakan Ajax untuk mengambil data artikel dari server
    $.ajax({
      url: '<?= base_url('Order/getDataPo') ?>', // Ganti dengan URL ke fungsi controller yang mengambil data artikel
      type: 'GET',
      data: {
        detail: id
      },
      success: function(response) {
        // Menampilkan data artikel ke dalam modal
        var artikelList = $('#artikelList');
        artikelList.empty(); // Menghapus konten sebelumnya (jika ada)

        // Mengisi tabel dengan data artikel
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
    var harga = hargaCell.val(); // Mengambil angka dari teks
    var diskonKedua = diskonCell.val();
    var qty = parseInt(inputElement.val());
    var diskonAmount = diskonDetail(harga, diskonKedua);
    var totalHarga = diskonAmount * qty;
    totalHargaCell.text(formatRupiah(totalHarga));
  }

  // Fungsi untuk mengubah angka menjadi format rupiah
  function formatRupiah(angka) {
    var numberString = angka.toString();
    var split = numberString.split('.');

    var rupiah = split[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    var desimal = split[1] != undefined ? parseFloat('0.' + split[1]).toString().substring(2) : '';

    return 'Rp ' + rupiah + (desimal !== '' ? ',' + (desimal === '00' ? '' : desimal) : '');
  }

  // fungsi helper
  function diskonDetail(harga_satuan, diskon) {
    var diskon_parts = diskon.split('+'); // Membagi diskon menjadi bagian-bagian
    var harga_diskon = harga_satuan;

    for (var i = 0; i < diskon_parts.length; i++) {
      var diskon_decimal = parseFloat(diskon_parts[i].replace('%', '')); // Menghilangkan tanda persen (%)
      harga_diskon -= harga_diskon * (diskon_decimal / 100);
    }

    // Membulatkan hasil diskon menjadi 2 angka di belakang koma
    var hasil_bulat = harga_diskon.toFixed(2);

    return parseFloat(hasil_bulat); // Mengembalikan nilai float setelah membulatkan
  }
</script>
<script>
  document.getElementById('export-button').addEventListener('click', function(event) {
    event.preventDefault(); // Menghentikan eksekusi default (submit) dari tombol
    var urut = $('#no_urut').val();
    var tgl_kirim = $('#tgl_kirim').val();
    if (urut === "" || urut === "0000") {
      Swal.fire(
        'GAGAL',
        'Nomor urut harus diisi atau tidak boleh 0000',
        'error'
      );
    } else {
      // Jalankan submit di sini
      document.getElementById('formExport').submit(); // Ganti 'form-id' dengan ID formulir Anda
      $('.exportSo').modal('hide'); // Ganti 'modal-id' dengan ID modal sesuai dengan kode Anda
      Swal.fire(
        'BERHASIL',
        'Data berhasil di export',
        'success'
      );
    }

  });
</script>