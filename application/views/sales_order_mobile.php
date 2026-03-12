<div class="card" style="height:95vh; overflow:hidden;">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('Sales_order') ?>"><i class="fas fa-home"></i></a></li>
      <li class="breadcrumb-item"><a href="<?= base_url('Sales_order/customer') ?>">Customer</a></li>
      <li class="breadcrumb-item active" aria-current="page">Produk</li>
    </ol>
  </nav>

  <div class="card-body isi d-none">
    <form>
      <div class="form-group">
        <input type="text" id="searchInput" class="form-control" placeholder="Cari kode...">
      </div>
    </form>
    <div id="catalog" style="height: 65vh; overflow: scroll">
      <?php foreach ($produk as $p) { ?>

        <div class="card mb-3 item">
          <div class="card-body">
            <p><b><i class="fas fa-tag"></i> <?= $p->kode_artikel ?></b></p>
            <p><?= $p->nama_artikel ?></p>
            <p>Size <?= $p->size ?></p>
            <p>Rp. <?= rupiah($p->harga) ?> / <?= $p->satuan ?></p>
            <!-- <div class="form-group">
              <input type="number" class="form-control" name="" value="0">
            </div> -->
            <p class="text-right"><button type="button" class="btn btn-link btn-sm btnAddCart" data-id_produk="<?= $p->id ?>" data-kode_artikel="<?= $p->kode_artikel ?>"><i class="fas fa-plus"></i> Tambah</button></p>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <div class="card-footer isi d-none p-3">
    <button class="btn btn-success btn-block" id="btnCheckout" type="button" onclick="window.location='<?= base_url('keranjang') ?>'"><i class="fas fa-forward"></i> Lanjut (<span id="jumlahCart"></span>)</button>
  </div>
</div>


 <script>
    function searchCatalog() {
        let filter, catalog, items, title;
        filter = $('#searchInput').val().toUpperCase();
        catalog = document.getElementById('catalog');
        items = catalog.getElementsByTagName('b');

        for (let i = 0; i < items.length; i++) {
            title = items[i].textContent || items[i].innerText;
            if (title.toUpperCase().indexOf(filter) > -1) {
                items[i].closest(".item").style.display = '';
            } else {
                items[i].closest(".item").style.display = 'none';
            }
        }
    }

    $("#searchInput").keyup(function(){
      searchCatalog();
    });

    $(".btnAddCart").click(function() {
      var id_produk = $(this).data('id_produk');
      var kode_artikel = $(this).data('kode_artikel');
      $.ajax({
          url: "<?= base_url('sales_order/add_cart') ?>",
          method: "POST",
          dataType: "json",
          data: {id_produk:id_produk,kode_artikel:kode_artikel},
          success: function(data) {
              if (data.message)
              {
                Swal.fire(
                  data.title,
                  data.message,
                  data.info
                );
                hitungCart();
              }
          },
          error: function() {
              Swal.fire(
                  'Gagal',
                  'Data gagal ditambahkan, silahkan mencoba kembali !',
                  'danger'
                );
              hitungCart();
          }
      });
    });


    function hitungCart(){
      $.ajax({
          url: "<?= base_url('sales_order/get_cart') ?>",
          method: "GET",
          dataType: "json",
          success: function(data) {
            if (data == 0) {
              $('#btnCheckout').attr('disabled',true);
            } else {
              $('#btnCheckout').removeAttr('disabled');
            }

            $("#jumlahCart").text(data);
          }
      });
    }



    hitungCart();


</script>