<?php
$title = $title ?? 'Dashboard';
$userName = $userName ?? ''
?>

<h1><?= htmlspecialchars($title) ?></h1>
<p><?= htmlspecialchars($userName) ?></p>
<a href="/profile">Profile</a>
<form action="/logout" method="post" style="display:inline;">
    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
    <button type="submit">Logout</button>
</form>
