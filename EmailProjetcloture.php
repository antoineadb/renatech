<?php

include_once 'decide-lang.php';
include 'class/email.php';
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} else {
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $_GET['numProjet']);
}
//TRAITEMENT DU LIBELLE DANS L'EMAIL
if (isset($_SESSION['pseudo'])) {
    $infocentrale = $manager->getList2("SELECT email1,email2,email3,email4,email5,libellecentrale FROM centrale,loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword AND idcentrale_centrale = idcentrale AND "
            . "pseudo=? ", $_SESSION['pseudo']);
    if (!empty($infocentrale)) {
        $centrale = $infocentrale[0]['libellecentrale'];
        $emailCentrale = '';
        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 6; $j++) {
                if (!empty($infocentrale[$i]['email' . $j])) {
                    $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
                }
            }
        }
    } else {
        $infocentrale =$manager->getList2("SELECT email1,email2,email3,email4,email5,libellecentrale FROM centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?", $idprojet);
        if (!empty($infocentrale)) {
            $centrale = $infocentrale[0]['libellecentrale'];
            $emailCentrale = '';
            for ($i = 0; $i < 5; $i++) {
                for ($j = 0; $j < 6; $j++) {
                    if (!empty($infocentrale[$i]['email' . $j])) {
                        $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
                    }
                }
            }
        }
    }
} else {
    $centrale = "";
    $emailCentrale = "";
}
if (isset($_SESSION['numprojet'])) {
    $numprojet = $_SESSION['numprojet'];
} else {
    $numprojet = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
}

$titreProjet = $manager->getSingle("select titre from projet where numero='" . $numprojet . "'");
$titre = str_replace("''", "'", utf8_decode($titreProjet));
$txtbodyref = html_entity_decode((utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_BOBYREF'))))));
$sujet = html_entity_decode(TXT_MAJPROJET,ENT_QUOTES, 'UTF-8').' : '  . $titre . ' ' . $txtbodyref . ' ' . $numprojet;
$body = utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_MRSMR')))) . '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILCLOTURE')))) .'<br><br>'.
        utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_REMERCIEMENT')))) . '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_SINCERESALUTATION')))) . '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_RESEAURENATECH')))) .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_EMAILADDRESSCENTRAL'))))  .' '. utf8_decode($centrale) . ' <br> ' . $emailCentrale . '<br>' .
         "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br>' .
        utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_DONOTREPLY'))));
$infodemandeur = array($manager->getList2('SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur
            AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
$maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER
$emailcentrales = array();
$mailcentrales = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale,loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword
            AND idcentrale_centrale = idcentrale AND pseudo=? ", $_SESSION['pseudo']);
if (!empty($mailcentrales)) {
    for ($i = 0; $i <= 5; $i++) {
        if (!empty($mailcentrales[0][$i])) {
            array_push($emailcentrales, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
        }
    }
}else{
    $mailcentrales = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?", $idprojet);
    for ($i = 0; $i <= 5; $i++) {
        if (!empty($mailcentrales[0][$i])) {
            array_push($emailcentrales, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
        }
    }
}


//AJOUT DE L'EMAIL DU RESPONSABLE SI IL EXISTE
$arrayemailCC = array();
if (!empty($infodemandeur[0][0]['mailresponsable'])) {
    array_push($arrayemailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
    $CC = array_merge($arrayemailCC, $arrayemailCC);
} else {
    $CC = $arrayemailCC;
}
$emailcc = array_merge($emailcentrales, $CC);
$mailCC = array_unique($emailcc);
envoieEmail($body, $sujet, $maildemandeur, $mailCC);
