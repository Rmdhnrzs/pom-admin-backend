<div class="card shadow">
  <h5 class="card-header text-white bg-info">Daftar Pelanggan</h5>
    <div class="card-body">
    <table class="table" id="datatable">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Karyawan</th>
          <th>No Pekerja</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; foreach ($karyawan as $k) { ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= $k->nama ?></td>
          <td><?= $k->rfid ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
