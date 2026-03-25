<body class="bg-primary">

<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
  <div class="row w-100 justify-content-center">

    <div class="col-md-5 col-lg-4">

      <div class="card shadow border-0" style="border-radius:10px;">

        <!-- Header -->
        <div class="card-body p-4">

          <div class="text-center mb-4">
            <h4 class="font-weight-bold mb-1">LOGIN</h4>
            <small class="text-muted">Masuk ke sistem</small>
          </div>

          <!-- Alert -->
          <?php if ($this->session->flashdata('gagal')) { ?>
            <div class="alert alert-danger small py-2">
              <?= $this->session->flashdata('gagal') ?>
            </div>
          <?php } ?>

          <!-- Form -->
          <form action="<?= base_url('auth/login') ?>" method="POST">

            <div class="form-group mb-3">
              <label class="small text-muted">Username</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-white border-right-0">
                    <i class="fa fa-user text-primary"></i>
                  </span>
                </div>
                <input type="text" name="username"
                  class="form-control border-left-0"
                  placeholder="Masukkan username"
                  required>
              </div>
            </div>

            <div class="form-group mb-3">
              <label class="small text-muted">Password</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-white border-right-0">
                    <i class="fa fa-lock text-primary"></i>
                  </span>
                </div>
                <input type="password" name="password"
                  class="form-control border-left-0"
                  placeholder="Masukkan password"
                  required>
              </div>
            </div>

            <button type="submit"
              class="btn btn-primary btn-block mt-3"
              style="border-radius:6px; font-weight:500;">
              LOGIN
            </button>

          </form>

        </div>

        <!-- Footer -->
        <div class="card-footer text-center bg-white border-0 pb-3">
          <small class="text-muted">2023 - IT Internal Globalindo Group</small>
        </div>

      </div>

    </div>

  </div>
</div>

</body>