<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'inc/PHPMailer/src/Exception.php';
require 'inc/PHPMailer/src/PHPMailer.php';
require 'inc/PHPMailer/src/SMTP.php';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the email from the form
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate the email
    if (empty($email)) {
        echo "Error: Email is required.";
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Invalid email format.";
        exit;
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.syrex.uk'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'info@syrex.uk'; // SMTP username
        $mail->Password = 'your-email-password'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
        $mail->Port = 587; // TCP port (587 for TLS, 465 for SSL)

        // Recipients
        $mail->setFrom('info@syrex.uk', 'Syrex Website');
        $mail->addAddress('info@syrex.uk'); // Recipient
        $mail->addReplyTo($email);

        // Content
        $mail->isHTML(false);
        $mail->Subject = 'Get Free Consultation';
        $mail->Body = "A new free Consultation request has been submitted:\n\nEmail: " . $email;

        // Send email
        $mail->send();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Sent</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background-color: #f4f4f4;
                }
                .message {
                    text-align: center;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .success {
                    color: #28a745;
                }
            </style>
        </head>
        <body>
            <div class="message">
                <h2 class="success">Email Successfully Sent!</h2>
                <p>You will be redirected in <span id="countdown">3</span> seconds...</p>
            </div>
            <script>
                let countdown = 3;
                const countdownElement = document.getElementById('countdown');
                const timer = setInterval(() => {
                    countdown--;
                    countdownElement.textContent = countdown;
                    if (countdown <= 0) {
                        clearInterval(timer);
                        window.location.href = '../why-choose-us.html'; // Adjust path
                    }
                }, 1000);
            </script>
        </body>
        </html>
        <?php
        exit;
    } catch (Exception $e) {
        echo "Error: Failed to send the email. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // If the form is not submitted, redirect back to the form page
    header("Location: ../index.html"); // Adjust the path to your form page
    exit;
}
?>