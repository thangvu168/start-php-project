<?php
$title = $title ?? 'Login';
$error = $error ?? '';
$success = $success ?? '';
$old = $old ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title) ?></title>
</head>

<body>
  <h1><?= htmlspecialchars($title) ?></h1>

  <?php if ($success !== ''): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>

  <?php if ($error !== ''): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form action="/login" method="post" novalidate>
    <div>
      <label for="email">Email</label>
      <input id="email" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    </div>

    <div>
      <label for="password">Password</label>
      <input id="password" type="password" name="password">
    </div>

    <button type="submit">Login</button>
  </form>

  <p><a href="/register">Create account</a></p>
</body>

</html>
