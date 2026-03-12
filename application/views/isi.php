<div class="card-body isi" >
  <form action="<?= base_url('Sales_order/simpan_customer') ?>" method="POST">
    <div class="form-group">
      <label>Pilih Customer</label>
      <select id="customer" name="id_customer" class="form-control select2" required>
        <option value="" data-alamat="-" data-tipe_customer="-">-- Pilih Customer --</option>
        <?php foreach ($customer as $row) { ?>
          <option value="<?= $row->id ?>" data-alamat="<?= $row->alamat ?>" data-nama_customer="<?= $row->nama_customer ?>" data-tipe_customer="<?= $row->tipe_harga ?>"><?= $row->nama_customer ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="form-group">
      <label>Nama Customer</label>
      <input type="text" class="form-control nama_customer" disabled>
      <input type="hidden" class="form-control" id="nama_customer" name="nama_customer">
    </div>
    <div class="form-group">
      <label>Alamat</label>
      <textarea class="form-control" id="alamat" disabled></textarea>
      <input type="hidden" class="form-control" id="alamathidden" name="alamat">
    </div>
    <div class="form-group">
      <label>Tipe</label>
      
      <input type="hidden" class="form-control tipe_customer" name="tipe_customer">
      <input type="text" class="form-control tipe_customer" disabled>
    </div>
</div>

<div class="card-footer d-none isi p-3">
  <button class="btn btn-primary btn-lg btn-block"><i class="fas fa-save"></i> Simpan</button>
</div>