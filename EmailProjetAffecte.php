<?php

include_once 'decide-lang.php';
include 'class/email.php';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
     header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
//CENTRALE SOURCE RECUPERATION DU LIBELLE DE LA CENTRALE
$libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE  idlogin = idlogin_loginpassword AND idcentrale = idcentrale_centrale and pseudo=?", $_SESSION['pseudo']);
//TRAITEMENT DE L'EMAIL DE LA PERSONNE A QUI ON AFFECTE LE PROJET
if (!empty($_GET['idutilisateur'])) {
    $emailaffecte = $manager->getSingle2("SELECT mail FROM loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword and idutilisateur = ?", $_GET['idutilisateur']);
}
//TRAITEMENT DU PROJET
if (!empty($_SESSION['idprojet'])) {
    $idprojet=$_SESSION['idprojet'];
}elseif(!empty($_GET['idprojet'])){
    $idprojet=$_GET['idprojet'];
}
$infoprojet = $manager->getList2("select numero,titre from projet where idprojet=?", $idprojet);

$sujet = utf8_decode(TXT_AFFECTPROJET) . $infoprojet[0]['numero'];
$body = htmlentities(stripslashes(str_replace("''","'",affiche('TXT_PROJETNUM'))), ENT_QUOTES, 'UTF-8') . $infoprojet[0]['numero'] . TXT_DELACENTRALE . $libellecentrale .' '. htmlentities(stripslashes(str_replace("''","'",affiche('TXT_AFFECTPROJET1'))), ENT_QUOTES, 'UTF-8') . '<br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILPHASE231'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . '<a href="https://www.renatech.org/projet">' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');
sendEmail($body, $sujet, $emailaffecte);
