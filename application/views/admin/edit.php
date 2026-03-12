 <style>
     /* Gaya default */
     td {
         font-size: 11px;
         text-align: left;
     }

     tr {
         font-size: 13px;
     }

     .btn_tambah {
         margin-bottom: 10px;
     }
 </style>
 <style type="text/css" media="print">
     /* Tambahkan gaya cetak khusus di sini */
     .print-table {
         background-color: transparent;
     }

     .print-table th,
     .print-table td {
         border: 1px solid #dee2e6;
         padding: 0.3rem;
     }

     .print-table.table-striped tbody tr:nth-of-type(odd) {
         background-color: rgba(0, 0, 0, 0.05);
     }

     /* Gaya lainnya sesuai kebutuhan Anda */
 </style>
 <div class="card shadow">
     <form action="<?= base_url('Order/update_po') ?>" method="POST">
         <header>
             <h5 class="card-header  bg-warning">Update Data Order</h5>
         </header>
         <div class="card-body">
             <div class="row">
                 <div class="col-md-3">
                     <div class="form-group">
                         <label><b>Nama Customer : </b></label>
                         <textarea id="nama_customer" class="form-control form-control-sm" readonly><?= $order->nama_customer ?></textarea>
                     </div>
                 </div>
                 <div class="col-md-3">
                     <div class="form-group">
                         <label><b>Nama Sales : </b></label>
                         <input type="text" id="nama_sales" value="<?= $order->sales ?>" class="form-control form-control-sm" readonly>
                         <input type="hidden" name="no_order" value="<?= $order->id ?>" class="form-control form-control-sm" readonly>
                     </div>
                 </div>
                 <div class="col-md-3">
                     <div class="form-group">
                         <label><b>No. PO : </b></label>
                         <input type="text" name="no_po" id="no_po" class="form-control form-control-sm" value="<?= $order->referensi ?>">
                     </div>
                 </div>
                 <div class="col-md-3">
                     <div class="form-group">
                         <label><b>Tanggal PO : </b></label>
                         <input type="date" name="tanggal" value="<?= date('Y-m-d', strtotime($order->tanggal_dibuat)); ?>" class="form-control form-control-sm" required>
                     </div>
                 </div>
             </div>
             <table class="table responsive">
                 <thead>
                     <tr>
                         <th style="width:5%">No</th>
                         <th style="width:13%">Kode</th>
                         <th>Artikel</th>
                         <th class="text-center" style="width:8%">Size</th>
                         <th class="text-center" style="width:9%">QTY</th>
                         <th class="text-center" style="width:8%">Satuan</th>
                         <th class="text-right">Harga Satuan</th>
                         <th style="width:7%" class="text-center">Margin</th>
                         <th class="text-center">Total</th>
                         <th class="text-center">Menu</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php
                        $no = 0;
                        $subtotal = 0;
                        foreach ($detail as $d) :
                            $no++;
                        ?>
                         <tr>
                             <td><?= $no ?></td>
                             <td><?= $d->kode_artikel ?></td>
                             <td><?= $d->nama_artikel ?></td>
                             <td class="text-center">
                                 <?= $d->size ?>
                             </td>
                             <td class="text-center">
                                 <input type="number" name="qty_edit[]" min="0" value="<?= $d->qty ?>" class="form-control form-control-sm qty_edit" required>
                                 <input type="hidden" name="id_detail[]" min="0" value="<?= $d->id ?>" class="form-control form-control-sm " required>
                             </td>
                             <td class="text-center"><?= $d->satuan ?></td>
                             <td class="text-right harga">Rp <?= rupiah($d->harga) ?></td>
                             <td class="text-center diskonHarga"><?= $d->diskon_barang ?></td>
                             <td class="text-right total_harga">Rp <?= rupiah(hitung_diskon($d->harga, $d->diskon_barang) * $d->qty) ?></td>
                             <td class="text-center">
                                 <a href="#" class=" btn_delete" data-id="<?= $d->id ?>" title="Hapus Artikel">
                                     <i class="fas fa-trash text-danger"></i>
                                 </a>
                             </td>
                         </tr>
                     <?php
                            $diskonPercentage = $order->diskon; // Example: "10%"
                            $diskonValue = (float) str_replace('%', '', $diskonPercentage);
                            $subtotal += hitung_diskon($d->harga, $d->margin) * $d->qty;
                            $diskon_p = $subtotal * $diskonValue / 100;
                            $grandtotal = $subtotal - $diskon_p;
                        endforeach ?>
                     <tr>
                         <td colspan="7" class="text-right"><strong>SubTotal :</strong></td>
                         <td></td>
                         <td class="text-right subtotal"><strong> Rp <?= rupiah($subtotal) ?></strong></td>
                         <td></td>
                     </tr>
                     <tr>
                         <td colspan="7" class="text-right"><strong>Diskon Faktur :</strong></td>
                         <td></td>
                         <td class="text-right diskonFaktur">
                             <select class="form-control" name="diskon_faktur" id="diskonFaktur">
                                 <option value="0%">0%</option>
                                 <option value="5%">5%</option>
                                 <option value="15%">15%</option>
                                 <option value="20%">20%</option>
                             </select>
                             <script>
                                var diskonFaktur = "<?= $diskonPercentage ?>";
                                $("#diskonFaktur").val(diskonFaktur);
                             </script>
                         </td>
                         <td></td>
                     </tr>
                     <tr>
                         <td colspan="7" class="text-right"><strong>Grandtotal :</strong></td>
                         <td></td>
                         <td class="text-right grandtotal"><strong> Rp <?= rupiah($grandtotal) ?></strong></td>
                         <td></td>
                     </tr>
                     <tr></tr>
                     <tr>
                         <td colspan="10">
                             <b>Catatan</b> : <br> <?= nl2br(htmlspecialchars($order->catatan)) ?>
                         </td>
                     </tr>
                 </tbody>
             </table>
         </div>
         <div class="card-footer">
             <button type="submit" class="btn btn-primary btn-sm float-right mb-2"><i class="fa fa-save"></i> Simpan</button>
             <button type="button" data-order="<?= $order->id ?>" data-margin="<?= $order->margin ?>" class="btn btn-success btn-sm float-right mr-3 btn_add mb-2"><i class="fa fa-plus"></i> Tambah artikel</button>
             <a href="<?= base_url('Order/detail/' . $order->id) ?>" class="btn btn-danger btn-sm float-right mr-3 mb-2 "><i class="fa fa-times"></i> Cancel</a>
         </div>
     </form>
 </div>
 <div class="modal fade modal_artikel" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
     <div class="modal-dialog ">
         <form id="formExport" action="<?= base_url('Order/TambahArtikel') ?>" method="post">
             <div class="modal-content">
                 <div class="modal-header text-white bg-success">
                     <h5 class="modal-title" id="exampleModalLabel">Tambah Artikel</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
                 </div>
                 <div class="modal-body">
                     <div class="form-group">
                         <label for="">Pilih artikel :</label>
                         <select name="id_artikel" class="form-control form-control-sm list_artikel" required>
                             <option value="">- Pilih Artikel -</option>
                             <?php foreach ($artikel as $row) : ?>
                                 <option value="<?= $row->id ?>"><?= $row->kode_artikel ?></option>
                             <?php endforeach ?>
                         </select>
                     </div>
                     <div class="form-group mb-0">
                         <label for="">Nama Artikel</label>
                         <input type="text" id="nama_artikel" class="form-control form-control-sm" readonly>
                         <input type="hidden" name="id_order" id="id_order" class="form-control form-control-sm" readonly>
                     </div>
                     <div class="row">
                         <div class="col-md-4">
                             <div class="form-group mb-0">
                                 <label for="">Size</label>
                                 <input type="text" id="size" class="form-control form-control-sm" readonly>
                             </div>
                         </div>
                         <div class="col-md-4">
                             <div class="form-group mb-0">
                                 <label for="">Satuan</label>
                                 <input type="text" id="satuan" class="form-control form-control-sm" readonly>
                             </div>
                         </div>
                         <div class="col-md-4">
                             <div class="form-group mb-0">
                                 <label for="">Harga</label>
                                 <input type="text" name="harga" id="harga" class="form-control form-control-sm" readonly>
                             </div>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6">
                             <div class="form-group mb-0">
                                 <label for="">Qty</label>
                                 <input type="number" name="qty" id="qty" class="form-control form-control-sm" required>
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="form-group mb-0">
                                 <label for="">Margin</label>
                                 <input type="text" name="margin" id="margin" class="form-control form-control-sm" required>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> Close</button>
                     <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
                 </div>
             </div>
         </form>
     </div>
 </div>
 <script>
     $(document).ready(function() {
         $('.modal_artikel').on('shown.bs.modal', function() {
             $('.list_artikel').select2({
                 theme: 'bootstrap4'
             });
         });
         //  ketika artikel di pilih
         $(".list_artikel").on("change", function() {
             var id_artikel = $(this).val();
             var id_order = $('#id_order').val();
             $.ajax({
                 url: "<?php echo base_url('Order/getArtikel'); ?>",
                 method: "POST",
                 data: {
                     id_artikel: id_artikel,
                     id_order: id_order,
                 },
                 dataType: "json",
                 success: function(response) {
                     $('#size').val(response.size);
                     $('#nama_artikel').val(response.nama_artikel);
                     $('#satuan').val(response.satuan);
                     $('#harga').val(response.harga);
                 }
             });

         });
         //  qty edit update
         $('.qty_edit').on('input', function() {
             updateTotalHarga($(this));
             updateGrandtotal($(this));
         });

         $("#diskonFaktur").change(function() {
            updateGrandtotal($(this));
         });
     });
     // get Edit Product
     $('.btn_add').on('click', function() {
         const order = $(this).data('order');
         const margin = $(this).data('margin');
         $('#id_order').val(order);
         $('#margin').val(margin);
         $('.modal_artikel').modal('show');
     });
     var grandTotal = 0;

     // Define the updateTotalHarga function
     function updateTotalHarga(inputElement) {
         var row = inputElement.closest('tr');
         var hargaCell = row.find('.harga').text().replace('Rp ', ''); // Get the harga from the corresponding cell
         var totalDiskon = row.find('.total_diskon').text().replace('Rp ', ''); // Get the harga from the corresponding cell
         var grandtotal = row.find('.grandtotal').text().replace('Rp ', ''); // Get the harga from the corresponding cell
         var diskonCell = row.find('.diskonHarga').text();
         var totalHargaCell = row.find('.total_harga');
         var harga = parseFloat(hargaCell); // Convert harga to a number
         var diskonKedua = diskonCell;
         var qty_edit = parseInt(inputElement.val());
         var diskonAmount = diskonDetail(harga, diskonKedua);
         var totalHarga = diskonAmount * qty_edit;

         // Format the totalHarga with two decimal places
         totalHarga = parseFloat(totalHarga).toFixed(3);

         totalHargaCell.text(formatRupiah(totalHarga));

         // Update the grandTotal with the subtotal of this row
         grandTotal = 0; // Reset grandTotal
         $('.total_harga').each(function() {
             grandTotal += parseFloat($(this).text().replace('Rp ', '').replace(/\./g, '').replace(',', '.')); // Calculate grandTotal from all rows
         });

         // Display the grandTotal wherever you need it
         var formattedGrandTotal = formatRupiah(grandTotal);
         // Example: Assuming you have a div with class "subtotal"
         $(".subtotal").text(formattedGrandTotal);
     }

     function formatRupiah(angka) {
         return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
     }

     // fungsi helper
     function diskonDetail(harga_satuan, diskon) {
         var diskon_parts = diskon.split('+'); // Membagi diskon menjadi bagian-bagian
         var harga_diskon = harga_satuan;

         for (var i = 0; i < diskon_parts.length; i++) {
             var diskon_decimal = parseFloat(diskon_parts[i].replace('%', '')); // Menghilangkan tanda persen (%)
             harga_diskon -= harga_diskon * (diskon_decimal / 100);
         }

         // Membulatkan hasil diskon menjadi 2 angka di belakang koma
         var hasil_bulat = harga_diskon.toFixed(3);

         return parseFloat(hasil_bulat); // Mengembalikan nilai float setelah membulatkan
     }
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
                 location.href = "<?php echo base_url('Order/hapus_list/') ?>" + id;
             }
         })
     })
 </script>

 <script>
     function updateGrandtotal() {
         var subtotal = $(".subtotal").text().replace('Rp ', '');
         subtotal = subtotal.replaceAll(".", '');
         var diskon = $("#diskonFaktur").val();
         var grandtotal = Math.round(diskonDetail(parseInt(subtotal), diskon));
         $(".grandtotal").text(formatRupiah(grandtotal));
     };
 </script>