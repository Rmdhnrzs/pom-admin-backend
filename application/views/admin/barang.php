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

  .btn_import {
    margin-right: 10px;
  }

  .table-responsive {
    max-height: 400px;
    overflow-y: auto;
  }

  .table tbody tr:hover {
    background-color: #f8fafc;
    transition: 0.2s;
  }

  thead th {
    position: sticky;
    top: 0;
    z-index: 2;
  }

  .badge {
    font-size: 11px;
    padding: 5px 8px;
  }

  .data-barang {
    position: relative;
    padding: 10px;
    border: 1px solid #ccc;
    transition: background-color 0.3s ease-in-out;
  }

  .btn_edit,
  .btn_delete {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    background-color: rgba(0, 0, 0, 0.3);
    border: none;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
  }

  .btn_edit {
    right: 0;
  }

  .btn_delete {
    right: 40px;
  }

  .data-barang:hover {
    background-color: #f5f5f5;
  }

  .data-barang:hover .btn_edit,
  .data-barang:hover .btn_delete {
    opacity: 1;
  }
</style>

<div class="card shadow">
  <h5 class="card-header text-white bg-info">Daftar Barang</h5>
  <div class="card-body">
    <button type="button" class="btn btn-success btn-sm float-right btn_tambah" data-toggle="modal" data-target="#modal_tambah">
      <i class="fas fa-plus"></i> Tambah Barang
    </button>
    <button type="button" class="btn btn-secondary btn-sm float-right btn_import" data-toggle="modal" data-target="#modal_import">
      <i class="fas fa-file-import"></i> Import Barang
    </button>
    <table class="table table-striped" id="datatable" style="width: 100%;">
      <thead>
        <tr>
          <th>No</th>
          <th>PT</th>
          <th>Kode</th>
          <th>Barang</th>
          <th>Keterangan</th>
          <th>Size</th>
          <th>Satuan</th>
          <th>Kelipatan</th>
          <th>Retail</th>
          <th>Grosir</th>
          <th>Grosir_10</th>
          <th>HET_Jawa</th>
          <th>Indo_Barat</th>
          <th>SP</th>
          <th>Brg X</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1;
        foreach ($barang as $k) { ?>
          <tr class="data-barang">
            <td><?= $no++ ?></td>
            <td><?= strtoupper($k->nama_perusahaan) ?></td>
            <td>
              <?= $k->kode_artikel ?>
              <br>
              <?php
              if ($k->kategori == 1) {
                echo "<span class='badge badge-danger badge-sm'>Barang X</span>";
              }
              ?>
            </td>
            <td><?= $k->nama_artikel ?></td>
            <td data-bs-toggle="tooltip" title="<?= $k->keterangan ?>"><?= (strlen($k->keterangan) > 40 ? substr($k->keterangan, 0, 40) . '...' : $k->keterangan) ?></td>
            <td><?= $k->size ?></td>
            <td><?= $k->satuan ?></td>
            <td><?= $k->step_qty > 1 ? 'x'.$k->step_qty : '-' ?></td>
            <td>Rp <?= number_format($k->retail) ?></td>
            <td>Rp <?= number_format($k->grosir) ?></td>
            <td>Rp <?= number_format($k->grosir_10) ?></td>
            <td>Rp <?= number_format($k->het_jawa) ?></td>
            <td>Rp <?= number_format($k->indo_barat) ?></td>
            <td>Rp <?= number_format($k->special_price) ?></td>
            <td>Rp <?= number_format($k->barang_x) ?>
              <button type="button" class="btn btn-warning btn-sm btn_edit" data-toggle="modal" data-target="#exampleModalCenter" onclick="getdetail('<?php echo $k->id; ?>')">
                <i class="fas fa-edit"></i>
              </button>
              <a href="#" class="btn btn-danger btn-sm btn_delete" data-id="<?= $k->id ?>">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<!-- tambah barang -->
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
  <form action="<?= base_url('Barang/simpan') ?>" method="POST">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="exampleModalLongTitle">Tambah Barang Baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Perusahaan :</label>
            <select name="perusahaan" class="form-control" required>
              <option value="">-- Pilih Perusahaan --</option>
              <?php foreach ($perusahaan as $p) { ?>
                <option value="<?= $p->id ?>"><?= $p->nama ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Kode Barang :</label>
                <input type="text" name="kode" id="kode_add" class="form-control form-control-sm" required>
              </div>
              <div class="form-group">
                <label for="">Nama Barang :</label>
                <input type="text" name="barang" class="form-control form-control-sm" required>
              </div>
              <div class="form-group">
                <label for="">Keterangan :</label>
                <textarea name="keterangan" class="form-control form-control-sm" rows="3"></textarea>
              </div>
              <div class="form-group">
                <label for="">Size :</label>
                <select name="size" class="form-control form-control-sm" required>
                  <option value="">- Pilih Size -</option>
                  <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                  <option value="XL">XL</option>
                  <option value="XXL">XXL</option>
                  <option value="XXXL">XXXL</option>
                  <option value="XXXXL">XXXXL</option>
                  <option value="S/M">S/M</option>
                  <option value="L/XL">L/XL</option>
                  <option value="M/L">M/L</option>
                  <option value="XL/XXL">XL/XXL</option>
                  <option value="ALL SIZE">ALL SIZE</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Satuan :</label>
                <select name="satuan" class="form-control form-control-sm" required>
                  <option value="">- Pilih Satuan -</option>
                  <option value="Pck">Pck</option>
                  <option value="Pcs">Pcs</option>
                  <option value="Box">Box</option>
                  <option value="Psg">Psg</option>
                  <option value="BOX">BOX</option>
                </select>
              </div>
              <div class="form-group">
                <label>Kelipatan :</label>
                <input type="number" name="step_qty" class="form-control form-control-sm" value="1" min="1">
                <small class="text-muted">Contoh: 5 = hanya bisa 5,10,15</small>
              </div>
              
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Kategori :</label>
                <select name="kategori" id="kategori_add" class="form-control form-control-sm" required>
                  <option value="">- Pilih kategori -</option>
                  <option value="0">Barang Normal</option>
                  <option value="1">Barang X</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Retail :</label>
                <input type="text" name="retail" id="retail_add" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Grosir :</label>
                <input type="text" name="grosir" id="grosir_add" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Grosir_10 :</label>
                <input type="text" name="grosir_10" id="grosir_10_add" class="form-control form-control-sm" autocomplete="off">
              </div>

            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Het Jawa :</label>
                <input type="text" name="het_jawa" id="het_jawa_add" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Indo Barat :</label>
                <input type="text" name="indo_barat" id="indo_barat_add" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Special Price :</label>
                <input type="text" name="special_price" id="sp_add" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Barang X :</label>
                <input type="text" name="barang_x" id="barang_x_add" class="form-control form-control-sm" autocomplete="off">
              </div>

            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success btn-sm">Simpan</button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- end tambah -->

<!-- modal import -->
<div class="modal fade" id="modal_import" tabindex="-1" data-backdrop="static">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content shadow">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">
          <i class="fas fa-file-import mr-2"></i> Import Data Barang
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <form id="form_import" action="<?= base_url('Barang/import') ?>" method="POST" enctype="multipart/form-data">

        <div class="modal-body">

          <!-- INPUT -->
          <div class="row">
            <div class="col-md-6">
              <label>Perusahaan</label>
              <select name="id_perusahaan" id="import_perusahaan" class="form-control" required>
                <option value="">-- Pilih --</option>
                <?php foreach ($perusahaan as $p) { ?>
                  <option value="<?= $p->id ?>"><?= $p->nama ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-6">
              <label>File Excel</label>
              <input type="file" name="file" id="file_input" class="form-control" accept=".xlsx,.xls,.csv" required>
            </div>
          </div>

          <!-- LOADING -->
          <div id="loading_import" class="text-center mt-4 d-none">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Menganalisa file Excel...</p>
          </div>

          <!-- SUMMARY -->
          <div id="summary_import" class="alert alert-light border mt-3 d-none"></div>

          <!-- WARNING -->
          <div id="warning_import" class="alert alert-warning mt-2 d-none">
            Sistem hanya akan menambahkan data baru dan mengisi data kosong.
          </div>

          <!-- PREVIEW -->
          <div id="preview_excel" class="mt-3 d-none">
            <div class="row">

              <!-- EXCEL -->
              <div class="col-md-6">
                <h6 class="text-primary">Excel</h6>
                <div class="table-responsive">
                  <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                      <tr>
                        <th>Status</th>
                        <th>Kode</th>
                        <th>PT</th>
                        <th>Barang</th>
                        <th>Keterangan</th>
                        <th>Size</th>
                        <th>Satuan</th>
                        <th>Retail</th>
                        <th>Grosir</th>
                        <th>G10</th>
                        <th>HET</th>
                        <th>Indo</th>
                        <th>SP</th>
                        <th>Brg X</th>
                      </tr>
                    </thead>
                    <tbody id="preview_excel_body"></tbody>
                  </table>
                </div>
              </div>

              <!-- DB -->
              <div class="col-md-6">
                <h6 class="text-warning">Database</h6>
                <div class="table-responsive">
                  <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                      <tr>
                        <th>Status</th>
                        <th>Kode</th>
                        <th>PT</th>
                        <th>Barang</th>
                        <th>Keterangan</th>
                        <th>Size</th>
                        <th>Satuan</th>
                        <th>Retail</th>
                        <th>Grosir</th>
                        <th>G10</th>
                        <th>HET</th>
                        <th>Indo</th>
                        <th>SP</th>
                        <th>Brg X</th>
                      </tr>
                    </thead>
                    <tbody id="preview_db_body"></tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" id="btn_import" class="btn btn-success btn-sm">Import</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
  <form action="<?= base_url('Barang/update') ?>" method="POST">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="exampleModalLongTitle">Update Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Perusahaan :</label>
            <input id="perusahaan" type="text" class="form-control" disabled>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Kode Barang :</label>
                <input type="text" name="kode" id="kode" class="form-control form-control-sm" readonly>
                <input type="hidden" name="id_barang" id="id_barang" class="form-control form-control-sm" readonly>
              </div>
              <div class="form-group">
                <label for="">Nama Barang :</label>
                <input type="text" name="barang" id="barang" class="form-control form-control-sm" required>

              </div>
              <div class="form-group">
                <label for="">Keterangan :</label>
                <textarea name="keterangan" id="keterangan" class="form-control form-control-sm" rows="3"></textarea>

              </div>
              <div class="form-group">
                <label for="">Size :</label>
                <select name="size" id="size_edit" class="form-control form-control-sm" required>
                  <option value="">- Pilih Size -</option>
                 <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                  <option value="XL">XL</option>
                  <option value="XXL">XXL</option>
                  <option value="XXXL">XXXL</option>
                  <option value="XXXXL">XXXXL</option>
                  <option value="S/M">S/M</option>
                  <option value="L/XL">L/XL</option>
                  <option value="M/L">M/L</option>
                  <option value="XL/XXL">XL/XXL</option>
                  <option value="ALL SIZE">ALL SIZE</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Satuan :</label>
                <select name="satuan" id="satuan_input" class="form-control form-control-sm">
                  <option value="Pck">Pck</option>
                  <option value="Pcs">Pcs</option>
                  <option value="Box">Box</option>
                  <option value="Psg">Psg</option>
                  <option value="BOX">BOX</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label>Kelipatan :</label>
              <input type="number" name="step_qty" id="step_qty" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Kategori :</label>
                <select name="kategori" id="kategori" class="form-control form-control-sm" required>
                  <option value="">- Pilih kategori -</option>
                  <option value="0">Barang Normal</option>
                  <option value="1">Barang X</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Retail :</label>
                <input type="text" name="retail" id="retail" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Grosir :</label>
                <input type="text" name="grosir" id="grosir" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Grosir_10 :</label>
                <input type="text" name="grosir_10" id="grosir_10" class="form-control form-control-sm" autocomplete="off">
              </div>
            </div>
            <div class="col-md-4">

              <div class="form-group">
                <label for="">Het Jawa :</label>
                <input type="text" name="het_jawa" id="het_jawa" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Indo Barat :</label>
                <input type="text" name="indo_barat" id="indo_barat" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Special Price :</label>
                <input type="text" name="special_price" id="sp" class="form-control form-control-sm" autocomplete="off">
              </div>
              <div class="form-group">
                <label for="">Barang X :</label>
                <input type="text" name="barang_x" id="barang_x" class="form-control form-control-sm" autocomplete="off">
              </div>

            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger  btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary  btn-sm">Simpan</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
  function formatRupiah(angka) {
    var number_string = angka.toString().replace(/[^,\d]/g, ""),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? "." : "";
      rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return "Rp " + rupiah;
  }

  function getdetail(id) {
    // Menggunakan Ajax untuk mengambil data artikel dari server
    $.ajax({
      url: '<?= base_url('Barang/getdata') ?>',
      type: 'GET',
      data: {
        id_barang: id
      },
      success: function(response) {
        // Mengisi form dengan data artikel
        if (response) {
          $('#id_barang').val(response.id_barang);
          $('#perusahaan').val(response.perusahaan.toUpperCase());
          $('#kode').val(response.kode);
          $('#keterangan').val(response.keterangan);
          $('#barang').val(response.nama);
          $('#satuan_input').val(response.satuan);
          $('#step_qty').val(response.step_qty);
          $('#size_edit').val(response.size);
          $('#kategori').val(response.kategori);
          $('#retail').val(formatRupiah(response.retail));
          $('#grosir').val(formatRupiah(response.grosir));
          $('#grosir_10').val(formatRupiah(response.grosir_10));
          $('#het_jawa').val(formatRupiah(response.het_jawa));
          $('#indo_barat').val(formatRupiah(response.indo_barat));
          $('#sp').val(formatRupiah(response.special_price));
          $('#barang_x').val(formatRupiah(response.barang_x));
          if (response.kategori === '1') { // Membandingkan dengan string "1"
            $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp").prop("readonly", true);
            $("#barang_x").prop("readonly", false);
          } else {
            $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp").prop("readonly", false);
            $("#barang_x").prop("readonly", true);
          }
        }
      },
      error: function(xhr, status, error) {
        console.log(error);
      }
    });
  }
  $(document).ready(function() {
    // Tambahkan event listener ke input retail, grosir, grosir_10, het_jawa, indo_barat, dan sp
    $('#retail, #grosir, #grosir_10, #het_jawa, #indo_barat, #sp,#barang_x, #retail_add, #grosir_add, #grosir_10_add, #het_jawa_add, #indo_barat_add, #sp_add, #barang_x_add').on('keyup', function() {
      var angka = $(this).val().replace(/[Rp.,]/g, ''); // Hilangkan karakter 'Rp', '.' dan ',' dari nilai input
      var rupiah = formatRupiah(angka); // Ubah nilai menjadi format rupiah
      $(this).val(rupiah); // Set nilai input menjadi format rupiah
    });
  });
</script>
<script>
  $('.btn_delete').click(function(e) {
    const id = $(this).data('id');
    e.preventDefault();
    Swal.fire({
      title: 'Hapus Data',
      text: "Apakah anda yakin untuk Menghapusnya ?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Batal',
      confirmButtonText: 'Yakin'
    }).then((result) => {
      if (result.isConfirmed) {
        location.href = "<?php echo base_url('Barang/hapus_data/') ?>" + id;
      }
    })
  })
</script>
<script>
  $(document).ready(function() {
    // Fungsi untuk melakukan pemeriksaan kode barang saat pengguna mengetikkan
    $("#kode_add").on("blur", function() {
      var kodeBarang = $(this).val();

      // Mengirimkan kode barang ke server untuk memeriksa keberadaannya di database
      $.ajax({
        url: "<?php echo base_url('Barang/check_kode_exist'); ?>",
        method: "POST",
        data: {
          kode: kodeBarang
        },
        dataType: "json",
        success: function(response) {
          if (response.exist) {
            // Jika kode sudah ada di database, tampilkan pesan
            Swal.fire(
              'Kode Artikel sudah ada',
              'Harap cek kembali / gunakan kode yang lain',
              'info'
            );
            $('#kode_add').css({
              'border': '1px solid red'
            });
            $('#kode_add').val('');
          } else {
            $("#kode_status").html(""); // Hapus pesan jika kode tidak ada di database
            $("button[type='submit']").prop("disabled", false); // Enable kembali tombol submit jika kode tidak ada
          }
        }
      });
    });
    $("#kategori_add").on("change", function() {
      var kategori = $(this).val();
      if (kategori === "1") { // Membandingkan dengan string "1"
        $("#retail_add,#grosir_add,#grosir_10_add,#het_jawa_add,#indo_barat_add,#sp_add").prop("readonly", true);
        $("#retail_add,#grosir_add,#grosir_10_add,#het_jawa_add,#indo_barat_add,#sp_add,#barang_x_add").val('');
        $("#barang_x_add").prop("readonly", false);
      } else {
        $("#retail_add,#grosir_add,#grosir_10_add,#het_jawa_add,#indo_barat_add,#sp_add").prop("readonly", false);
        $("#retail_add,#grosir_add,#grosir_10_add,#het_jawa_add,#indo_barat_add,#sp_add,#barang_x_add").val('');
        $("#barang_x_add").prop("readonly", true);
      }
    });
    $("#kategori").on("change", function() {
      var kategori = $(this).val();
      if (kategori === "1") { // Membandingkan dengan string "1"
        $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp").prop("readonly", true);
        $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp,#barang_x").val('');
        $("#barang_x").prop("readonly", false);
      } else {
        $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp").prop("readonly", false);
        $("#retail,#grosir,#grosir_10,#het_jawa,#indo_barat,#sp,#barang_x").val('');
        $("#barang_x").prop("readonly", true);
      }
    });

  });
</script>

<script>
$(document).ready(function(){

  $('#file_input').on('change', function(){

    let file = this.files[0];
    let id_perusahaan = $('#import_perusahaan').val();

    if(!file) return;

    if(!id_perusahaan){
      alert('Pilih perusahaan dulu!');
      $('#file_input').val('');
      return;
    }

    $('#loading_import').removeClass('d-none');
    $('#preview_excel').addClass('d-none');
    $('#summary_import').addClass('d-none');
    $('#warning_import').addClass('d-none');

    let formData = new FormData();
    formData.append('file', file);
    formData.append('id_perusahaan', id_perusahaan);

    $.ajax({
      url: '<?= base_url("Barang/preview_import") ?>',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',

      success: function(res){

        $('#loading_import').addClass('d-none');

        if(!res.success){
          alert(res.error);
          return;
        }

        let excel_html = '';
        let db_html = '';

        let total=0, baru=0, update=0, sama=0;

        let items = res.data.items || [];

        items.forEach(function(item){

          total++;

          let badge='', rowClass='';

          if(item.is_new){
            baru++;
            badge='<span class="badge badge-primary">Baru</span>';
            rowClass='table-primary';
          } else if(item.changed){
            update++;
            badge='<span class="badge badge-warning">Update</span>';
            rowClass='table-warning';
          } else {
            sama++;
            badge='<span class="badge badge-secondary">Tidak Berubah</span>';
            rowClass='table-light';
          }

          // EXCEL
          excel_html += `
          <tr class="${rowClass}">
            <td>${badge}</td>
            <td>${item.kode}</td>
            <td>${item.excel.nama_perusahaan || '-'}</td>
            <td>${item.excel.nama || '-'}</td>
            <td>${item.excel.keterangan || '-'}</td>
            <td>${item.excel.size || '-'}</td>
            <td>${item.excel.satuan || '-'}</td>
            <td>${formatRupiah(item.excel.retail)}</td>
            <td>${formatRupiah(item.excel.grosir)}</td>
            <td>${formatRupiah(item.excel.grosir_10)}</td>
            <td>${formatRupiah(item.excel.het_jawa)}</td>
            <td>${formatRupiah(item.excel.indo_barat)}</td>
            <td>${formatRupiah(item.excel.special_price)}</td>
            <td>${formatRupiah(item.excel.barang_x)}</td>
          </tr>
          `;

          // DB
          db_html += `<tr class="${rowClass}">
            <td>${item.is_new ? 'Belum Ada' : 'Ada'}</td>
          `;

          if(item.is_new){

            db_html += `
              <td>${item.kode}</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
            `;

          } else {

            db_html += `
              <td>${item.db.kode}</td>
              <td>${item.db.perusahaan || '-'}</td>
              <td>${item.db.nama}</td>
              <td>${item.db.keterangan}</td>
              <td>${item.db.size}</td>
              <td>${item.db.satuan}</td>
              <td>${formatRupiah(item.db.retail)}</td>
              <td>${formatRupiah(item.db.grosir)}</td>
              <td>${formatRupiah(item.db.grosir_10)}</td>
              <td>${formatRupiah(item.db.het_jawa)}</td>
              <td>${formatRupiah(item.db.indo_barat)}</td>
              <td>${formatRupiah(item.db.special_price)}</td>
              <td>${formatRupiah(item.db.barang_x)}</td>
            `;
          }

          db_html += `</tr>`;

        });

        $('#preview_excel_body').html(excel_html);
        $('#preview_db_body').html(db_html);

        $('#summary_import').removeClass('d-none').html(`
          <b>${total}</b> data —
          <span class="text-primary">${baru} baru</span>,
          <span class="text-warning">${update} update</span>,
          <span class="text-muted">${sama} sama</span>
        `);

        $('#warning_import').removeClass('d-none');
        $('#preview_excel').removeClass('d-none');

        $('#btn_import').prop('disabled', (baru === 0 && update === 0));
      }
    });

  });
    $('#form_import').on('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this);

    $('#btn_import').prop('disabled', true).text('Importing...');

    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',

      success: function(res){

        $('#btn_import').prop('disabled', false).text('Import');

        if(!res.success){
          Swal.fire('Error', res.error, 'error');
          return;
        }

        Swal.fire({
          icon: 'success',
          title: 'Import Berhasil',
          html: `
            <b>${res.data.inserted}</b> data ditambahkan<br>
            <b>${res.data.updated}</b> data diupdate
          `
        }).then(() => {
          location.reload();
        });

      },

      error: function(){
        $('#btn_import').prop('disabled', false).text('Import');
        Swal.fire('Error', 'Server error', 'error');
      }
    });

  });

});
</script>
<script>
  // Aktifkan semua tooltip di halaman
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>