<body class="bg-success">
<?php
if ($this->session->flashdata('type')) { ?>
    <script>
        var type = "<?= $this->session->flashdata('type'); ?>"
        var title = "<?= $this->session->flashdata('title'); ?>"
        var text = "<?= $this->session->flashdata('text'); ?>"
        Swal.fire(title, text, type);
    </script>
<?php } ?>
<div class="container mt-3">

<!-- Outer Row -->
<div class="row justify-content-center">

    <div class="col-lg-6">
        <div class="card shadow">
            
            <div class="text-center card-header">
                <img class="w-25" src="<?= base_url('assets/icon/logo-pom.png') ?>">
                <h5>Purchase Order Mobile</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('auth_mobile/login') ?>" method="POST">
                    <div class="form-group">
                        <label><i class="fa fa-building"></i> PERUSAHAAN</label>
                        <select class="form-control" name="perusahaan" required>
                            <?php
                            foreach ($perusahaan as $p) { ?>
                                <option value="<?= $p->id ?>"><?= strtoupper($p->nama) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-user"></i> USERNAME</label>
                        <input type="username" autocomplete="false" name="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><i class="fa fa-lock"></i> PASSWORD</label>
                        <input type="password" autocomplete="false" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-block btn-success" value="LOGIN">
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>2023 - IT Internal Globalindo Group</small>
            </div>

        </div>

    </div>
</div>
</div>

