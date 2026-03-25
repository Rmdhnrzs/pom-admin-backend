<style>
.modal-content {
  border-radius: 10px;
  border: none;
}

.modal-header {
  border-bottom: none;
}

.modal-footer {
  border-top: none;
}
.modal-content {
  border-radius: 14px;
  border: none;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  overflow: hidden;
}

/* Header */
.modal-header {
  padding: 16px 20px;
  border-bottom: 1px solid #f1f1f1;
}

.modal-header.bg-success {
  background: linear-gradient(135deg, #28a745, #34ce57);
}

.modal-header.bg-warning {
  background: linear-gradient(135deg, #ffc107, #ffda6a);
}

.modal-header.bg-dark {
  background: linear-gradient(135deg, #343a40, #495057);
}

.modal-header h5 {
  font-weight: 600;
  letter-spacing: 0.3px;
}

.modal-header .close {
  opacity: 0.7;
  transition: 0.2s;
}

.modal-header .close:hover {
  opacity: 1;
  transform: scale(1.1);
}

/* Body */
.modal-body {
  padding: 20px;
  background: #fafafa;
  max-height: 70vh;
  overflow-y: auto;
}

/* Form */
.modal-body .form-group {
  margin-bottom: 14px;
}

.modal-body label {
  font-size: 12px;
  font-weight: 600;
  color: #555;
}

.modal-body .form-control,
.modal-body .form-control-sm,
.modal-body select {
  border-radius: 8px;
  border: 1px solid #e0e0e0;
  transition: all 0.2s ease;
  font-size: 12px;
}

.modal-body .form-control:focus,
.modal-body .form-control-sm:focus,
.modal-body select:focus {
  border-color: #2c7be5;
  box-shadow: 0 0 0 2px rgba(44,123,229,0.1);
}

.modal-body .row > div {
  margin-bottom: 8px;
}

/* Footer */
.modal-footer {
  padding: 14px 20px;
  border-top: 1px solid #f1f1f1;
  background: #fff;
}

/* Button */
.modal-footer .btn {
  border-radius: 8px;
  padding: 6px 16px;
  font-size: 12px;
  font-weight: 500;
  transition: 0.2s;
}

.modal-footer .btn-success {
  background: #28a745;
}

.modal-footer .btn-success:hover {
  background: #218838;
}

.modal-footer .btn-primary:hover {
  background: #0069d9;
}

.modal-footer .btn-danger:hover {
  background: #c82333;
}

input[readonly] {
  background-color: #f5f5f5 !important;
  cursor: not-allowed;
}

/* Scrollbar modal */
.modal-body::-webkit-scrollbar {
  width: 6px;
}
.modal-body::-webkit-scrollbar-thumb {
  background: #ccc;
  border-radius: 10px;
}
.modal-body::-webkit-scrollbar-thumb:hover {
  background: #999;
}

/* Animasi muncul */
.modal.fade .modal-dialog {
  transform: translateY(20px);
  transition: all 0.25s ease;
}

.modal.fade.show .modal-dialog {
  transform: translateY(0);
}
</style>
<!-- Modal Tambah -->
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
                <input type="number" name="kelipatan" class="form-control form-control-sm" value="1" min="1">
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

          <!-- Pilih Perusahaan -->
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
              <small class="text-muted">
                Belum punya template?
                <a href="<?= base_url('assets/templates/Template_Data_Barang.xlsx') ?>" download>
                  <i class="fas fa-download"></i> Download Template
                </a>
              </small>
            </div>
          </div>

          <!-- Loading -->
          <div id="loading_import" class="text-center mt-4 d-none">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Menganalisa file Excel...</p>
          </div>

          <!-- Summary -->
          <div id="summary_import" class="mt-3 d-none">
            <div class="row text-center">
              <div class="col">
                <div class="card border-0 bg-light rounded">
                  <div class="card-body py-2 px-1">
                    <div class="text-muted mb-1" style="font-size:10px;text-transform:uppercase;letter-spacing:.5px">Total Item di Excel</div>
                    <div id="sum_total" class="font-weight-bold" style="font-size:22px;line-height:1">0</div>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card border-0 rounded" style="background:#f0f0f0">
                  <div class="card-body py-2 px-1">
                    <div class="text-muted mb-1" style="font-size:10px;text-transform:uppercase;letter-spacing:.5px">Tidak Ada Perubahan</div>
                    <div id="sum_nochange" class="font-weight-bold text-secondary" style="font-size:22px;line-height:1">0</div>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card border-0 rounded" style="background:#fff8e1">
                  <div class="card-body py-2 px-1">
                    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:#b8860b" class="mb-1">Akan Diupdate</div>
                    <div id="sum_update" class="font-weight-bold text-warning" style="font-size:22px;line-height:1">0</div>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card border-0 rounded" style="background:#fff0f0">
                  <div class="card-body py-2 px-1">
                    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:#c0392b" class="mb-1">Akan Ditambahkan</div>
                    <div id="sum_insert" class="font-weight-bold text-danger" style="font-size:22px;line-height:1">0</div>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card border-0 rounded" style="background:#fdf0ff">
                  <div class="card-body py-2 px-1">
                    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:#8e44ad" class="mb-1">Data Error</div>
                    <div id="sum_error" class="font-weight-bold" style="font-size:22px;line-height:1;color:#8e44ad">0</div>
                  </div>
                </div>
              </div>
            </div>
      </div> 
          <!-- Preview -->
          <div id="preview_excel" class="mt-3 d-none">
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
                <tbody id="preview_body"></tbody>
              </table>
            </div>
          </div>

          <!-- Keterangan -->
          <div id="warning_import" class="mt-2 d-none">
            <div class="alert alert-light border mb-0 py-2" style="font-size:12px">
              <div class="mb-1">
                <span class="badge badge-danger mr-1">Merah = Insert / Baru</span>
                Kode barang ini <strong>belum ada di database</strong>. Sistem akan <strong>menambahkan</strong> data ini sebagai barang baru.
              </div>
              <div>
                <span class="badge badge-warning mr-1">Kuning = Update</span>
                Kode barang ini <strong>sudah ada di database</strong>, namun ada <strong>perbedaan data</strong> antara Excel dan database. Sistem akan <strong>memperbarui</strong> data yang berbeda.
              </div>
              <div>
                <span class="badge badge-dark mr-1">Error</span>
                Data bermasalah, <strong>perbaiki data yang ada pada template.</strong>
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

<!-- Modal Update-->
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
              <input type="number" name="kelipatan" id="kelipatan" class="form-control form-control-sm">
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