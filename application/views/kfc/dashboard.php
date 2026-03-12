<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta http-equiv="refresh" content="10">
    <link href="<?= base_url('assets'); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets'); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">Alat A01</div>
                    <div class="card-body bg-primary text-white">
                        <div class="form-group">
                            <label>Temperatur Saat Ini</label>
                            <h1><i class="fas fa-temperature-low"></i> <?= $temperature1 ?></h1>
                        </div>
                    </div>
                </div>
                <div class="card bg-success">
                    <div class="card-body text-white">
                        <div class="form-group">
                            <label>Kelembapan Saat Ini</label>
                            <h1><i class="fas fa-tint"></i> <?= $humidity1 ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                <div class="card-header">Alat A02</div>
                    <div class="card-body bg-primary text-white">
                        <div class="form-group">
                            <label>Temperatur Saat Ini</label>
                            <h1><i class="fas fa-temperature-low"></i> <?= $temperature2 ?></h1>
                        </div>
                    </div>
                </div>
                <div class="card bg-success">
                    <div class="card-body text-white">
                        <div class="form-group">
                            <label>Kelembapan Saat Ini</label>
                            <h1><i class="fas fa-tint"></i> <?= $humidity2 ?></h1>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Alat</th>
                    <th>Waktu</th>
                    <th>Temperatur</th>
                    <th>Kelembapan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list_data as $row) { ?>
                <tr>
                    <td><?= $row->id_alat; ?></td>
                    <td><?= $row->created_date; ?></td>
                    <td><?= $row->temperature; ?></td>
                    <td><?= $row->humidity; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<script src="<?= base_url('assets'); ?>/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>