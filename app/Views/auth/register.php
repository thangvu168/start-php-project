<?php
$title = $title ?? 'Register';
$error = $error ?? '';
$old = $old ?? [];
?>
<h1><?= htmlspecialchars($title) ?></h1>

<p class="js-form-message" style="display:none;"></p>

<?php if ($error !== ''): ?>
  <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form id="registerForm" action="/register" method="post" novalidate>
  <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

  <div>
    <label for="first_name">First name</label>
    <input id="first_name" type="text" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>">
    <small class="js-field-error" data-field="first_name" style="color:red;"></small>
  </div>

  <div>
    <label for="last_name">Last name</label>
    <input id="last_name" type="text" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>">
    <small class="js-field-error" data-field="last_name" style="color:red;"></small>
  </div>

  <div>
    <label for="username">Username</label>
    <input id="username" type="text" name="username" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
    <small class="js-field-error" data-field="username" style="color:red;"></small>
  </div>

  <div>
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    <small class="js-field-error" data-field="email" style="color:red;"></small>
  </div>

  <div>
    <label for="password">Password</label>
    <input id="password" type="password" name="password">
    <small class="js-field-error" data-field="password" style="color:red;"></small>
  </div>

  <div>
    <label for="confirm_password">Confirm password</label>
    <input id="confirm_password" type="password" name="confirm_password">
    <small class="js-field-error" data-field="confirm_password" style="color:red;"></small>
  </div>

  <button type="submit">Register</button>
</form>

<p><a href="/login">Back to login</a></p>
