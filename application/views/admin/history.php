<style>
  td {
    font-size: 11px;
    text-align: left;
  }

  tr {
    font-size: 14px;
  }

  .btn_tambah {
    margin-bottom: 10px;
  }
</style>

<div class="card shadow">
  <h5 class="card-header text-white bg-info">History Sales Order</h5>
  <div class="card-body">
    <form action="<?= base_url('Order/history') ?>" method="get">
      <div class="form-group">
        <select name="s" class="form-control col-3" value="<?= $status ?>" onchange="this.form.submit()">
          <option value="">- Pilih Status -</option>
          <option value="1">Diproses</option>
          <option value="2">Ditolak</option>
        </select>
      </div>
    </form>
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
          <th style="width:10%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 0;
        foreach ($detail as $d) :
        ?>
          <tr>
            <td><?= $d->perusahaan ?></td>
            <td><?= $d->nama_customer ?></td>
            <td><?= $d->tipe_harga ?></td>
            <td><?= jenis_so($d->jenis) ?></td>
            <td><?= $d->sales ?></td>
            <td><?= $d->referensi ?></td>
            <td><?= $d->tanggal_dibuat ?></td>
            <td><?= status_so($d->status) ?></td>
            <td>
              <a href="<?= base_url('Order/detail/' . $d->id) ?>" class="btn btn-info btn-sm"> <i class="fa fa-eye"></i> Detail</a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>