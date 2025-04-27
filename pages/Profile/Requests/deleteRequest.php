<?php
if (isset($_GET['id'])) {
    $requestId = intval($_GET['id']);

    include '../../../database/db.php';

    $sql = "DELETE FROM request WHERE request_id = $requestId";

    if ($conn->query($sql) === TRUE) {
        header("Location: ./MyRequest.php?deleteSuccess=1");
        
    } else {
        echo "Error updating record: " . $conn->error;
        header("Location: ./MyRequest.php?deleteSuccess=2");

    }

    $conn->close();
} else {
    header("Location: ./requests.php");
}
?>
