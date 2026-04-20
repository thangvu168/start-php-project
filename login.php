<?php
session_start();

// Connect db
require_once('config.php');

$err_field_email = '';
$err_field_password = '';
$err_login = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $err_field_email = 'Please enter email';
    } else if (empty($password)) {
        $err_field_password = 'Please enter password';
    }

    if (!empty($email) && !empty($password)) {
        // Query db
        $sql = "SELECT id, email, username, password FROM users WHERE email = ?";
        $smtp = $conn->prepare($sql);
        /*
        i: interger
        d: double
        s: string
        b: blob
        */
        $smtp->bind_param("s", $_POST['email']);
        $smtp->execute();
        $result = $smtp->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = 'TRUE';
                header("Location: index.php");
            } else {
                $err_login = "Password incorrect!";
            }
            // Navigate using header
        } else {
            $err_login = "User not found";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="left_panel">
            <div class="">HEADER</div>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                <div class="field">
                    <div class="field_inner">
                        <label for="email">Email</label>
                        <input type="email" name="email">
                    </div>
                    <p class="error_message"><?= $err_field_email ?? '' ?></p>
                </div>

                <div class="field">
                    <div class="field_inner">
                        <label for="email">Password</label>
                        <input type="password" name="password">
                        <p class="error_message"><?= $err_field_password ?? '' ?></p>
                    </div>
                </div>

                <button type="submit">Login</button>
                <p class="error_message"><?= $err_login ?? '' ?></p>
            </form>
            <a href="./register.php">Register</a>
        </div>
        <div class="right_panel">IMAGE</div>
    </div>

</body>

</html>