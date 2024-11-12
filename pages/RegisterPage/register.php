<?php
include '/database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve text data
    $title = $_POST['title'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $postalCode = $_POST['postalCode'];
    $nic = $_POST['nic'];

    // Handle file uploads
    if (isset($_FILES['idCopy'])) {
        $idCopyPath = 'uploads/' . basename($_FILES['idCopy']['name']);
        move_uploaded_file($_FILES['idCopy']['tmp_name'], $idCopyPath);
    }

    // Handle base64 image
    if (isset($_FILES['photo'])) {
        $photoPath = 'uploads/photo_' . uniqid() . '.png';
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    // Insert data into database
    $sql = "INSERT INTO customer (title, firstName, lastName, email, password, contactNo, NIC, status)
            VALUES ('$title', '$first_name', '$last_name', '$email', '$password', '$phone', '$nic' , '$status')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Registration successful"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }}

    $conn->close();
?>