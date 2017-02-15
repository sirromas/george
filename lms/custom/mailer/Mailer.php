<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/mailer/vendor/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';

/**
 * Description of Mailer
 *
 * @author moyo
 */
class Mailer extends Utils {

    public $mail_smtp_host = 'mail.mycodebusters.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'lms@mycodebusters.com';
    public $mail_smtp_pwd = 'aK6SKymc';
    public $admin_email;

    function __construct() {
        parent::__construct();
        $this->get_admin_email();
    }

    function get_admin_email() {
        $query = "select * from uk_user where id=2";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $email = $row['email'];
        }
        $this->admin_email = $email;
    }

    function get_user_login_link($user) {
        $list = "";
        $list.="<div><form method='post' target='_blank' action='http://" . $_SERVER['SERVER_NAME'] . "/lms/login/index.php?authldap_skipntlmsso=1'>";
        $list.="<input type='hidden' name='username' value='$user->email'>";
        $list.="<input type='hidden' name='password' value='$user->pwd'>";
        $list.="Please click <input type='submit' value='here' style='background: none;border: none;color: #0066ff;decoration: underline;cursor:pointer;'> to login. Once logged in you can go to the 'My Profile' section and change your password if you wish. <br>";
        $list.="</form></div>";
        return $list;
    }

    function send_account_confirmation_message($user) {
        $mail = new PHPMailer();
        $link = $this->get_user_login_link($user);
        $addressA = $user->email;
        $addressB = $this->admin_email;
        $addressC = 'sirromas@gmail.com';

        $message = "";

        $message.="<html>";
        $message.="<body>";
        $message.="<p align='left'>Dear $user->firstname $user->lastname!</p>";
        $message.="<p align='left'>Your Green Practice Account was setup. Your credentials are below:<p>";

        $message.="<table align='left' style='font-weight:bold;'>";
        $message.="<tr>";
        $message.="<td>Account name:</td><td style='padding-left:15px;'>$user->pname</td>";
        $message.="</tr>";
        $message.="<tr>";
        $message.="<td>Login email address:</td><td style='padding-left:15px;'>$user->email</td>";
        $message.="</tr>";
        $message.="<tr>";
        $message.="<td>Password:</td><td style='padding-left:15px;'>$user->pwd</td>";
        $message.="</tr>";

        $message.="<tr style='font-weight:normal;'>";
        $message.="<td colspan='2'>$link<br></td>";
        $message.="</tr>";

        $message.="<tr style='font-weight:normal;'>";
        $message.="<td colspan='2'>Kind regards,<br></td>";
        $message.="</tr>";

        $message.="<tr style='font-weight:normal;'>";
        $message.="<td colspan='2'>Practice Index Support<br></td>";
        $message.="</tr>";

        $message.="<tr style='font-weight:normal;'>";

        $message.="<td colspan='2'>";

        $message.="<p align='left'><span style='font-weight:bold;'>Practice Index Ltd</span><br>
                    4th Floor<br>
                    86 - 90 Paul Street<br>
                    London<br>
                    EC2A 4NE<br>
                    <span style='font-weight:bold;'>Tel:</span>  020 7099 5510<br>
                    <span style='font-weight:bold;'>Fax: </span> 020 7099 5585<br>
                    <span style='font-weight:bold;'>Email:</span><a href='mailto:info@practiceindex.co.uk'> info@practiceindex.co.uk</a><br>
                    <span style='font-weight:bold;'>Website:</span><a href='www.practiceindex.co.uk' target='_blank'> www.practiceindex.co.uk</a></p>";

        $message.="<p align='left'><img src='http://practiceindex.co.uk/skin/frontend/default/practiceindex/images/logo.png'></p>";

        $message.="<p align='left'>DISCLAIMER: The information contained in this email is confidential and is intended "
                . "for the recipient only. If you have received it in error, please notify us immediately "
                . "by reply email and then delete it from your system. The views contained in this email "
                . "are those of the author and not necessarily those of Practice Index Ltd "
                . "(UK Company Registration Number 09018867)</p>";

        $message.="</td>";

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
        $mail->AddAddress($addressB);
        $mail->AddAddress($addressC);

        $mail->addReplyTo($this->mail_smtp_user, 'Practice Index');
        $mail->isHTML(true);
        $mail->Subject = 'Practice Index eLearning - Account Setup';
        $mail->Body = $message;
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function send_update_credentials_letter($user) {
        $mail = new PHPMailer();
        $link = $this->get_user_login_link($user);
        $addressA = $user->email;
        $addressB = $this->admin_email;
        $addressC = 'sirromas@gmail.com';

        $message = "";

        $message.="<html>";
        $message.="<body>";
        $message.="<p align='left'>Dear $user->firstname $user->lastname!</p>";
        $message.="<p align='left'>Your Green Practice Account was updated. Your credentials are below:<p>";

        $message.="<table align='left' style='font-weight:bold;'>";
        $message.="<tr>";
        $message.="<td>Account name:</td><td style='padding-left:15px;'>$user->pname</td>";
        $message.="</tr>";
        $message.="<tr>";
        $message.="<td>Login email address:</td><td style='padding-left:15px;'>$user->email</td>";
        $message.="</tr>";
        $message.="<tr>";
        $message.="<td>Password:</td><td style='padding-left:15px;'>$user->pwd</td>";
        $message.="</tr>";

        $message.="<tr style='font-weight:normal;'>";
        $message.="<td colspan='2'>$link<br></td>";
        $message.="</tr>";

        $message.="<tr style='font-weight:normal;'>";
        $message.="<td colspan='2'>Kind regards,<br></td>";
        $message.="</tr>";

        $message.="<tr style='font-weight:normal;'>";
        $message.="<td colspan='2'>Practice Index Support<br></td>";
        $message.="</tr>";

        $message.="<tr style='font-weight:normal;'>";

        $message.="<td colspan='2'>";

        $message.="<p align='left'><span style='font-weight:bold;'>Practice Index Ltd</span><br>
                    4th Floor<br>
                    86 - 90 Paul Street<br>
                    London<br>
                    EC2A 4NE<br>
                    <span style='font-weight:bold;'>Tel:</span>  020 7099 5510<br>
                    <span style='font-weight:bold;'>Fax: </span> 020 7099 5585<br>
                    <span style='font-weight:bold;'>Email:</span><a href='mailto:info@practiceindex.co.uk'> info@practiceindex.co.uk</a><br>
                    <span style='font-weight:bold;'>Website:</span><a href='www.practiceindex.co.uk' target='_blank'> www.practiceindex.co.uk</a></p>";

        $message.="<p align='left'><img src='http://practiceindex.co.uk/skin/frontend/default/practiceindex/images/logo.png'></p>";

        $message.="<p align='left'>DISCLAIMER: The information contained in this email is confidential and is intended "
                . "for the recipient only. If you have received it in error, please notify us immediately "
                . "by reply email and then delete it from your system. The views contained in this email "
                . "are those of the author and not necessarily those of Practice Index Ltd "
                . "(UK Company Registration Number 09018867)</p>";

        $message.="</td>";

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
        $mail->AddAddress($addressB);
        $mail->AddAddress($addressC);

        $mail->addReplyTo($this->mail_smtp_user, 'Practice Index');
        $mail->isHTML(true);
        $mail->Subject = 'Practice Index eLearning - Account Update';
        $mail->Body = $message;
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

}
