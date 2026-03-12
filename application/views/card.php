<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Cards with Pagination</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="height:100vh;overflow:scroll">
    <div class="container mt-4">
        <h2 class="fixed-top">Bootstrap Cards</h2>
        <div>
            <div class="row">
                <?php foreach ($cards as $card): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?= $card->kode_artikel; ?></h5>
                                <p class="card-text"><?= $card->nama_artikel; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="fixed-bottom">
                <?= $links; ?>
            </div>
        </div>
    </div>
</body>
</html>
