<?php
include('../../../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'];
    $date = $_POST['date'];
    $shape = $_POST['shape'];
    $type = $_POST['type'];
    $weight = $_POST['weight'];
    $color = $_POST['color'];
    $requirement = $_POST['requirement'];

    $sql = "UPDATE request SET date=?, shape=?, type=?, weight=?, color=?, requirement=? WHERE request_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $date, $shape, $type, $weight, $color, $requirement, $request_id);


    if ($stmt->execute()) {
        echo "Record updated successfully.";
        header("Location: ./MyRequest.php?editSuccess=1"); 
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
        header("Location: ./MyRequest.php?editSuccess=2"); 

    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
