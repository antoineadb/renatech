<?php

include_once 'decide-lang.php';
include 'class/email.php';
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (!empty($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (!empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} else {
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $_GET['numProjet']);
}
//TRAITEMENT DU LIBELLE DANS L'EMAIL
if (!empty($_SESSION['pseudo'])) {
    $infocentrale = $manager->getList2("SELECT email1,email2,email3,email4,email5,libellecentrale FROM centrale,loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword AND idcentrale_centrale = idcentrale AND pseudo=? ", $_SESSION['pseudo']);
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
if (!empty($_SESSION['numprojet'])) {
    $numprojet = $_SESSION['numprojet'];
} else {
    $numprojet = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
}
$titreProjet = $manager->getSingle("select titre from projet where numero='" . $numprojet . "'");
$titre = str_replace("''", "'", utf8_decode($titreProjet));
$txtbodyref = html_entity_decode((htmlentities(affiche('TXT_BOBYREF'), ENT_QUOTES, 'UTF-8')));
$sujet = TXT_DEMANDEPROJET . ' : ' . $titre . ' ' . $txtbodyref . ' ' . $numprojet;
$body = htmlentities(affiche('TXT_MAILCONTACTDEB6'), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(affiche('TXT_BODYEMAILREALISATION4'), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(affiche('TXT_BODYEMAILREALISATION1'), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(affiche('TXT_BODYEMAILREALISATION2'), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(affiche('TXT_BODYEMAILREALISATION3'), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(affiche('TXT_RESPONSABLEBODYTEMAIL84'), ENT_QUOTES, 'UTF-8') . '<br><br>' . 
        '<br><br>' . htmlentities(affiche('TXT_BODYEMAILREALISATION0'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . ' <br> ' . $emailCentrale . '<br>' .
        htmlentities(affiche('TXT_MAILCONTACTFIN5'), ENT_QUOTES, 'UTF-8') . "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br>' .
        htmlentities(affiche('TXT_NORESPONSE5'), ENT_QUOTES, 'UTF-8');
$infodemandeur = array($manager->getList2('SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur
            AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
$maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER
$emailcentrales = array();
$mailcentrales = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale,loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword
            AND idcentrale_centrale = idcentrale AND pseudo=? ", $_SESSION['pseudo']);
for ($i = 0; $i <= 5; $i++) {
    if (!empty($mailcentrales[0][$i])) {
        array_push($emailcentrales, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
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

