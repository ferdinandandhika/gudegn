<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>

        .about-us {
            background-color: #eabe6e; 
            padding: 20px; 
        }

        .about-container {
            font-family: 'Playfair Display', serif; 
            color: #6f5001; 
        }
    </style>
</head>
<body>
    <nav class="navbar" >
        <a href="#" class="navbar-logo">GUDEG JOGJA IBU DIRJO</a>

        <div class="navbar-nav">
            <?php if (isset($_SESSION['fullname'])): ?>
                <span>Welcome, <?php echo $_SESSION['fullname']; ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="index.php">Home</a>
                <a href="tentang.php">About</a>
                <a href="menu.php">Menu</a>
                <a href="kontak.php">Contact</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>

        <div class="navbar-extra">
            <a href="#" id="shopping-cart-button"><i data-feather="shopping-cart"></i><span id="cart-count">0</span></a>
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>

    <section class="about-us">
        <div class="about-container">
            <h1>Tentang Kami</h1>
            <p>Selamat datang di GUDEG JOGJA IBU DIRJO! Toko Gudeg Jogja Ibu Dirjo berdiri kokoh sejak tahun 1974, meneruskan tradisi lezat keluarga dalam menghadirkan gudeg yang otentik dan kaya rasa. Kami menyajikan gudeg yang dimasak dengan bahan-bahan segar pilihan dan bumbu rahasia yang turun temurun, menghasilkan cita rasa gudeg yang khas dan tak terlupakan.</p>
            <p>Toko Gudeg Jogja Ibu Dirjo buka setiap hari dari pukul 10.00 pagi hingga 16.30 sore. Kami juga menyediakan layanan pesan antar untuk memudahkan pelanggan yang ingin menikmati gudeg kami di rumah.Datang dan kunjungi Toko Gudeg Jogja Ibu Dirjo, rasakan kelezatan gudeg otentik Jogja yang kaya rasa dan penuh tradisi. Kami tunggu kedatangan Anda!</p>
            <img src="img/tentangg.jpeg" alt="Tentang Kami">
    </section>

    <script src="script.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>