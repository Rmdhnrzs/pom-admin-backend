<div class="card shadow mb-3">
  <div class="card-body">
    <form method="POST" action="<?= base_url('Laporan') ?>">
      <div class="form-group">
        <label>Pilih Periode Laporan</label>
        <select class="form-control" name="periode_id">
          <option>-- Pilih Periode --</option>
          <?php foreach ($periode as $p) { ?>
            <option value="<?= $p->id ?>"><?= $p->awal_periode." s.d. ".$p->akhir_periode  ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Tampilkan Data">
      </div>
    </form>
  </div>
</div>
<div class="card shadow">
  <h5 class="card-header text-white bg-info">History Pembayaran</h5>
    <div class="card-body">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>No. Karyawan</th>
          <th class="text-right">A (Rp. <?= rupiah($harga_a) ?>)</th>
          <th class="text-right">B (Rp. <?= rupiah($harga_b) ?>)</th>
          <th class="text-right">C (Rp. <?= rupiah($harga_c) ?>)</th>
          <th class="text-right">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $grandtotal = 0;
        $no = 1; foreach ($penilaian as $n) { ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= $n->nama ?></td>
          <td><?= $n->rfid ?></td>
          <td class="text-right"><?= $n->nilai_a ?></td>
          <td class="text-right"><?= $n->nilai_b ?></td>
          <td class="text-right"><?= $n->nilai_c ?></td>
          <?php
            $total_a = $harga_a * $n->nilai_a;
            $total_b = $harga_b * $n->nilai_b;
            $total_c = $harga_c * $n->nilai_c;
            $total = $total_a + $total_b + $total_c;
            $grandtotal += $total;
          ?>
          <td class="text-right">Rp. <?= rupiah($total) ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th>Total Bonus</th>
          <th class="text-right">Rp. <?= rupiah($grandtotal) ?></th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
