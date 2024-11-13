<?php

include('../../database/db.php'); // Make sure this file sets up $conn for database connection


$errors = [];

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Step 1: Collect and sanitize the input data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $first_name = mysqli_real_escape_string($conn, $_POST['firstName']); 
    $last_name = mysqli_real_escape_string($conn, $_POST['lastName']);
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    // Step 2: Validate email and password
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    }

    // Step 3: Continue collecting data for Step 2 and Step 3
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address1 = mysqli_real_escape_string($conn, $_POST['address1']);
    $address2 = mysqli_real_escape_string($conn, $_POST['address2']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $nic = mysqli_real_escape_string($conn, $_POST['nic']);

    // Photo handling
    if (!empty($_POST['photo'])) {
        $base64_string = $_POST['photo'];
        $data = explode(',', $base64_string);
        $image_data = base64_decode($data[1]);
        $target_dir = "../../uploads/";
        $photo_filename = "photo_" . time() . ".png";
        $photo_file = $target_dir . $photo_filename;
        file_put_contents($photo_file, $image_data);
    } else {
        $errors[] = "Photo capture was not successful.";
    }

    // Handle ID copy upload
    if (!empty($_FILES["id_copy"]["name"])) {
        $id_copy_file = $target_dir . basename($_FILES["id_copy"]["name"]);
        if (!move_uploaded_file($_FILES["id_copy"]["tmp_name"], $id_copy_file)) {
            $errors[] = "There was an error uploading your ID copy.";
        }
    } else {
        $errors[] = "ID copy is required.";
    }

    // Insert into database if there are no errors
    if (empty($errors)) {
        $sql = "INSERT INTO customer (title, firstName, lastName, email, password, gender, DOB, contactNo, address1, address2, city, country, postalCode, NIC, image, pdf)
                VALUES ('$title', '$first_name', '$last_name', '$email', '$hashed_password', '$gender', '$dob', '$phone', '$address1', '$address2', '$city', '$country', '$postal_code', '$nic', '$photo_file', '$id_copy_file')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }


    // Close the database connection
    mysqli_close($conn);
}
?>
