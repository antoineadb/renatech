<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../class/Securite.php';

if (empty($_POST['idcentraleactuelemail'])) {
    header('location:/'.REPERTOIRE.'/update_mail_centraleErr1/' . $lang . '/TXT_MESSAGEERREUREMAILCENTRALESELECT');
    exit;
} else {
    if (isset($_POST['modifcentraleemail1'])) {
        $modifcentraleemail1 = $_POST['modifcentraleemail1'];
    }else{
        $modifcentraleemail1 ='';
    }
    if (isset($_POST['modifcentraleemail2'])) {
        $modifcentraleemail2 = $_POST['modifcentraleemail2'];
    }else{
        $modifcentraleemail2 = '';
    }
    if (isset($_POST['modifcentraleemail3'])) {
        $modifcentraleemail3 = $_POST['modifcentraleemail3'];
    }else{
        $modifcentraleemail3 ='';
    }
    
    if (isset($_POST['modifcentraleemail4'])) {
        $modifcentraleemail4 = $_POST['modifcentraleemail4'];
    }else{
        $modifcentraleemail4 ='';
    }
    if (isset($_POST['modifcentraleemail5'])) {
        $modifcentraleemail5 = $_POST['modifcentraleemail5'];
    }else {
        $modifcentraleemail5 = '';
    }
}
//SI TOUS LES EMAIL SONT VIDE ONT SORT
if(empty($modifcentraleemail1)&&empty($modifcentraleemail2)&&empty($modifcentraleemail3)&&empty($modifcentraleemail4)&&empty($modifcentraleemail5)){
    header('location:/'.REPERTOIRE.'/update_mail_centraleErr2/' . $lang . '/TXT_MESSAGEERREUREMAILCENTRALENONSAISIE');
    exit;
}else{
    $emailcentrale = new Emailcentrale($modifcentraleemail1,$modifcentraleemail2, $modifcentraleemail3, $modifcentraleemail4, $modifcentraleemail5);
    $manager->updateEmailCentrale($emailcentrale, $_POST['idcentraleactuelemail']);
    header('location:/'.REPERTOIRE.'/update_mail_centrale/' . $lang . '/TXT_MESSAGEEMAILCENTRALEUPDATE');
}
    BD::deconnecter();
