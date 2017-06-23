<?php

include_once 'Chiffrement.php';
if (is_file('../outils/constantes.php')) {
    include_once '../outils/constantes.php';
} elseif (is_file('outils/constantes.php')) {
    include_once 'outils/constantes.php';
}
if (is_file('../outils/toolBox.php')) {
    include_once '../outils/toolBox.php';
} elseif (is_file('outils/toolBox.php')) {
    include_once 'outils/toolBox.php';
}
if (!is_file('PHPMailer_5.2.4/class.phpmailer.php')) {
    include_once '../PHPMailer_5.2.4/class.phpmailer.php';
} else {
    include_once 'PHPMailer_5.2.4/class.phpmailer.php';
}


showError($_SERVER['PHP_SELF']);

define('HOST', Chiffrement::decrypt('YDzU5SLlxlKBCtClyVtIBnArw9Lf+Xrf85gZh8kUt+E=')); // SMTP server'
define('SMTPAUTH', true);
define('SMTPSECURE', 'ssl');
define('PORT', Chiffrement::decrypt('p9T+WHQqGP9FmcWWGqGyWw=='));
define('USERNAME', Chiffrement::decrypt('kvwqIXYTEkx5pEROS9i5Rw=='));
define('PASSWORD', Chiffrement::decrypt('rGGUOsDRwshC2hbkKYuowA=='));
/**
 * 
 * @param type $body  string
 * @param type $sujet string
 * @param type $email array
 * @param type $cc array
 */
function envoieEmail($body, $sujet, $email, $cc) {

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
        $mail->Body = wordwrap($body, 50); //Autorise les lignes > 998 caractères et évite l'affichage de ! aléatoire
        $mail->AddEmbeddedImage('../styles/img/logo-renatech.jpg', 'logo', 'logo-renatech.jpg'); //envoie d'une image ou pièce jointe        
      //  $mail->Send();
        $mail->SmtpClose();
    } catch (phpmailerException $e) {
        echo 'Message not Sent</p>';
        echo 'errorMessage: ' . $e->errorMessage() . '<br>'; //Pretty error messages from PHPMailer
        echo 'Code : ' . $e->getCode() . '<br>';
        echo 'Line: ' . $e->getLine() . '<br>';
        echo $e->getTraceAsString() . '<br>';
    } catch (Exception $e) {
        echo 'Message ' . $e->getMessage();
        echo "Message not Sent</p>";
    }
}
function sendEmail($body, $sujet, $email) { // envoi sans copie    
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
        $mail->Body = wordwrap($body, 50); //Autorise les lignes > 998 caractères et évite l'affichage de ! aléatoire
        $mail->AddEmbeddedImage('/' . REPERTOIRE . '/styles/img/logo-renatech.jpg', 'logo', 'logo-renatech.jpg'); //envoie d'une image ou pièce jointe
    //    $mail->Send(); 
        $mail->SmtpClose();
        return true;
    } catch (phpmailerException $e) {
        return false;
        echo "Message not Sent</p>";
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        return false;
        echo "Message not Sent</p>";
        echo $e->getMessage(); //Boring error messages from anything else!
        echo $e->getCode();
        $mail->ErrorInfo;
        }
        
        
}

function envoieEmailAttachement($body, $sujet, $email, $cc,$path,$filename) {

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
        $mail->Body = wordwrap($body, 50); //Autorise les lignes > 998 caractères et évite l'affichage de ! aléatoire
        $mail->AddAttachment($path,$filename);        
 //       $mail->Send();
        return true;
        $mail->SmtpClose();
    } catch (phpmailerException $e) {
        return false;
        echo 'Message not Sent</p>';
        echo 'errorMessage: ' . $e->errorMessage() . '<br>'; //Pretty error messages from PHPMailer
        echo 'Code : ' . $e->getCode() . '<br>';
        echo 'Line: ' . $e->getLine() . '<br>';
        echo $e->getTraceAsString() . '<br>';
    } catch (Exception $e) {
        return false;
        echo 'Message ' . $e->getMessage();
        echo "Message not Sent</p>";
    }
}