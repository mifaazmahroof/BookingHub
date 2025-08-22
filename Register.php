<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include 'futsal_db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = bin2hex(random_bytes(32));






    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST']; // gives localhost:8000 or yourdomain.com
$baseUrl = $protocol . "://" . $host;

$verifyUrl = $baseUrl . "/verify.php?token=" . urlencode($token);
    $fullname = htmlspecialchars($_POST['indoor_name']);
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
    $id = htmlspecialchars($_POST['clientId']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (!$email) {
        echo "Invalid email.";
        exit;
    }
$expiry = date("Y-m-d H:i:s", strtotime("+1 day"));

$sql = "INSERT INTO stadium_email_verification (client_id, token, expiry) 
        VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $id, $token, $expiry);
$stmt->execute();
    // PHPMailer setup
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mifas1993@gmail.com';          // Your Gmail
        $mail->Password   = 'rfqm ztbc jvwj hsde';           // Your app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender & recipient
        $mail->setFrom('yourmail@gmail.com', 'no-reply');
        $mail->addAddress($email, $fullname);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Account Created';
        $mail->Body    = "<p>Hello <strong>$fullname</strong>,<br>Your account was created successfully with Username: $username<br>Click the button below to verify your email:</p>
<a href='$verifyUrl' style='display:inline-block;
    padding:12px 24px;background:#4CAF50;color:#fff;text-decoration:none;border-radius:5px;'>
Verify Email</a>";

        $mail->send();
        echo "Registration successful. Confirmation email sent.";

    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>