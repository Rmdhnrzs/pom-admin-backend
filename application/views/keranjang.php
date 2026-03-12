<div class="card" style="height:95vh; overflow:hidden;">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('Sales_order') ?>"><i class="fas fa-home"></i></a></li>
      <li class="breadcrumb-item"><a href="<?= base_url('Sales_order/customer') ?>">Customer</a></li>
      <li class="breadcrumb-item"><a href="<?= base_url('Sales_order/list_produk') ?>">Produk</a></li>
      <li class="breadcrumb-item active" aria-current="page">Checkout</li>
    </ol>
  </nav>
  <div class="card-body isi d-none">
    <div id="catalog" style="height: 100%; overflow: scroll;" class="small">
      <?php foreach ($list as $p) { ?>

        <div class="card mb-3">
          <div class="card-body">
            <p><b><?= $p->kode_artikel . " (Size " . $p->size . ")" ?></b></p>
            <p><?= $p->nama_artikel  ?></p>
            <p><?= "Rp. " . rupiah($p->harga) . "/" . $p->satuan ?></p>
            <div class="row">
              <div class="form-group col">
                <label for="">Qty</label>
                <input name="qty" type="number" class="form-control" value="<?= $p->qty ?>">
                <input name="rowid" type="hidden" class="form-control" value="<?= $p->rowid ?>">
              </div>
              <?php if ($tipe_po != 1) { ?>
              <div class="form-group col-6">
                <label for="">Diskon</label>
                <select name="diskon" class="form-control">
                  <option value="0%">0%</option>
                  <option value="15%">15%</option>
                  <option value="20%">20%</option>
                  <option value="22.5%">22.5%</option>
                  <option value="25%">25%</option>
                  <option value="30%">30%</option>
                  <option value="50%+15%">50%+15%</option>
                  <option value="50%+17.5%">50%+17.5%</option>
                  <option value="50%+20%">50%+20%</option>
                  <option value="50%+22.5%">50%+22.5%</option>
                  <option value="50%+25%">50%+25%</option>
                </select>
              </div>
              <?php } ?>
            </div>
            <div class="text-right">
              <button type="button" class="btn btn-sm btn-link btnHapus" data-rowid="<?= $p->rowid ?>"><i class="fas fa-trash"></i></button>
            </div>
          </div>
        </div>
      <?php } ?>

      <form id="formMain" enctype="multipart/form-data">
        <div class="form-group">
          <label><b>File PO</b><small class="text-danger"> *Wajib dilampirkan</small></label>
          <input id="lampiran" class="form-control" name="lampiran" type="file" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,capture=camera" required>
          <p><small>Ukuran file tidak boleh melebihi 5MB.</small></p>
        </div>
        <div class="form-group">
          <label><b>No. PO</b></label><br>
          <input type="radio" name="isNomerPO" value="1" checked> Ada
          <input type="radio" name="isNomerPO" value="0"> Tidak Ada
          <input type="text" class="form-control" id="referensi" name="referensi">
        </div>
        <div class="form-group">
          <label><b>Catatan</b></label>
          <textarea class="form-control" id="catatan" name="catatan"></textarea>
        </div>
        <hr>
        <div class="form-group">
          <label>Nama Customer</label>
          <p><b><?= $nama_customer ?></b></p>
        </div>
        <div class="form-group">
          <label>Jenis PO</label>
          <?php
          if ($tipe_po == 1) {
            $tipe_po_text = "Reguler";
          } elseif ($tipe_po == 2) {
            $tipe_po_text = "Spesial Price";
          } elseif ($tipe_po == 3) {
            $tipe_po_text = "Barang X";
          } ?>
          <p><b><?= $tipe_po_text ?></b></p>
        </div>
        <hr>
        <div class="form-group text-right">
          <label>Total</label>
          <p><b>
              <div id="total"></div>
            </b></p>
        </div>
    </div>
  </div>
  <div class="card-footer isi d-none p-3">
    <button class="btn btn-success btn-block" type="button" id="btnProses"><i class="fas fa-save"></i> Simpan</button>
  </div>

  </form>
</div>

