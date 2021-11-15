<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class TestMailer extends \Phalcon\Mvc\Model
{

	public static function composeSignUpMail($recipient)
	{
		$mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'adj.testing.grounds@gmail.com';
            $mail->Password   = '***';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('adj.testing.grounds@gmail.com', 'Tutorial Mailer');
            $mail->addAddress($recipient);
            $mail->addReplyTo('adj.testing.grounds@gmail.com', 'Do Not Reply');

            $mail->isHTML(true);
            $mail->Subject = 'Tutorial Sign Up Success';
            $mail->Body    = 'Thank you for registering your account. Visit <a href="http://localhost:8000/user/login">http://localhost:8000/users/login</a> to login.';
            $mail->AltBody = 'Thank you for registering your account. Visit http://localhost:8000 to login.';

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
	}
    
    public static function composePasswordChangeMail($recipient)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'adj.testing.grounds@gmail.com';
            $mail->Password   = '***';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('adj.testing.grounds@gmail.com', 'Tutorial Mailer');
            $mail->addAddress($recipient);
            $mail->addReplyTo('adj.testing.grounds@gmail.com', 'Do Not Reply');

            $mail->isHTML(true);
            $mail->Subject = 'Tutorial Password Changed';
            $mail->Body    = 'Your password was successfully changed.';
            $mail->AltBody = 'Your password was successfully changed.';

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public static function composeCheckoutMail($recipient, $order)
	{
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'adj.testing.grounds@gmail.com';
            $mail->Password   = '***';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('adj.testing.grounds@gmail.com', 'Tutorial Mailer');
            $mail->addAddress($recipient);
            $mail->addReplyTo('adj.testing.grounds@gmail.com', 'Do Not Reply');

            $mail->isHTML(true);
            $mail->Subject = 'Purchase Confirmation';
            $mail->Body    = 'Thank you for your purchase. Summarized details of your order are as follows.<br><br>
                Order #: ' . $order->id . '<br>
                Total payment: $' . 
                number_format((float)($order->total_cost - $order->discount), 2, '.', '') . 
                ' BND<br><br>
                For more details, login to your account at <a href="http://localhost:8000/user/login">http://localhost:8000/users/login</a> and view your Order History under Manage Account.
                ';
            $mail->AltBody = 'Thank you for your purchase. For more details, login to your account and view your Order History under Manage Account.';
                $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
	}

}
