<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Application') ?></title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <meta name="_csrf_token" content="<?= htmlspecialchars($csrf_token ?? '') ?>">
</head>

<body>
    <div id="root">
        <?php include __DIR__ . '/partials/header.php'; ?>
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <div class="main__container <?= !empty($aside) ? 'has-aside' : '' ?>">
            <div class="main__inner" style="padding-inline: 10px;">
                <?php include __DIR__ . '/partials/page_header.php'; ?>
                <div class="content">
                    <?= $content ?>
                </div>
            </div>

            <?php if (!empty($aside)):
                $asideFile = __DIR__ . '/../../' . $aside . '.php';
                if (file_exists($asideFile)): ?>
                    <aside class="main__aside">
                        <?php include $asideFile; ?>
                    </aside>
            <?php endif;
            endif; ?>
        </div>

        <?php include __DIR__ . '/partials/footer.php'; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="/assets/js/components/modal.js"></script>
    <script src="/assets/js/components/menu.js"></script>
    <script src="/assets/js/components/form.js"></script>
    <script src="/assets/js/modules/auth.js"></script>
    <script src="/assets/js/components/sidebar.js"></script>

    <?php foreach ($scripts ?? [] as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>"></script>
    <?php endforeach; ?>
    <script src="/assets/js/app.js"></script>
</body>

</html>
