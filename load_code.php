<?php
session_start();

$mysqli = new mysqli("localhost", "root", "9932", "devcollab");
if ($mysqli->connect_error) {
    die("Connection failed: {$mysqli->connect_error}");
}

if (isset($_SESSION['roomId']))
    $sql = "select codes from code_bases where room_id = {$_SESSION['roomId']}";
$result = $mysqli->query($sql);
if (!$result) {
    die("Error fetching code base: {$mysqli->error}");
}
if ($row = $result->fetch_assoc()) {
    echo $row['codes'];
}

$mysqli->close();
?>