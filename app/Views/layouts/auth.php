<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? '') ?></title>
    <meta name="_csrf_token" content="<?= htmlspecialchars($csrf_token ?? '') ?>">
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/layouts/auth.css">
</head>

<body>
    <div class="auth__container">
        <?= $content ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <?php if (!empty($recaptchaSiteKey)): ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <script src="/assets/js/components/modal.js"></script>
    <script src="/assets/js/components/form.js"></script>
    <script src="/assets/js/modules/auth.js"></script>
    <?php foreach ($scripts ?? [] as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>"></script>
    <?php endforeach; ?>
    <script src="/assets/js/app.js"></script>
</body>

</html>