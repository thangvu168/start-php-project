<?php
$avatar = $user['avatar'] ?? '/assets/image/default-avatar.png';
?>

<h1><?= htmlspecialchars($title ?? 'Profile') ?></h1>

<p class="js-form-message" style="display:none;"></p>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form id="profileForm" action="/profile" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

    <p>Avatar:</p>
    <img src="<?= htmlspecialchars($avatar) ?>" id="avatarPreview" alt="Avatar" width="120" height="120" />
    <input type="file" name="avatar" accept="image/*" id="avatarInput">
    <small class="js-field-error" data-field="avatar" style="color:red;"></small>
    <p>Username: </p>
    <input type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled>
    <p>Email: </p>
    <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled>
    <p>First name: </p>
    <input id="first_name" type="text" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" name="first_name">
    <small class="js-field-error" data-field="first_name" style="color:red;"></small>
    <p>Last name: </p>
    <input id="last_name" type="text" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" name="last_name">
    <small class="js-field-error" data-field="last_name" style="color:red;"></small>

    <button type="submit">Submit</button>
</form>
