<?php
    require_once 'dbconnect.php'; // Assumes dbconnect.php is in the same directory

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fromId = $_POST["fromId"];
        $toId = $_POST["toId"];
        $roomId = $_POST["roomId"];
        $message = $_POST["message"];
        $timestamp = date("Y-m-d H:i:s");
        $sql = "INSERT INTO user_messages (fromId, message, roomId, tm, toId) VALUES ('$fromId', '$message', '$roomId', '$timestamp', '$toId')";
        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
            echo "Message saved successfully";
        } else {
            die("Error: " . mysqli_error($conn));
        }
    }
?>
