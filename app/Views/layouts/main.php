<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Application') ?></title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body>
    <div id="root">
        <?php include __DIR__ . '/partials/header.php'; ?>
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <div class="main__container">
            <?php include __DIR__ . '/partials/page_header.php'; ?>
            <div class="content">
                <?= $content ?>
            </div>
        </div>

        <?php include __DIR__ . '/partials/footer.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="/assets/js/sidebar.js"></script>
    <script src="/assets/js/profile.js"></script>
</body>

</html>