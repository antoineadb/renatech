<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../decide-lang.php';
include_once '../outils/constantes.php';
if (isset($_SESSION['page_precedente']) && $_SESSION['page_precedente'] == 'createLogin.html') {
    include_once '../class/Manager.php';    
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

    if (isset($_SESSION['mail'])) {
        $mail = $_SESSION['mail'];
    }
    if (isset($_SESSION['mot_de_passe_1'])) {
        $passe = $_SESSION['mot_de_passe_1'];
    }
    if (isset($_SESSION['pseudo'])) {
        $pseudo = $_SESSION['pseudo'];
    }   
//INSERSION EN BASE DE DONNEE
    $idlogin = $manager->getSingle("select max(idlogin) from loginpassword") + 1;
    $tmpx = 30; //secondes valeur par défaut du tmps de connexion 
    $login = new Login($idlogin, $mail, $passe, $pseudo, $tmpx);
    $manager->addlogin($login);
    $_SESSION['mail'] = $mail;
    header('location:/'.REPERTOIRE.'/contact/' . $lang . '');
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}
BD::deconnecter();