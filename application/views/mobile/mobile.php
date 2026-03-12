<body style="height:90vh;">
    <div class="container mt-3">
        <?php if (empty($view)) {
            echo"<img class='d-block w-100' src='assets/img/hicoop.jpg'></img>";
        }else{
            $this->load->view($view);
        }
         ?>
    <!-- spinner -->
        <div id="loading">
            <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5> -->
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah anda yakin ingin keluar?</div>
                <div class="modal-footer">
                    <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-link" href="<?= base_url('auth_mobile/logout') ?>">Iya</a>
                </div>
            </div>
        </div>
    </div>

<script>
$(document).ready(function() {
  // Hide the loading screen when the page is fully loaded
  $(window).on('load', function() {
    $('#loading').fadeOut('slow', function() {
      $(this).hide();
      $('.isi').removeClass('d-none',true);
    });
  });
});
</script>
