<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "Safgems";

date_default_timezone_set('Asia/Kolkata');

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
