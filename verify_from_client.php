<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vid = $_POST['vid'] ?? '';
    $email = $_POST['email'] ?? '';
    $conn = mysqli_connect("localhost", "root", "9932", "devcollab");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $query = "SELECT isVerified FROM verification WHERE vid=? AND email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $vid, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['isVerified'] == 1) {
            echo "verified";
        }
    }
    $stmt->close();
    $conn->close();
}
?>