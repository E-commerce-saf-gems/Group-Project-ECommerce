<?php
include './db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    if (strlen($new_password) < 6) { 
        echo "<script>alert('Password must be at least 6 characters!'); window.history.back();</script>";
        exit();
    }

    $query = "SELECT * FROM customer WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<script>alert('No customer found with this email!'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update = "UPDATE customer SET password = ? WHERE email = ?";
    $stmt2 = $conn->prepare($update);
    $stmt2->bind_param("ss", $hashed_password, $email);

    if ($stmt2->execute()) {
        echo "<script>alert('Password reset successful!'); window.location.href='./login.php';</script>";
    } else {
        echo "<script>alert('Failed to update password. Try again later!'); window.history.back();</script>";
    }
}
?>
