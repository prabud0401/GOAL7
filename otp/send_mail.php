<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start(); // Start the session to access session variables

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['receipt_content'])) {
    // Retrieve the receipt HTML from the AJAX call
    $receiptContent = $_POST['receipt_content'];

    // Ensure the recipient email is stored in the session
    if (isset($_SESSION['email'])) {
        $to = $_SESSION['email']; // Retrieve recipient email from session
    } else {
        // Fallback email in case the session does not have the email
        $to = 'prabud0401@gmail.com';
    }

    $subject = 'GOAL7 Online Futsal Booking System Receipt';

    // Set up the email body and headers
    $htmlContent = "
        <html>
        <head>
            <title>GOAL7 Futsal Booking Receipt</title>
        </head>
        <body>
            <p>Here is your booking confirmation receipt for the futsal booking:</p>
            $receiptContent
        </body>
        </html>
    ";

    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    try {
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
        $mail->addAddress($to);  // Recipient's email (from session)

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlContent;

        // Send email
        $mail->send();
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        // Log the error
        file_put_contents('mail_error_log.txt', date('Y-m-d H:i:s') . ' - ' . $mail->ErrorInfo . "\n", FILE_APPEND);
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
