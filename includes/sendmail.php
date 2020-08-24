<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();


//$mail->SMTPDebug = 3;                                 // Enable verbose debug output
// $mail->isSMTP();                                        // Set mailer to use SMTP
// $mail->Host = 'smtp.silvernet.nl';                    // Specify main and backup SMTP servers
// $mail->SMTPAuth = true;                                 // Enable SMTP authentication
// $mail->Username = 'dipak@silvernet.nl';      // SMTP username
// $mail->Password = 'tanaqui1';                           // SMTP password
// $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption, `ssl` also accepted
// $mail->Port = 587;                                      // TCP port to connect to

$message = "";
$status = "false";

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['form_name'] != '' AND $_POST['form_email'] != '' AND $_POST['form_subject'] != '' ) {

        $name = $_POST['form_name'];
        $email = $_POST['form_email'];
        $subject = $_POST['form_subject'];
        $phone = $_POST['form_phone'];
        $message = $_POST['form_message'];
        

        $subject = isset($subject) ? $subject : 'Contact';

        $botcheck = $_POST['form_botcheck'];

        $toemail = 'info@rijschoolroch.nl'; // Your Email Address
        $toname = 'Rijschool ROCH'; // Your Name

        if( $botcheck == '' ) {

            $mail->SetFrom( $email , $name );
            $mail->AddReplyTo( $email , $name );
            $mail->AddAddress( $toemail , $toname );
            $mail->Subject = "Interesse Rijles";

            $name = isset($name) ? "<strong>Naam:</strong><br> $name<br><br>" : '';
            $email = isset($email) ? "<strong>Email:</strong><br> $email<br><br>" : '';
            $phone = isset($phone) ? "<strong>Telefoonnummer:</strong><br> $phone<br><br>" : '';
            $subject = isset($subject) ? "<strong>Onderwerp:</strong><br> $subject<br><br>" : '';
            $message = isset($message) ? "<strong>Bericht:</strong><br> $message<br><br>" : '';

            $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>Deze mail is verstuurd vanaf de webpagina: ' . $_SERVER['HTTP_REFERER'] : '';

            $body = "$name $email $phone $subject $message $referrer";

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