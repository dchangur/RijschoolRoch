<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();


// //$mail->SMTPDebug = 3;                               // Enable verbose debug output
// $mail->isSMTP();                                      // Set mailer to use SMTP
// $mail->Host = 'just55.justhost.com';  // Specify main and backup SMTP servers
// $mail->SMTPAuth = true;                               // Enable SMTP authentication
// $mail->Username = 'themeforest@ismail-hossain.me';                 // SMTP username
// $mail->Password = 'AsDf12**';                           // SMTP password
// $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
// $mail->Port = 465;                                    // TCP port to connect to

$message = "";
$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['form_email'] != '' AND $_POST['form_message'] != '' ) {

        $name = 'Quick Contact';
        $email = $_POST['form_email'];
        $message = $_POST['form_message'];

        $subject = isset($subject) ? $subject : 'Spoedcontact';

        $botcheck = $_POST['form_botcheck'];

        $toemail = 'info@electrovikesh.nl'; // Your Email Address
        $toname = 'Electro Vikesh'; // Your Name

        if( $botcheck == '' ) {

            $mail->SetFrom( $email , $name );
            $mail->AddReplyTo( $email , $name );
            $mail->AddAddress( $toemail , $toname );
            $mail->Subject = $subject;

            $email = isset($email) ? "Email: $email<br><br>" : '';
            $message = isset($message) ? "Bericht: $message<br><br>" : '';

            $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Deze mail is verstuurd vanaf de webpagina:  ' . $_SERVER['HTTP_REFERER'] : '';

            $body = "$email $message $referrer";

            $mail->MsgHTML( $body );
            $sendEmail = $mail->Send();

            if( $sendEmail == true ):
                $message = 'We hebben uw bericht in goede orde <strong>ontvangen</strong> en nemen zo snel mogelijk contact op met u.';
                $status = "true";
            else:
                $message = 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '';
                $status = "false";
            endif;
        } else {
            $message = 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
            $status = "false";
        }
    } else {
        $message = 'Please <strong>Fill up</strong> all the Fields and Try Again.';
        $status = "false";
    }
} else {
    $message = 'An <strong>unexpected error</strong> occured. Please Try Again later.';
    $status = "false";
}

$status_array = array( 'message' => $message, 'status' => $status);
echo json_encode($status_array);
?>