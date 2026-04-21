<?php
$title = $title ?? 'Register';
$error = $error ?? '';
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

  <?php if ($error !== ''): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form action="/register" method="post" novalidate>
    <div>
      <label for="first_name">First name</label>
      <input id="first_name" type="text" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>">
    </div>

    <div>
      <label for="last_name">Last name</label>
      <input id="last_name" type="text" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>">
    </div>

    <div>
      <label for="username">Username</label>
      <input id="username" type="text" name="username" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
    </div>

    <div>
      <label for="email">Email</label>
      <input id="email" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    </div>

    <div>
      <label for="password">Password</label>
      <input id="password" type="password" name="password">
    </div>

    <div>
      <label for="confirm_password">Confirm password</label>
      <input id="confirm_password" type="password" name="confirm_password">
    </div>

    <button type="submit">Register</button>
  </form>

  <p><a href="/login">Back to login</a></p>
</body>

</html>
