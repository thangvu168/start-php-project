<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? '') ?></title>
    <link rel="stylesheet" href="/assets/css/global.css">
</head>

<body>
    <div class="auth__container">
        <?= $content ?>
    </div>
</body>

</html>