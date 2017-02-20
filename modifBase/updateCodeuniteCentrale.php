<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../class/Securite.php';

if (empty($_POST['idcentraleactuelcodeunite'])) {
    header('location:/' . REPERTOIRE . '/update_codeuniteErr1/' . $lang . '/TXT_MESSAGEERREURECODEUNITECENTRALESELECT');
    exit;
} else {
    if (isset($_POST['modifcentralecodeunite'])) {
        $modifcentralecodeunite = stripslashes(Securite::bdd($_POST['modifcentralecodeunite']));
    }
}
//SI TOUS LES EMAIL SONT VIDE ONT SORT
if (empty($modifcentralecodeunite)) {
    header('location:/' . REPERTOIRE . '/update_codeuniteErr2/' . $lang . '/TXT_MESSAGEERREURCODEUNITECENTRALENONSAISIE');
    exit;
} else {
    $codeunitecentrale = new Codeunitecentrale($modifcentralecodeunite);
    $manager->updateCodeuniteCentrale($codeunitecentrale, $_POST['idcentraleactuelcodeunite']);
    header('location:/' . REPERTOIRE . '/update_codeunite/' . $lang . '/TXT_MESSAGECODEUNITECENTRALEUPDATE');
}
BD::deconnecter();
