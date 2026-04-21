<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="content">
        <?= $content ?>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>