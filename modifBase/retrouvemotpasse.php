<?php

session_start();
include_once '../decide-lang.php';
include_once '../class/email.php';
include_once '../class/Manager.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../outils/constantes.php';

$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (!empty($_POST['page_precedente']) && $_POST['page_precedente'] == 'motpasseoublie.php') {
//----------------------------------------------------------------------------------------------------------------------------------------------------
//					ON A SAISIE UN PSEUDO SANS EMAIL
//----------------------------------------------------------------------------------------------------------------------------------------------------
    if (!empty($_POST['pseudo']) && empty($_POST['mail'])) {
        $email = $manager->getSingle2("select mail from loginpassword where pseudo =?", $_POST['pseudo']);
        if (!empty($email)) {
            $passe = genPasse(3); //GENERATION D'UN NOUVEAU MOT DE PASSE
            $loginpassword = new LoginPassword(sha1($passe), $_POST['pseudo']); //MISE A JOUR DU MOT DE PASSE DANS LA BASE DE DONNEE
            $manager->updateloginpassword($loginpassword);
            $motpasseenvoye = new LoginMotpasseEnvoye('TRUE', $_POST['pseudo']); //MISE A JOUR DU BOOLEAN DANS LOGINPASSWORD QUI FORCERA LA MISE A JOUR DU MOT DE PASSE APRES CONNEXION
            $manager->updateMotpasseenvoye($motpasseenvoye);
            //ENVOI DE L'EMAIL AVEC LE MOT DE PASSE EN CLAIR
            $messagemotpasse = TXT_MOTPASSETITREMAIL;
            $body1 = htmlentities(TXT_MOTPASSEMAIL, ENT_QUOTES, 'UTF-8') . '<br>';
            $body2 = htmlentities(TXT_VOTREPSEUDO, ENT_QUOTES, 'UTF-8') . ' : ' . $_POST['pseudo'] . '<br>';
            $body3 = htmlentities(TXT_VOTREMOTPASSE, ENT_QUOTES, 'UTF-8') . ' : ' . $passe . '<br>';
            sendEmail($body1 . $body2 . $body3, $messagemotpasse, $email);            
            header('Location: /' . REPERTOIRE . '/message_password/' . $lang);
        } else {            
            header('Location: /' . REPERTOIRE . '/msg_nologinaccount/' . $lang);
        }
    } elseif (empty($_POST['pseudo']) && !empty($_POST['mail'])) {
        //RECUPERATION DES PSEUDOS LIE A CET EMAIL
        $arraypseudo = $manager->getList2("select pseudo from loginpassword where lower(mail)=?", strtolower($_POST['mail']));
        if (count($arraypseudo) == 0) {            
            header('Location: /' . REPERTOIRE . '/msg_noemailaccount/' . $lang);
        } elseif (count($arraypseudo) == 1) {
            $passe = genPasse(3); //GENERATION D'UN NOUVEAU MOT DE PASSE
            $loginpassword = new LoginPassword(sha1($passe), $arraypseudo[0]['pseudo']); //MISE A JOUR DU MOT DE PASSE DANS LA BASE DE DONNEE            
            $manager->updateloginpassword($loginpassword);
            $motpasseenvoye = new LoginMotpasseEnvoye('TRUE', $arraypseudo[0]['pseudo']); //MISE A JOUR DU BOOLEAN DANS LOGINPASSWORD QUI FORCERA LA MISE A JOUR DU MOT DE PASSE APRES CONNEXION
            $manager->updateMotpasseenvoye($motpasseenvoye);
            //ENVOI DE L'EMAIL AVEC LE MOT DE PASSE EN CLAIR
            $messagemotpasse = TXT_MOTPASSETITREMAIL;
            $pseudo= $manager->getSingle2("select pseudo from loginpassword where mail =?", $_POST['mail']);
            $body1 = htmlentities(TXT_MOTPASSEMAIL, ENT_QUOTES, 'UTF-8') . '<br>';
            $body2 = htmlentities(TXT_VOTREPSEUDO, ENT_QUOTES, 'UTF-8') . ' : ' . $pseudo . '<br>';
            $body3 = htmlentities(TXT_VOTREMOTPASSE, ENT_QUOTES, 'UTF-8') . ' : ' . $passe . '<br>';
            sendEmail($body1 . $body2 . $body3, $messagemotpasse, $_POST['mail']);
            header('Location: /' . REPERTOIRE . '/message_password/' . $lang);
        } elseif (count($arraypseudo) > 1) {
            $s_Pseudo = '';
            for ($i = 0; $i < count($arraypseudo); $i++) {
                $s_Pseudo .=$arraypseudo[$i]['pseudo'] . ' - ';
            }
            $s_pseudo = substr($s_Pseudo, 0, -2);
            $_SESSION['s_pseudo']=$s_pseudo;
            header('Location: /' . REPERTOIRE . '/pseudos/' . $lang);
        }
//----------------------------------------------------------------------------------------------------------------------------------------------------
//					ON A UN EMAIL ET UN PSEUDO
//----------------------------------------------------------------------------------------------------------------------------------------------------
    } elseif (!empty($_POST['pseudo']) && !empty($_POST['mail'])) {
        //VERIFICATION
        $mail = $manager->getsingle2("select mail from loginpassword where pseudo=?", $_POST['pseudo']);
        if (strtolower($_POST['mail']) != strtolower($mail)) {            
            header('Location: /' . REPERTOIRE . '/msg_nolink/' . $lang);
            exit();
        } else {
            $passe = genPasse(3); //GENERATION D'UN NOUVEAU MOT DE PASSE
            $loginpassword = new LoginPassword(sha1($passe), $_POST['pseudo']); //MISE A JOUR DU MOT DE PASSE DANS LA BASE DE DONNEE
            $manager->updateloginpassword($loginpassword);
            $motpasseenvoye = new LoginMotpasseEnvoye('TRUE', $_POST['pseudo']); //MISE A JOUR DU BOOLEAN DANS LOGINPASSWORD QUI FORCERA LA MISE A JOUR DU MOT DE PASSE APRES CONNEXION
            $manager->updateMotpasseenvoye($motpasseenvoye);
            //ENVOI DE L'EMAIL AVEC LE MOT DE PASSE EN CLAIR
            $messagemotpasse = TXT_MOTPASSETITREMAIL;
            $body1 = htmlentities(TXT_MOTPASSEMAIL, ENT_QUOTES, 'UTF-8') . '<br>';
            $body2 = htmlentities(TXT_VOTREPSEUDO, ENT_QUOTES, 'UTF-8') . ' : ' . $_POST['pseudo'] . '<br>';
            $body3 = htmlentities(TXT_VOTREMOTPASSE, ENT_QUOTES, 'UTF-8') . ' : ' . $passe . '<br>';
            sendEmail($body1 . $body2 . $body3, $messagemotpasse, $_POST['mail']);            
            header('Location: /' . REPERTOIRE . '/message_password/' . $lang);
        }
//----------------------------------------------------------------------------------------------------------------------------------------------------
//					ON A SAISIE UN PSEUDO ET UN EMAIL
//----------------------------------------------------------------------------------------------------------------------------------------------------
    } else {
        header('location:/'.REPERTOIRE.'/message_noselect/' . $lang );
//----------------------------------------------------------------------------------------------------------------------------------------------------
//					ON A RIEN SAISIE
//----------------------------------------------------------------------------------------------------------------------------------------------------
    }
} else {    
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}