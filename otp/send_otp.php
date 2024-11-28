<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';  // Ensure you have PHPMailer installed via Composer

// Database connection (assuming you have a database connection setup like this)
include('../fun/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Action to send OTP
    if (isset($_POST['action']) && $_POST['action'] === 'sendOtp') {
        $email = $_POST['email'];
        
        // Generate a 6-digit OTP and store it in session
        $otp = mt_rand(100000, 999999);
        $_SESSION['otp'] = $otp;

        try {
            // Set up PHPMailer
            $mail = new PHPMailer(true);

            // Set SMTP configuration for Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'prabud0401@gmail.com';  // Your Gmail address
            $mail->Password = 'cqub tdac qdxh rgxr';     // App password for Gmail (use app password for security)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('prabud0401@gmail.com', 'GOAL7 Futsal Booking'); // Your email address and name
            $mail->addAddress($email);  // Recipient's email (from session)

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for account verification';
            $mail->Body    = "Your OTP code is: $otp";
            $mail->isHTML(true);
$mail->Subject = 'Your OTP for account verification';

$otp = $_SESSION['otp']; // Assuming OTP is already stored in session
$logoUrl = 'GOAL7'; // Add your logo URL here
$websiteUrl = 'GOAL7'; // Your website URL
$style = '
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        .header {
            text-align: center;
        }
        .header img {
            width: 150px;
        }
        .content {
            margin-top: 20px;
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            padding: 10px;
            background-color: #f1f9ff;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #999;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
';

$mail->Body = '
    <html>
    <head>' . $style . '</head>
    <body>
        <div class="container">
            <div class="header">
                <img src="' . $logoUrl . '" alt="Goal7 Futsal Booking" />
                <h2>Account Verification</h2>
            </div>
            <div class="content">
                <p>Hello,</p>
                <p>Thank you for registering with Goal7 Futsal Booking. To complete your account verification, please use the following OTP code:</p>
                <div class="otp">' . $otp . '</div>
                <p>This OTP is valid for the next 15 minutes. If you did not request this code, please ignore this message.</p>
                <p>If you encounter any issues, feel free to <a href="' . $websiteUrl . '/contact">contact us</a>.</p>
            </div>
            <div class="footer">
                <p>Best regards,<br />The Goal7 Futsal Booking Team</p>
                <p><a href="' . $websiteUrl . '" target="_blank">Visit Goal7 Futsal Booking Website</a></p>
            </div>
        </div>
    </body>
    </html>
';


            // Send email
            if ($mail->send()) {
                echo json_encode(['success' => true, 'message' => 'OTP sent successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to send OTP.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
        }
    }

    // Action to verify OTP
    if (isset($_POST['action']) && $_POST['action'] === 'verifyOtp') {
        // Retrieve username from session or post (you should be storing it somewhere when the OTP was sent)
        $username = $_POST['username']; // Assuming you pass the username to verify from frontend
        
        if (isset($_SESSION['otp']) && $_SESSION['otp'] == $_POST['otp']) {
            // OTP verified successfully
            // Update the 'verified' column in the users table
            $query = "UPDATE users SET verified = 1 WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'OTP verified and account is now verified.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update verification status.']);
            }
            $stmt->close();
        } else {
            // Invalid OTP
            echo json_encode(['success' => false, 'message' => 'Invalid OTP!']);
        }
    }
}
?>
