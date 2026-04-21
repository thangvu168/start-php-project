<?php
$title = $title ?? 'Dashboard';
$userName = $userName ?? ''
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
    <p><?= htmlspecialchars($userName) ?></p>
    <a href="/profile">Profile</a>
    <a href="/logout">Logout</a>
</body>

</html>