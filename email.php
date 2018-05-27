<?php

include_once 'Chiffrement.php';
define('HOST', Chiffrement::decrypt('YDzU5SLlxlKBCtClyVtIBnArw9Lf+Xrf85gZh8kUt+E=')); // SMTP server'
define('SMTPAUTH', true);
define('SMTPSECURE', 'ssl');
define('PORT', Chiffrement::decrypt('p9T+WHQqGP9FmcWWGqGyWw=='));
define('USERNAME', Chiffrement::decrypt('kvwqIXYTEkx5pEROS9i5Rw=='));
define('PASSWORD', Chiffrement::decrypt('rGGUOsDRwshC2hbkKYuowA=='));

function envoieEmail($body, $sujet, $email, $cc) {
   error_reporting(0);
    if (!is_file(include_once 'PHPMailer_5.2.1/class.phpmailer.php')==TRUE) {
        include_once '../PHPMailer_5.2.1/class.phpmailer.php';
    }else{
        include_once 'PHPMailer_5.2.1/class.phpmailer.php';
    }
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    setlocale(LC_CTYPE, "fr_FR.UTF-8");
    $mail->IsSMTP(true); // telling the class to use SMTP
    try {

        $mail->Host = HOST;
        $mail->SMTPAuth = SMTPAUTH;
        $mail->SMTPSecure = SMTPSECURE;
        $mail->Port = PORT;
        $mail->Username = USERNAME;
        $mail->Password = PASSWORD;

        if (!empty($email)) {
            for ($i = 0; $i < count($email); $i++) {
                $mail->AddAddress($email[$i]);
            }
        }
        if (!empty($cc)) {
            for ($i = 0; $i < count($cc); $i++) {
                $mail->AddCC($cc[$i]);
            }
        }
        $mail->SetFrom('projets@renatech.org', 'RENATECH');
        $mail->Subject = $sujet;
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
        $mail->Body = $body;
        $mail->Send();
        $mail->SmtpClose();
        /*echo $sujet.'<br>';echo $body.'<br>';echo'<pre>';print_r($email);echo'</pre>';exit();echo'<pre>';print_r($cc);echo'</pre>';exit();
        exit();*/
    } catch (phpmailerException $e) {
        echo "Message not Sent</p>\n";
        echo $e->errorMessage() . '<br>'; //Pretty error messages from PHPMailer
        echo $e->getCode() . '<br>';
        echo $e->getLine() . '<br>';
        echo $e->getTraceAsString() . '<br>';
    } catch (Exception $e) {
        echo $e->getMessage();
        echo "Message not Sent</p>\n";
    }
}

function sendEmail($body, $sujet, $email) { // envoi sans copie

    require_once('/var/www/rtb/html/projet/PHPMailer_5.2.1/class.phpmailer.php');
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    setlocale(LC_CTYPE, "fr_FR.UTF-8");
    $mail->IsSMTP(true); // telling the class to use SMTP
    try {
        $mail->Host = HOST;
        $mail->SMTPAuth = SMTPAUTH;
        $mail->SMTPSecure = SMTPSECURE;
        $mail->Port = PORT;
        $mail->Username = USERNAME;
        $mail->Password = PASSWORD;
        $mail->AddAddress($email);
        $mail->SetFrom('projets@renatech.org', 'RENATECH');
        $mail->Subject = $sujet;
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
        $mail->Body = $body;
        $mail->AddEmbeddedImage('../styles/img/logo-renatech.jpg', 'logo', 'logo-renatech.jpg'); //envoie d'une image ou pièce jointe
        $mail->Send();
        $mail->SmtpClose();
       // echo "Message Sent OK</p>\n";
    } catch (phpmailerException $e) {
        echo "Message not Sent</p>\n";
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo "Message not Sent</p>\n";
        echo $e->getMessage(); //Boring error messages from anything else!
        echo $e->getCode();
    }
}

?>