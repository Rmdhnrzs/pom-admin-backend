<div class="card shadow">
  <h5 class="card-header text-white bg-info">Sales Order Reguler</h5>
  <div class="card-body">
    <table class="table">
      <form>
      <tr>
        <td>Nama Customer</td>
        <td>
          <select id="customer" class="form-control select2">
            <option data-alamat="-" data-tipe_harga="-">-- Pilih Customer --</option>
            <?php foreach ($customer as $row) { ?>
              <option value="<?= $row->id ?>" data-alamat="<?= $row->alamat ?>" data-tipe_harga="<?= $row->tipe_harga ?>"><?= $row->nama_customer ?></option>
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td id="alamat">-</td>
      </tr>
      <tr>
        <td>Tipe Customer</td>
        <td id="tipe_harga">-</td>
      </tr>
      <tr>
        <td>Nama Sales</td>
        <td><?= $this->session->userdata('name') ?></td>
      </tr>
      <tr>
        <td>Tanggal PO</td>
        <td><input name="tanggal_po" type="date" class="form-control"></td>
      </tr>
      <tr>
        <td>No. Referensi</td>
        <td><input name="no_ref" type="text" class="form-control"></td>
      </tr>
    </form>
    </table>

    <!-- <div class="text-center"><button class="btn btn-primary">Lanjut</button></div> -->
      
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Kode</th>
            <th>Deskripsi</th>
            <th>Qty.</th>
            <th>Satuan</th>
            <th>Harga Satuan</th>
            <th>Margin</th>
            <th>Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="product-table-body"></tbody>
        <tfoot>
          <tr>
            <td colspan="7">Subtotal</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="7">Diskon</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="7">Grandtotal</td>
            <td></td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <button type="button" type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahItem"><i class="fas fa-plus"></i> Tambah Item</button>
    <hr>
    <button type="button" class="btn btn-success"><i class="fas fa-paper-plane"></i> Kirim</button>
  </div>
</div>

<!-- modal tambah item -->
<div class="modal fade" id="tambahItem" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formCart">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahItem">Tambah Barang</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <label>Pilih Barang</label>
                  <select name="id_produk" id="pilihItem" class="form-control select2">
                    <?php foreach ($produk as $p) { ?>
                      <option value="<?= $p->id ?>" data-kode_artikel="<?= $p->kode_artikel ?>"><?= $p->kode_artikel ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Qty</label>
                  <input type="number" name="qty" class="form-control" required>
                  <input type="hidden" name="kode_artikel" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="submit" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" onclick="addCart()" type="button">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
  $(function() {
    //Initialize Select2 Elements
    $("#customer").on("change", function(){
      var alamat = $(this).find(":selected").data('alamat');
      var tipe_harga = $(this).find(":selected").data('tipe_harga');
      var id_customer = $(this).find(":selected").val();
      $("#alamat").text(alamat);
      $("#tipe_harga").text(tipe_harga);
      $("#customer").attr("disabled",true);
      resetAll();
    })
  })
</script>

<script>
  $(function() {
    //Initialize Select2 Elements
    $("#pilihItem").on("change", function(){
      var kode_artikel = $(this).find(":selected").data('kode_artikel');
      $("input[name='kode_artikel']").val(kode_artikel);
    })
  })
</script>

<script>
  function getProducts() {
        $.ajax({
            url: "<?= base_url('sales_order/get_cart') ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {
                displayProducts(data);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching products:", status, error);
            }
        });
    }

  function deleteProduct(id) {
        $.ajax({
            url: "<?= base_url('sales_order/delete_cart') ?>",
            method: "POST",
            dataType: "json",
            data: {id:id},
            success: function(data) {
                getProducts();
            },
            error: function(xhr, status, error) {
                alert('Item gagal dihapus !');
            }
        });
    }

  function displayProducts(products) {
      var productTableBody = $("#product-table-body");
      productTableBody.empty();
      var number = 1;
      var subtotal = 1;
        $.each(products, function(index, product) {
            var row = $("<tr>");
            var numberCell = $("<td>").text(number++);
            var productNameCell = $("<td>").text(product.name);
            var productDescCell = $("<td>").text(product.options.desc);
            var productQtyCell = $("<td>").text(product.qty);
            var satuanCell = $("<td>").text(product.options.satuan);
            var size = product.options.satuan;
            var prizeCell = $("<td>").text(product.price);
            var marginCell = $("<td>").text(product.options.diskon);
            var totalCell = $("<td>").text(product.options.total);
            var actionCell = $("<td>");
            var deleteButton = $("<button>").addClass("btn btn-danger btn-sm").text("Hapus");
            deleteButton.click(function() {
                deleteProduct(product.rowid);
            });
            productDescCell.append(" ("+size+")");
            actionCell.append(deleteButton);
            row.append(numberCell, productNameCell, productDescCell, productQtyCell,satuanCell , prizeCell, marginCell, totalCell, actionCell);
            productTableBody.append(row);
        });
  }

  function addCart() {
    var qty = $("#formCart input[name='qty']").val();
    var id_produk = $(this).data();
    var kode_artikel = $(this).data();
    $.ajax({
        url: "<?= base_url('sales_order/add_cart') ?>",
        method: "POST",
        dataType: "json",
        data: {qty:qty,id_produk:id_produk,kode_artikel:kode_artikel},
        success: function(data) {
            if (data.success)
            {
              $('#tambahItem').modal('hide');
              getProducts();
            }
            else if (data.error)
            {
                window.alert(data.error);
            }
        },
        error: function() {
            alert('Item gagal ditambahkan !');
        }
    });
  }

  function resetAll() {
    $.ajax({
        url: "<?= base_url('sales_order/reset_cart') ?>",
        method: "GET",
        success: function(data) {
            getProducts();
        }
    });
  }

  resetAll();
</script>