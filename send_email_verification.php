<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Only proceed if the request is POST and email is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['username'])) {
    require __DIR__ . '/vendor/autoload.php';

    $mail = new PHPMailer(true);

    $conn = mysqli_connect("localhost", "root", "9932", "devcollab");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }   
    $sql="select count(*) from users where email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    if ($row[0] > 0) {
        echo json_encode([
            "error" => "Email already exists"
        ]);
        exit;
    }

    try {
        $verificationCode = random_int(100000, 999999);

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chatapp659@gmail.com';           // Gmail address
        $mail->Password   = 'mjwn gmtx eekj vkdv';             // Gmail App Password (16-char app password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('chatapp659@gmail.com', 'DevCollab');
        $mail->addAddress($_POST['email'], $_POST['username']);  // Recipient

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email - DevCollab';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; border-radius: 8px; max-width: 480px; margin: auto;'>
            <div style='background: #4f8cff; color: #fff; padding: 16px 0; border-radius: 6px 6px 0 0; text-align: center; font-size: 22px; font-weight: bold;'>
                DevCollab Email Verification
            </div>
            <div style='background: #fff; padding: 28px 24px 24px 24px; border-radius: 0 0 6px 6px;'>
                <p style='font-size: 17px; margin-bottom: 18px;'>Hi <strong>{$_POST['username']}</strong>,</p>
                <p style='font-size: 15px; color: #333; margin-bottom: 24px;'>
                Thank you for registering with <strong>DevCollab</strong>.<br>
                Please verify your email address to activate your account.
                </p>
                <div style='text-align: center; margin: 32px 0;'>
                <a href='http://localhost/Real-timeCollaboration/verify.php?vid={$verificationCode}&email={$_POST['email']}' 
                   style='background: #4f8cff; color: #fff; text-decoration: none; padding: 14px 36px; border-radius: 5px; font-size: 16px; font-weight: bold; display: inline-block;'>
                    Verify Email
                </a>
                </div>
                <p style='font-size: 13px; color: #888; margin-top: 30px;'>
                If you did not request this, you can safely ignore this email.
                </p>
                <p style='font-size: 14px; margin-top: 32px;'>Regards,<br>DevCollab Team</p>
            </div>
            </div>
        ";

        $mail->send();
        $conn=mysqli_connect("localhost", "root", "9932", "devcollab");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql="delete from verification where email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_POST['email']);
        $stmt->execute();
        if (!$stmt->execute()) {
            echo "❗ Error deleting previous verification code: " . $stmt->error;
        } else {
            $stmt->close();
            $sql="insert into verification (vid,email) values (?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $verificationCode, $_POST['email']);
            if (!$stmt->execute()) {
                echo "❗ Error inserting verification code: " . $stmt->error;
            }
            $stmt->close();
            echo json_encode([
                "verificationCode" => $verificationCode
            ]);
        }
    } 
    catch (Exception $e) {
        echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    }
} 
else {
    echo "Invalid request. Please submit the form with valid email and username.";
}
?>
