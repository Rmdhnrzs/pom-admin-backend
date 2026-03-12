<body class="bg-primary">
<?php
if ($this->session->flashdata('type')) { ?>
    <script>
        var type = "<?= $this->session->flashdata('type'); ?>"
        var title = "<?= $this->session->flashdata('title'); ?>"
        var text = "<?= $this->session->flashdata('text'); ?>"
        Swal.fire(title, text, type)
    </script>
<?php } ?>
<div class="container mt-3">

<!-- Outer Row -->
<div class="row justify-content-center">

    <div class="col-lg-6">
        <?php if ($this->session->flashdata('gagal')) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Login Gagal !</strong><br><?= $this->session->flashdata('gagal') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php } ?>
        <div class="card shadow">
            
            <h3 class="text-center card-header">LOGIN</h3>
            <div class="card-body">
                <form action="<?= base_url('auth/login') ?>" method="POST">
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> USERNAME</label>
                        <input type="username" autocomplete="false" name="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-lock"></i> PASSWORD</label>
                        <input type="password" autocomplete="false" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-block btn-primary" value="LOGIN">
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>2023 - IT Internal Globalindo Group.</small>
            </div>

        </div>

    </div>
</div>
</div>