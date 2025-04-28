<?php
session_start();
include('../../database/db.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_now_btn'])) {
    
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    
    $query = "SELECT * FROM customer WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['customer_id'] = $user['customer_id']; 
            $_SESSION['email'] = $user['email'];

            
            header('Content-Type: application/json');
            echo json_encode([
                'loggedIn' => true,
                'email' => $_SESSION['email'],
                'customer_id' => $_SESSION['customer_id'],
            ]);

            
            header("Location: ../homepage/homepage.php");
            exit();
        } else {
            
            header("Location: ../Login/login.php?fail=1");
            exit();
        }
    } else {
        
        header("Location: ../Login/login.php?fail=2");
        exit();
    }
} else {
    
    header("Location: ../Login/login.php");
    exit();
}
?>
