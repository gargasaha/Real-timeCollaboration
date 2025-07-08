<?php
    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['vid']) && isset($_GET['email'])) {
        session_start();
        $vid = $_GET['vid'];
        $email = $_GET['email'];
        echo "Verification ID: " . htmlspecialchars($vid);
        $conn = mysqli_connect("localhost", "root", "9932", "devcollab");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql="update verification set isVerified=1 where vid=? and email=?";
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