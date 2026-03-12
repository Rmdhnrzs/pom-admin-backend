<div class="card" style="height:95vh; overflow:hidden;">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('Sales_order') ?>"><i class="fas fa-home"></i></a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer</li>
    </ol>
  </nav>
  <div class="card-body isi d-none" >
    <form action="<?= base_url('Sales_order/simpan_customer') ?>" method="POST">
        
        <div class="form-group">
          <label>Customer</label>
          <select id="customer" name="id_customer" class="select2" required>
            <option value="" data-alamat="-" data-tipe_customer="-">-- Pilih Customer --</option>
            <?php foreach ($customer as $row) { ?>
              <option value="<?= $row->id ?>" data-alamat="<?= $row->alamat ?>" data-nama_customer="<?= $row->nama_customer ?>" data-tipe_customer="<?= $row->tipe_harga ?>"><?= $row->nama_customer ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label>Alamat</label>
          <textarea class="form-control" id="alamat" disabled></textarea>
          <input type="hidden" class="form-control" id="alamathidden" name="alamat">
        </div>
        <div class="form-group">
          <label>Tipe</label>
          <input type="hidden" class="form-control" id="nama_customer" name="nama_customer">
          <input type="hidden" class="form-control tipe_customer" name="tipe_customer">
          <input type="text" class="form-control tipe_customer" disabled>
        </div>
        <div class="form-group">
          <label>Jenis PO</label>
          <select name="tipe_po" class="form-control" required>
            <option value="">-- Pilih Jenis PO --</option>
            <option value="1">Reguler</option>
            <option value="2">Spesial Price</option>
            <option value="3">Barang X</option>
          </select>
        </div>
      
    </div>
    <div class="card-footer d-none isi p-3">
      <button class="btn btn-success btn-block"><i class="fas fa-forward"></i> Lanjut</button>
    </div>
</div>
  </form>
</div>

<script>

  $(function() {
    //Initialize Select2 Elements
    $("#customer").on("change", function(){
      var alamat = $(this).find(":selected").data('alamat');
      var nama_customer = $(this).find(":selected").data('nama_customer');
      var tipe_customer = $(this).find(":selected").data('tipe_customer');
      $("#alamat").text(alamat);
      $("#alamathidden").val(alamat);
      $(".tipe_customer").val(tipe_customer);
      $("#nama_customer").val(nama_customer);
    })
  })
</script>