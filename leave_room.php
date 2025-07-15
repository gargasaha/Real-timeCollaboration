<?php
session_start();
require_once 'dbconnect.php'; // Use your dbconnect.php for connection

unset($_SESSION['roomId']); 

$sql = "DELETE FROM room_member WHERE user_id='{$_SESSION['id']}'";

if (mysqli_query($conn, $sql)) {
    // Success
} else {
    die('<div class="alert alert-danger">Query failed: ' . mysqli_error($conn) . '</div>');
}

mysqli_close($conn);

header("Location: dashboard.php");
exit();
?>