<script>
  $("[name='isNomerPO']").change(function() {
    var pilih = $("input[name='isNomerPO']:checked").val();
    if (pilih == 0) {
      $("#referensi").addClass('d-none', true);
    } else {
      $("#referensi").removeClass('d-none');
    }
  })

  function subtotal() {
    var diskon_faktur = $("#diskon_faktur").val();
    $.ajax({
      url: "<?= base_url('Keranjang/subtotal') ?>",
      method: "POST",
      dataType: "json",
      data: {
        // diskon_faktur: diskon_faktur
      },
      success: function(data) {
        $("#total").html(data.total);
      }
    });
  }


  $("#btnProses").click(function(event) {
    var pilih = $("input[name='isNomerPO']:checked").val();
    var referensi = $('#referensi').val();
    var catatan = $('#catatan').val();
    var diskon_faktur = $('#diskon_faktur').val();
    var lampiran = $("#lampiran")[0].files[0];
    var formData = new FormData();

    if (typeof(lampiran) == "undefined") {
      Swal.fire(
        'Info',
        'Silahkan pilih file lampiran PO terlebih dahulu !',
        'warning'
      );
      return;
    }

    if (pilih == 1 && referensi == "") {
      Swal.fire(
        'Info',
        'Silahkan masukkan No. PO',
        'warning'
      );
      return;
    }


    formData.append("diskon_faktur", diskon_faktur);
    formData.append("catatan", catatan);
    formData.append("referensi", referensi);
    formData.append("lampiran", lampiran);


    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Data akan dikirim ke tim marketing.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Tidak',
      confirmButtonText: 'Ya'
    }).then((result) => {
      if (result.isConfirmed) {
        $('#loading').show();
        $('#btnProses').attr('disabled', true);

        $.ajax({
          url: "<?= base_url('keranjang/proses') ?>",
          method: "POST",
          dataType: "json",
          data: formData,
          contentType: false,
          processData: false,
          success: function(data) {
            if (data.status == 1) {
              Swal.fire(
                'Berhasil!',
                'Data berhasil disimpan.',
                'success'
              ).then((result) => {
                if (result.isConfirmed) {
                  location.href = "<?= base_url('sales_order') ?>";
                }
              })
            } else {
              Swal.fire(
                'Gagal!',
                data.error,
                'error'
              ).then((result) => {
                if (result.isConfirmed) {
                  $('#loading').hide();
                  $('#btnProses').removeAttr('disabled');
                }
              })
            }
          },
          error: function(){
            $('#loading').hide();
            $('#btnProses').removeAttr('disabled');
          }
        });
      }
    })
  })
  subtotal();
</script>

<script>
  $(".btnHapus").click(function() {
    var rowid = $(this).data('rowid');
    var card = $(this).closest(".card");
    Swal.fire({
      title: 'Apakah anda yakin ingin menghapus item ini?',
      showCancelButton: true,
      cancelButtonText: 'Tidak',
      confirmButtonColor: 'blue',
      cancelButtonColor: 'red',
      confirmButtonText: 'Ya',
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {

        $.ajax({
          url: "<?= base_url('sales_order/delete_cart/') ?>" + rowid,
          method: "GET",
          dataType: "json",
          success: function(data) {
            if (data.sukses) {
              card.remove();
              subtotal();

            }
          }
        });
      }
    })

  });
</script>

<script>
  $("[name='qty']").on("focus", function() {
    var card = $(this).closest(".card");
    const qty = card.find("[name='qty']").val();
    const diskon = card.find("select[name='diskon']").val();
    const rowid = card.find("[name='rowid']").val();

    $(this).change(function() {
      var editQty = card.find("[name='qty']").val();
      var editDiskon = card.find("select[name='diskon']").val();
      var editRowid = card.find("[name='rowid']").val();
      $.ajax({
        type: "POST",
        url: "<?= base_url('Keranjang/edit_item') ?>",
        data: {
          qty: editQty,
          diskon: editDiskon,
          rowid: editRowid
        },
        dataType: "json",
        success: function(response) {
          subtotal();
        },
        error:function(){
          card.find("[name='qty']").val(qty);
          card.find("select[name='diskon']").val(diskon);
          card.find("[name='rowid']").val(rowid);
        }
      });
    });
  });
</script>

<script>
  $("[name='diskon']").on("focus", function() {
    var card = $(this).closest(".card");
    const qty = card.find("[name='qty']").val();
    const diskon = card.find("select[name='diskon']").val();
    const rowid = card.find("[name='rowid']").val();

    $(this).change(function() {
      var editQty = card.find("[name='qty']").val();
      var editDiskon = card.find("select[name='diskon']").val();
      var editRowid = card.find("[name='rowid']").val();
      $.ajax({
        type: "POST",
        url: "<?= base_url('Keranjang/edit_item') ?>",
        data: {
          qty: editQty,
          diskon: editDiskon,
          rowid: editRowid
        },
        dataType: "json",
        success: function(response) {
          subtotal();
        },
        error:function(){
          card.find("[name='qty']").val(qty);
          card.find("select[name='diskon']").val(diskon);
          card.find("[name='rowid']").val(rowid);
        }
      });
    });
  });
</script>