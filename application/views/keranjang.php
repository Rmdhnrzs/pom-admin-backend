<style>
.qty-control__inner {
  display: flex;
  align-items: center;
  background: #fff;
  border: 1.5px solid #dee2e6;
  border-radius: 50px;
  overflow: hidden;
  height: 48px;
  transition: border-color 0.2s;
}

.qty-control__btn {
  width: 52px;
  height: 100%;
  border: none;
  background: transparent;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.qty-control__btn--minus { color: #dc3545; }
.qty-control__btn--plus  { color: #28a745; }

.qty-control__btn:active { background: #f0f0f0; }

.qty-control__display {
  flex: 1;
  text-align: center;
  border-left: 1px solid #dee2e6;
  border-right: 1px solid #dee2e6;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  line-height: 1.2;
  user-select: none;
}

.qty-control__value {
  font-size: 18px;
  font-weight: 700;
  color: #212529;
  transition: transform 0.1s;
}

.qty-control__satuan {
  font-size: 9px;
  color: #adb5bd;
  text-transform: uppercase;
  letter-spacing: 0.8px;
}

/* States */
.qty-control--loading .qty-control__inner {
  opacity: 0.5;
  pointer-events: none;
}
.qty-control--success .qty-control__inner {
  border-color: #28a745;
}
.qty-control--error .qty-control__inner {
  border-color: #dc3545;
}
.qty-control--min .qty-control__btn--minus {
  opacity: 0.25;
  pointer-events: none;
}

/* Bump animation saat qty berubah */
.qty-control__value.bump {
  transform: scale(1.3);
}

.qty-control__feedback {
  font-size: 11px;
  margin-top: 3px;
  text-align: center;
  min-height: 16px;
}

/* Badge kelipatan */
.badge-kelipatan {
  font-size: 10px;
  background: #fff3cd;
  color: #856404;
  border: 1px solid #ffc107;
  border-radius: 4px;
  padding: 1px 5px;
  margin-left: 4px;
}
</style>
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
              <?php
              $kelipatan = (int)($p->kelipatan ?? 1);
              $kelipatan = max(1, $kelipatan);
              // Pastikan qty awal valid (kelipatan)
              $qty = (int)($p->qty ?? $kelipatan);
              if ($kelipatan > 1 && $qty % $kelipatan !== 0) {
                $qty = (int)(ceil($qty / $kelipatan) * $kelipatan);
              }
              $qty = max($qty, $kelipatan);
              ?>

              <label class="mb-1">
                <span>Qty</span>
                <?php if ($kelipatan > 1): ?>
                  <span class="badge-kelipatan">
                    <i class="fas fa-layer-group"></i> ×<?= $kelipatan ?>
                  </span>
                <?php endif; ?>
              </label>

              <div class="qty-control"
                data-kelipatan="<?= $kelipatan ?>"
                data-rowid="<?= $p->rowid ?>"
                data-satuan="<?= $p->satuan ?>">

                <div class="qty-control__inner">
                  <button type="button" class="qty-control__btn qty-control__btn--minus">
                    <i class="fas fa-minus"></i>
                  </button>
                  <div class="qty-control__display">
                    <span class="qty-control__value"><?= $qty ?></span>
                    <span class="qty-control__satuan"><?= $p->satuan ?></span>
                  </div>
                  <button type="button" class="qty-control__btn qty-control__btn--plus">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>

                <input type="hidden" name="qty"   class="qty-control__input" value="<?= $qty ?>">
                <input type="hidden" name="rowid" value="<?= $p->rowid ?>">

                <div class="qty-control__feedback"></div>
              </div>
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
function kelipatanNext(qty, step) {
  if (step <= 1) return qty + 1;
  return Math.ceil((qty + 1) / step) * step;
}

function kelipatanPrev(qty, step) {
  if (step <= 1) return Math.max(1, qty - 1);
  return Math.max(step, Math.floor((qty - 1) / step) * step);
}

var QtyControl = {

  _timer: {},   

  init: function () {
    $(document).on('click', '.qty-control__btn--plus', function () {
      QtyControl.step($(this).closest('.qty-control'), 1);
    });
    $(document).on('click', '.qty-control__btn--minus', function () {
      QtyControl.step($(this).closest('.qty-control'), -1);
    });
  },

  step: function ($ctrl, direction) {
    var kelipatan = parseInt($ctrl.data('kelipatan')) || 1;
    var input     = $ctrl.find('.qty-control__input');
    var current   = parseInt(input.val()) || kelipatan;

    var newQty;
    if (direction > 0) {
      newQty = kelipatanNext(current, kelipatan);
    } else {
      newQty = kelipatanPrev(current, kelipatan);
    }


    if (newQty < kelipatan) newQty = kelipatan;

    QtyControl._applyAndSync($ctrl, newQty);
  },

  _applyAndSync: function ($ctrl, newQty) {
    var input     = $ctrl.find('.qty-control__input');
    var display   = $ctrl.find('.qty-control__value');
    var kelipatan = parseInt($ctrl.data('kelipatan')) || 1;
    var rowid     = $ctrl.data('rowid');
    var oldQty    = parseInt(input.val());

    // Validasi kelipatan sebelum apply
    if (kelipatan > 1 && newQty % kelipatan !== 0) {
      QtyControl._feedback($ctrl, 'Qty harus kelipatan ' + kelipatan, 'danger');
      return;
    }


    input.val(newQty);
    QtyControl._bumpDisplay(display, newQty);
    QtyControl._checkMin($ctrl, newQty, kelipatan);
    QtyControl.setLoading($ctrl, true);

    clearTimeout(QtyControl._timer[rowid]);
    QtyControl._timer[rowid] = setTimeout(function () {

      var diskon = $ctrl.closest('.card').find("select[name='diskon']").val() || '0%';

      $.ajax({
        type: "POST",
        url: "<?= base_url('Keranjang/edit_item') ?>",
        data: { qty: newQty, diskon: diskon, rowid: rowid },
        dataType: "json",
        success: function () {
          QtyControl.setLoading($ctrl, false);
          QtyControl._flashState($ctrl, 'success');
          subtotal();
        },
        error: function () {
          // Rollback
          input.val(oldQty);
          QtyControl._bumpDisplay(display, oldQty);
          QtyControl._checkMin($ctrl, oldQty, kelipatan);
          QtyControl.setLoading($ctrl, false);
          QtyControl._flashState($ctrl, 'error');
          QtyControl._feedback($ctrl, 'Gagal menyimpan, coba lagi', 'danger');
        }
      });

    }, 300);
  },

  setLoading: function ($ctrl, state) {
    $ctrl.toggleClass('qty-control--loading', state);
  },

  _bumpDisplay: function ($el, val) {
    $el.text(val).addClass('bump');
    setTimeout(function () { $el.removeClass('bump'); }, 150);
  },

  _checkMin: function ($ctrl, qty, kelipatan) {
    $ctrl.toggleClass('qty-control--min', qty <= kelipatan);
  },

  _flashState: function ($ctrl, state) {
    $ctrl.addClass('qty-control--' + state);
    setTimeout(function () {
      $ctrl.removeClass('qty-control--success qty-control--error');
    }, 800);
  },

  _feedback: function ($ctrl, msg, type) {
    var $fb = $ctrl.find('.qty-control__feedback');
    $fb.removeClass('text-danger text-success text-muted')
       .addClass('text-' + type)
       .text(msg);
    clearTimeout($ctrl.data('fbTimer'));
    $ctrl.data('fbTimer', setTimeout(function () {
      $fb.text('');
    }, 2500));
  },


  setValue: function ($ctrl, newQty) {
    QtyControl._applyAndSync($ctrl, newQty);
  }
};

$(document).ready(function () {
  QtyControl.init();

  // Set state min pada load awal
  $('.qty-control').each(function () {
    var $ctrl     = $(this);
    var kelipatan = parseInt($ctrl.data('kelipatan')) || 1;
    var qty       = parseInt($ctrl.find('.qty-control__input').val()) || kelipatan;
    QtyControl._checkMin($ctrl, qty, kelipatan);
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