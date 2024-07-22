<?php
session_start();
include 'db.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $sql = "SELECT * FROM users WHERE username=? AND email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql_update = "UPDATE users SET password=? WHERE username=? AND email=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sss", $new_password, $username, $email);
        if ($stmt_update->execute()) {
            $success_message = "Password berhasil direset! Silakan login.";
            echo "<script>
                alert('Password berhasil direset! Silakan login.');
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 500);
            </script>";
        } else {
            $error_message = "Terjadi kesalahan saat mereset password!";
        }
        $stmt_update->close();
    } else {
        $error_message = "Username atau email tidak ditemukan!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="#" class="navbar-logo">GUDEG JOGJA IBU DIRJO</a>
        <div class="navbar-nav">
            <a href="index.php">Home</a>
            <a href="tentang.php">Tentang</a>
            <a href="menu.php">Daftar Menu</a>
            <a href="kontak.php">Kontak</a>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        </div>
    </nav>

    <section class="login-section">
        <h2>Reset Password</h2>
        <form action="reset_password.php" method="POST">
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="new_password">Password Baru:</label>
            <input type="password" id="new_password" name="new_password" required>
            <button type="submit" class="btn-login">Reset Password</button>
        </form>
    </section>
    <!-- Add background image to the body -->
<body style="background-image: url('img/latar2.jpg'); background-size: cover;">
</body>
</html>