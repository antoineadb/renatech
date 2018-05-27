<?php

if(isset($_GET['lang'])){
    $lang=$_GET['lang'];
}else{
    include 'decide-lang.php';
}
include_once 'class/email.php';
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
if(isset($_SESSION['numprojet'])){
    $numprojet=$_SESSION['numprojet'];
}elseif(isset($_GET['idprojet'])){
    $numprojet=$manager->getSingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
}

//RECUPERATION DES ADRESSES MAIL ET NOM DES CENTRALES
$emailCentrale = '';
for ($i = 0; $i < count($_SESSION['emailCentrale']); $i++) {
    for ($j = 0; $j < 4; $j++) {
        if (!empty($_SESSION['emailCentrale'][$i]['email' . $j])) {
            $emailCentrale .=$_SESSION['libellecentrale'][$i] . ' - ' . $_SESSION['emailCentrale'][$i]['email' . $j] . '<br>';
        }
    }
}
$titre = stripslashes(str_replace("''", "'", utf8_decode((($_SESSION['titreProjet'])))));
$txtbodyref = utf8_decode((affiche('TXT_BOBYREF')));
$sujet = TXT_DEMANDEPROJET . ' : ' . $titre . ' ' . $txtbodyref . ' ' . $numprojet;
$body = affiche('TXT_BODYEMAILPROJET0') . '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILPROJET1'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILPROJET2'))), ENT_QUOTES, 'UTF-8') .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILPROJET3')))) .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_INFOQUESTION')))) .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_SINCERESALUTATION')))) .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_RESEAURENATECH')))) .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILPROJET4')))) . ' <br>' . $emailCentrale . '<br>' .
        "<a href=".ADRESSESITE." >" . TXT_RETOUR . '</a><br>    ' .
         '<br><br>' .utf8_decode(str_replace("!","", affiche('TXT_DONOTREPLY')));

//---------------------------TRAITEMENT DES EMAIL DES CENTRALES COPIES----------------------------------------------------------------------//
$arrayemailcc = array();
for ($i = 0; $i < count($_SESSION['emailCentrale']); $i++) {
    for ($j = 1; $j < 4; $j++) {
        if (!empty($_SESSION['emailcentrale'][$i][$j]) && $_SESSION['emailCentrale'][$i][$j] != $email) {
            array_push($arrayemailcc, $_SESSION['emailcentrale'][$i][$j]); //construction d'un tableau d'email hors responsable centrale
        }
    }
}
$arrayEmailCC = array_unique($arrayemailcc); //correspond aux copistes
//---------------------------TRAITEMENT DES DESTINATAIRES EMAIL DES CENTRALES RESPONSABLES CENTRALE-------------------------------------------//
$arrayemailResponsable = array(); //cas des email1 dans la table centrale
for ($i = 0; $i < count($_SESSION['emailCentrale']); $i++) {
    for ($j = 1; $j < 6; $j++) {
        if (!empty($_SESSION['emailCentrale'][$i]['email' . $j])) {
            array_push($arrayemailResponsable, $_SESSION['emailCentrale'][$i]['email' . $j]); //construction d'un tableau d'email des responsables des centrales
        }
    }
}
//Ajout des responsables des centrales ajouter aprés (cas d'un second responsable de centrale qui n'est pas dans la table centrale champ email1
// mais son idtypeutilisateur_typeutilisateur (4) correspond à un responsable de centrale
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} elseif (isset($_SESSION['mail'])) {
    $email = $_SESSION['mail'];
} else {
    $email = $manager->getSingle2("select mail from loginpassword where pseudo =?", $_SESSION['pseudo']);
}
$mailDestinataire = array();
array_push($mailDestinataire, $email); //ajout du demandeur dans $mailDestinataire
//Récupération des emails du responsables si il existe Récupération en 1er de l'idutilisateur
$idutilisateur = $manager->getSingle2("SELECT u.idutilisateur FROM  utilisateur u,loginpassword l WHERE l.idlogin = u.idlogin_loginpassword and l.pseudo =?", $_SESSION['pseudo']);
$emailResponsable = $manager->getSingle2("Select mailResponsable from utilisateur where idutilisateur=?", $idutilisateur);
if (!empty($emailResponsable)) {
    array_push($arrayEmailCC, $emailResponsable); // ajout dans le tableau le responsable si il existe
}
array_unique($arrayEmailCC); //Fusion et supression des doublons des responsables
foreach ($arrayEmailCC as $key => $value) { //Traitement des emails identiques des 2 tableaux
    if (in_array($value, $mailDestinataire)) {
        $arrayEmailCC[$key] = ''; //suppresssion des doublons
    }
}
$emailCC = array_filter($arrayEmailCC, function($var) { //enlève les enregistrement vide
    return (!($var == '' || is_null($var)));
});
$arraycc = array_merge($emailCC, $arrayemailResponsable); //Responsable centrale
$mailcc = array_unique($arraycc);
$emailcc = array_filter($mailcc, function($var) { //enlève les enregistrement vide
    return (!($var == '' || is_null($var)));
});
$maildestinataire = array_values($mailDestinataire); //Renumérotation des index
$mailCC = array_values($emailcc); //Renumérotation des index
if (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} elseif (isset($_SESSION['idprojet'])) {
    $idprojet = $_SESSION['idprojet'];
}

envoieEmail($body, $sujet, $maildestinataire, $mailCC); //envoie de l'email au responsable centrale et au copiste
header('Location:/'.REPERTOIRE.'/update_project/' . $lang . '/' . $idprojet);
