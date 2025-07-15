<?php
session_start();
include 'dbconnect.php'; // Use your dbconnect.php for connection

if (isset($_SESSION['roomId'])) {
    $roomId = intval($_SESSION['roomId']); // Prevent SQL injection
    $sql = "SELECT codes FROM code_bases WHERE room_id = $roomId";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error fetching code base: " . mysqli_error($conn));
    }
    if ($row = mysqli_fetch_assoc($result)) {
        echo $row['codes'];
    }
}

mysqli_close($conn);
?>
