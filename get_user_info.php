<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    $conn = mysqli_connect('localhost', 'root', '9932', 'devcollab');
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
    $result = mysqli_query($conn, "SELECT username FROM users WHERE id = {$userId}");
    if ($result) {
        $userInfo = mysqli_fetch_assoc($result);
        echo json_encode($userInfo);
    } else {
        echo json_encode(['error' => 'Query failed']);
    }
    mysqli_close($conn);
} else {
    echo json_encode(['error' => 'Invalid or missing user_id']);
}
