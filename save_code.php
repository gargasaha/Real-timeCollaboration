<?php
session_start();

$mysqli = new mysqli("localhost", "root", "9932", "devcollab");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


$sql="select codes from code_bases where room_id = {$_SESSION['roomId']}";
$result = $mysqli->query($sql);
if (!$result) {
    die("Error fetching code base: " . $mysqli->error);
}   
$row = $result->fetch_assoc();


$code =$row['codes']. $_POST['code'] ?? '';
$roomId = $_SESSION['roomId'] ?? null;

if ($roomId === null) {
    die("No room ID in session.");
}

$stmt = $mysqli->prepare("UPDATE code_bases SET codes = ? WHERE room_id = ?");
$stmt->bind_param("si", $code, $roomId);

if (!$stmt->execute()) {
    die("Error updating code base: " . $stmt->error);
}

echo "Code saved.";
$stmt->close();
$mysqli->close();
