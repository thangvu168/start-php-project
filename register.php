<?php
session_start();

// Connect db
require_once('config.php');

$err_field_email = '';
$err_field_username = '';
$err_field_password = '';
$err_field_confirm_password = '';

$err_register = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($email)) {
        $err_field_email = 'Please enter email';
    }
    if (empty($username)) {
        $err_field_username = 'Please enter username';
    }
    if (empty($password)) {
        $err_field_password = 'Please enter password';
    }
    if (empty($confirm_password)) {
        $err_field_confirm_password = 'Please enter confirm password';
    }
    if ($password != $confirm_password) {
        $err_field_confirm_password = 'Confirm password is incorrect';
    }

    if (!empty($email) && !empty($password) && !empty($username) && $password == $confirm_password) {
        try {
            // 1. Check exist email
            $sql_check = "SELECT id FROM users WHERE email = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result->fetch_assoc()) {
                $err_register = "User already exists!";
                $stmt_check->close();
            } else {
                $stmt_check->close();

                $sql_insert = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt_insert->bind_param("sss", $email, $username, $hashed_password);

                if ($stmt_insert->execute()) {
                    $_SESSION['success'] = "Registration successful!";
                    header("Location: login.php");
                    exit;
                } else {
                    throw new Exception("Execution failed.");
                }
                $stmt_insert->close();
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $err_register = "Something went wrong. Please try again later.";
        } finally {
            $conn->close();
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
    <title>Register</title>
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
                        <label for="username">Username</label>
                        <input type="text" name="username">
                    </div>
                    <p class="error_message"><?= $err_field_username ?? '' ?></p>
                </div>

                <div class="field">
                    <div class="field_inner">
                        <label for="password">Password</label>
                        <input type="password" name="password">
                        <p class="error_message"><?= $err_field_password ?? '' ?></p>
                    </div>
                </div>

                <div class="field">
                    <div class="field_inner">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password">
                        <p class="error_message"><?= $err_field_confirm_password ?? '' ?></p>
                    </div>
                </div>

                <button type="submit">Register</button>
                <p class="error_message"><?= $err_register ?? '' ?></p>
            </form>
        </div>
        <div class="right_panel">IMAGE</div>
    </div>

</body>

</html>