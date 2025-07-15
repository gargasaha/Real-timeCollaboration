<?php
session_start();
include 'dbconnect.php';

$sql = "SELECT codes FROM code_bases WHERE room_id = {$_SESSION['roomId']}";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error fetching code base: " . mysqli_error($conn));
}
$row = mysqli_fetch_assoc($result);

$code = $_POST['code'] ?? '';
$roomId = $_SESSION['roomId'] ?? null;

if ($roomId === null) {
    die("No room ID in session.");
}

$stmt = mysqli_prepare($conn, "UPDATE code_bases SET codes = ? WHERE room_id = ?");
mysqli_stmt_bind_param($stmt, "si", $code, $roomId);

if (!mysqli_stmt_execute($stmt)) {
    die("Error updating code base: " . mysqli_stmt_error($stmt));
}

echo "Code saved.";
mysqli_stmt_close($stmt);
mysqli_close($conn);
