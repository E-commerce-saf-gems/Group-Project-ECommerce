<?php
include '../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        die("Passwords do not match.");
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE customer SET password = '$hashedPassword' WHERE email = '$email'";
    if ($conn->query($sql) === TRUE) {
        echo "Password successfully updated.";
    } else {
        echo "Error updating password: " . $conn->error;
    }

    $conn->close();
}
?>
