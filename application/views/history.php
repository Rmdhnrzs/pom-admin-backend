<div class="card" style="height:95vh; overflow:hidden;">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('Sales_order') ?>"><i class="fas fa-home"></i></a></li>
      <li class="breadcrumb-item active" aria-current="page">History</li>
    </ol>
  </nav>
  <div class="container isi d-none">
    <label for="">Pilih Tanggal</label>
    <form type="get" action="<?= base_url('Sales_order/history') ?>">
    <input type="date" class="form-control" name="tanggal" onchange="this.form.submit()" value="<?= $tanggal ?>" min="<?= date('Y-m-d', strtotime('-7 day')) ?>" max="<?= date('Y-m-d') ?>">
    <!--<input type="date" class="form-control" name="tanggal" onchange="this.form.submit()" value="<?= $tanggal ?>" max="<?= date('Y-m-d') ?>">-->
    </form>
    <hr>
    <div id="catalog" style="height: 65vh; overflow: scroll">
      <?php $no = 1; foreach ($data_history as $p) { ?>
        <div class="card mb-3 item">
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <p><b><?= date('d-m-Y',strtotime($p->tanggal_dibuat)) ?></b></p>
              </div>
              <div class="col-6 text-right">
                <p><b><?= date('H:i',strtotime($p->tanggal_dibuat)) ?></b></p>
              </div>
            </div>
            <p class="customer"> <?= $p->nama_customer ?></p>
            <?php
            $tipe_po = $p->jenis;
            if ($tipe_po == 1) {
              $tipe_po_text = "Reguler";
            } elseif ($tipe_po == 2){
              $tipe_po_text = "Spesial Price";
            } elseif ($tipe_po == 3){
              $tipe_po_text = "Barang X";
            } ?>
            <p> <?= $tipe_po_text ?></p>

            <?php
            $status = $p->status;
            if ($status == 0) {
              echo "<p class='text-warning'><i class='fas fa-hourglass-start'></i> Menunggu Diproses</p>";
            } elseif ($status == 1) {
              echo "<p class='text-success'><i class='fas fa-check'></i> Sudah Diproses</p>";
            }  elseif ($status == 2) {
              echo "<p class='text-danger'><i class='fas fa-times'></i> Ditolak MV</p>";
            }
            ?>

            <div class="text-right">
                <button class="btn btn-sm btn-link btnDetail" type="button" data-toggle="modal" data-target="#modalDetail" data-id="<?= $p->id ?>"><i class="fas fa-eye"></i> Lihat Detail</button>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="label"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="isi_detail"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-link" type="button" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

 <script>

    $(".btnDetail").click(function(){
      $("#isi_detail").html("");
      var id = $(this).data('id');
      var url = "<?= base_url('Sales_order/history_detail/') ?>"+id;
      $("#isi_detail").load(url);
    });

    $('#modalDetail').on('hidden.bs.modal', function (e) {
      $("#isi_detail").html("");
    })


</script>

