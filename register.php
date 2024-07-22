<?php
session_start();
include 'db.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = $_POST['address'];

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username atau email sudah ada. Silakan pilih username atau email yang berbeda.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, password, address, email) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssss", $fullname, $username, $password, $address, $email);

        if ($stmt->execute()) {
            $success_message = "User berhasil terdaftar. Silakan login.";
            // Redirect to login page after 3 seconds
            echo "<script>
                alert('User berhasil terdaftar. Silakan login.');
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 500);
            </script>";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="#" class="navbar-logo">GUDEG JOGJA IBU DIRJO</a>
        <div class="navbar-nav">
            <?php if (isset($_SESSION['fullname'])): ?>
                <span>Welcome, <?php echo $_SESSION['fullname']; ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="index.php">Home</a>
                <a href="tentang.php">Tentang</a>
                <a href="menu.php">Daftar Menu</a>
                <a href="kontak.php">Kontak</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="register-section">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <label for="fullname">Nama Lengkap:</label>
            <input type="text" id="fullname" name="fullname" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="address">Alamat:</label>
            <textarea id="address" name="address" required></textarea>
            <button type="submit" class="btn-register">Register</button>
        </form>
    </section>
    <!-- Add background image to the body -->
<body style="background-image: url('img/latar2.jpg'); background-size: cover;">
</body>
</html>