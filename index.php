<?php
session_start();

// Check logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== 'TRUE') {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <h1>Profile Page</h1>
    <a href="/login.php">Login</a>
    <a href="/logout.php">Logout</a>
</body>

</html>