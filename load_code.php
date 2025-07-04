<?php
session_start();

$mysqli = new mysqli("localhost", "root", "9932", "devcollab");
if ($mysqli->connect_error) {
    die("Connection failed: {$mysqli->connect_error}");
}

if (isset($_SESSION['roomId']))
    if ($row = $result->fetch_assoc()) {
        echo $row['codes'];
    }

$mysqli->close();
?>