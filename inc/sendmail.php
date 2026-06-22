<?php
require_once('phpmailer/class.phpmailer.php');
// We will use the standard mail function to avoid SMTP connection issues
$mail = new PHPMailer();

$message = "";
$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    // These must match the 'name' attributes in your HTML form
    if( $_POST['user_name'] != '' AND $_POST['user_email'] != '' ) {

        $name     = $_POST['user_name'];
        $email    = $_POST['user_email'];
        $company  = $_POST['company'];
        $phone    = $_POST['user_phone'];
        $service  = $_POST['service_area'];
        $location = $_POST['locations'];
        $msg      = $_POST['message'];

        $toemail = 'info@syrex.uk'; // YOUR EMAIL
        $toname  = 'Syrex Group';   // YOUR NAME

        // Botcheck (HoneyPot) - Ensure your HTML has: <input type="hidden" name="form_botcheck" value="">
        $botcheck = $_POST['form_botcheck'];

        if( $botcheck == '' ) {

            $mail->SetFrom( $email , $name );
            $mail->AddReplyTo( $email , $name );
            $mail->AddAddress( $toemail , $toname );
            $mail->Subject = "New Proposal Request: " . $service;

            $body  = "<strong>New Website Enquiry</strong><br><br>";
            $body .= "<strong>Name:</strong> $name<br>";
            $body .= "<strong>Company:</strong> $company<br>";
            $body .= "<strong>Email:</strong> $email<br>";
            $body .= "<strong>Phone:</strong> $phone<br>";
            $body .= "<strong>Service Area:</strong> $service<br>";
            $body .= "<strong>Locations:</strong> $location<br><br>";
            $body .= "<strong>Message:</strong><br>$msg";

            $mail->MsgHTML( $body );
            
            // Using the server's local mailer
            $sendEmail = $mail->Send();

            if( $sendEmail == true ):
                $message = 'Your proposal request has been <strong>successfully</strong> sent. We will respond shortly.';
                $status = "true";
            else:
                $message = 'Email <strong>could not</strong> be sent. <br /><strong>Reason:</strong><br />' . $mail->ErrorInfo;
                $status = "false";
            endif;
        } else {
            $message = 'Bot detected.';
            $status = "false";
        }
    } else {
        $message = 'Please <strong>Fill up</strong> all required fields.';
        $status = "false";
    }
}

$status_array = array( 'message' => $message, 'status' => $status);
echo json_encode($status_array);
?>