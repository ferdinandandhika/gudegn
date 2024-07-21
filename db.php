<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "cendrawasih.kencang.com";
$username = "gudegjog_admin";
$password = "2T2VDC_0_~}Q";
$dbname = "gudegjog_gudeg";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>