<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['customer_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'email' => $_SESSION['email'],
        'customer_id' => $_SESSION['customer_id'],
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>
