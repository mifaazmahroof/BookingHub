<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = htmlspecialchars($_POST['fname']);
	$lname = htmlspecialchars($_POST['lname']);
	$username = htmlspecialchars($_POST['phone']);
	$password = htmlspecialchars($_POST['password']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (!$email) {
        echo "Invalid email.";
        exit;
    }

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
        $mail->addAddress($email, $fname);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Account Created';
        $mail->Body    = "<p>Hello <strong>$fname $lname</strong>,<br>Your account was created successfully. Please wait for the approval.<br>Username: $username<br>Password: $password</p>";

        $mail->send();
        echo "Registration successful. Confirmation email sent.";

    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
