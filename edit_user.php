<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php'); // Redirect to login page if not admin
    exit();
}

include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id=$id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle form submission for updating user password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'];

    $sql = "UPDATE users SET password='$password' WHERE id=$id";
    $conn->query($sql);

    header('Location: user_management.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-section {
            margin: 0 auto;
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body style="background-image: url('img/latar2.jpg'); background-size: cover;">
    <nav class="navbar">
        <a href="admin_dashboard.php" class="navbar-logo">GUDEG JOGJA IBU DIRJO</a>
        <div class="navbar-nav">
            <span>Halo, <?php echo $_SESSION['fullname']; ?></span>
            <a href="admin_dashboard.php">Admin Toko</a> 
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <section class="admin-section">
        <h2>Edit User Password</h2>
        <form method="POST" action="edit_user.php?id=<?php echo $id; ?>">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" class="btn">Save</button>
        </form>
    </section>
</body>
</html>
<?php $conn->close(); ?>