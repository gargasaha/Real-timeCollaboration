<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['vid']) && isset($_GET['email'])) {
    session_start();
    $vid = $_GET['vid'];
    $email = $_GET['email'];
    echo "Verification ID: " . htmlspecialchars($vid);

    require_once 'dbconnect.php'; // Use your dbconnect.php for connection

    $sql = "UPDATE verification SET isVerified=1 WHERE vid=? AND email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $vid, $email);
    if ($stmt->execute()) {
        echo "Email verified successfully.";
    } else {
        echo "Error verifying email: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
