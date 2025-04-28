<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();


if (!isset($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit;
}


include './db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerID = $_SESSION['customer_id'];

    
    $FirstName = trim($_POST['firstName']);
    $LastName = trim($_POST['lastName']);
    $Email = trim($_POST['email']);
    $Phone = trim($_POST['contactNo']);
    $Address1 = trim($_POST['address1']);
    $Address2 = trim($_POST['address2']);
    $city = trim($_POST['city']);
    $country = trim($_POST['country']);
    $postalCode = trim($_POST['postalCode']);

    try {
        
        $sqlCustomer = "UPDATE customer SET firstname = ?, lastname = ?, email = ?,contactNo = ? WHERE customer_id = ?";
        $stmt = $conn->prepare($sqlCustomer);
        $stmt->bind_param("ssssi", $FirstName, $LastName, $Email, $Phone, $customerID);
        $stmt->execute();
        $stmt->close();

        
        $sqlAddress = "UPDATE customer SET address1 = ?, address2 = ?, city = ?, country = ?, postalCode = ? WHERE customer_id = ?";
        $stmt = $conn->prepare($sqlAddress);
        $stmt->bind_param("sssssi", $Address1, $Address2, $city, $country, $postalCode, $customerID);
        $stmt->execute();
        $stmt->close();

        
        header("Location: MyDetails.php?updateSuccess=1");
        exit;
    } catch (Exception $e) {
        error_log("Error updating customer details: " . $e->getMessage());
        header("Location: MyDetails.php?updateError=1");
        exit;
    }
} else {
    
    header("Location: MyDetails.php");
    exit;
}
?>
