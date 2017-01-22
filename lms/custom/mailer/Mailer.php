<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/mailer/vendor/PHPMailerAutoload.php';

/**
 * Description of Mailer
 *
 * @author moyo
 */
class Mailer {

    public $mail_smtp_host = 'mail.mycodebusters.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'lms@mycodebusters.com';
    public $mail_smtp_pwd = 'aK6SKymc';

    function __construct() {
        
    }

    function send_gpadmin_account_confirmation($user) {
        $mail = new PHPMailer();

        $addressA = $user->email;
        $addressC = 'sirromas@gmail.com';

        $message = "";

        $message.="<html>";
        $message.="<body>";
        $message.="<p align='center'>Dear $user->firstname $user->lastname!</p>";
        $message.="<p align='center'>Your Green Practice Admin Account was created. Your credentials are below:<p>";
        $message.="<table align='center'>";
        $message.="<tr>";
        $message.="<td>Username:</td><td>$user->email</td>";
        $message.="</tr>";
        $message.="<tr>";
        $message.="<td>Password:</td><td>$user->pwd</td>";
        $message.="</tr>";
        $message.="<tr>";
        $message.="<td>Best regards, </td><td>Practice Index Team</td>";
        $message.="</tr>";
        $message.="</table>";
        $message.="</body>";
        $message.="</html>";

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Practice Index');

        $mail->AddAddress($addressA);
        $mail->AddAddress($addressC);

        $mail->addReplyTo($this->mail_smtp_user, 'Practice Index');
        $mail->isHTML(true);
        $mail->Subject = 'Practice Index - Account Confirmation';
        $mail->Body = $message;
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function send_student_account_confirmation($user) {
        
    }

}
