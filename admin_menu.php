<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$notification = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = file_get_contents($_FILES['image']['tmp_name']);

        $sql = "INSERT INTO menu_items (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $description, $price, $image);
        if ($stmt->execute()) {
            $notification = 'Menu berhasil ditambahkan!';
        }
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        if (!empty($_FILES['image']['tmp_name'])) {
            $image = file_get_contents($_FILES['image']['tmp_name']);
            $sql = "UPDATE menu_items SET name=?, description=?, price=?, image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsi", $name, $description, $price, $image, $id);
        } else {
            $sql = "UPDATE menu_items SET name=?, description=?, price=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdi", $name, $description, $price, $id);
        }

        if ($stmt->execute()) {
            $notification = 'Menu berhasil diubah!';
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM menu_items WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $notification = 'Menu berhasil dihapus!';
        }
    }
}

$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Menu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-section {
            margin: 0 auto;
            max-width: 1000px;
            width: 100%;
            padding: 0 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
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
        .btn-edit{
            background-color: #3D6484;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-delete:hover {
            background-color: #e53935;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 95%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .notification {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: none;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.9);
        }
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }
        .modal-content, #caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }
        @keyframes zoom {
            from {transform:scale(0)}
            to {transform:scale(1)}
        }
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }
        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .modal-content img {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="admin_dashboard.php" class="navbar-logo">GUDEG JOGJA IBU DIRJO</a>
        <div class="navbar-nav">
            <span>Selamat datang, <?php echo $_SESSION['fullname']; ?></span>
            <a href="admin_dashboard.php">Admin Toko</a>
            <a href="user_management.php">Admin Web</a> 
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <section class="admin-section">
        <h2>Kelola Menu</h2>
        <?php if ($notification): ?>
            <div class="notification"><?php echo $notification; ?></div>
        <?php endif; ?>
        <form method="POST" action="admin_menu.php" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id">
            <label for="name">Nama:</label>
            <input type="text" name="name" id="name" required>
            <label for="description">Deskripsi:</label>
            <input type="text" name="description" id="description" required>
            <label for="price">Harga:</label>
            <input type="number" name="price" id="price" required>
            <label for="image">Gambar:</label>
            <input type="file" name="image" id="image" accept="image/*">
            <img id="current-image" src="" alt="Current Image" style="width: 100px; height: auto; display: none;">
            <button type="submit" name="add" class="btn">Tambah</button>
            <button type="submit" name="edit" class="btn btn-edit">Edit</button>
            <button type="submit" name="delete" class="btn btn-delete">Hapus</button>
        </form>
        <h3>Daftar Menu</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr id="menu-row-<?php echo $row['id']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" alt="<?php echo $row['name']; ?>" style="width: 100px; height: auto;" onclick="openModal(this)"></td>
                    <td>
                        <button class="btn btn-edit" onclick="editMenu(<?php echo $row['id']; ?>, '<?php echo $row['name']; ?>', '<?php echo $row['description']; ?>', <?php echo $row['price']; ?>, '<?php echo base64_encode($row['image']); ?>')">Edit</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="img01" style="width:100%">
            <div id="caption"></div>
        </div>
    </div>

    <script>
        function editMenu(id, name, description, price, image) {
            document.getElementById('id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('description').value = description;
            document.getElementById('price').value = price;
            document.getElementById('current-image').src = 'data:image/jpeg;base64,' + image;
            document.getElementById('current-image').style.display = 'block';
        }

        function openModal(img) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");
            modal.style.display = "block";
            modalImg.src = img.src;
            captionText.innerHTML = img.alt;
        }

        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.querySelector('.notification');
            if (notification) {
                notification.style.display = 'block';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>