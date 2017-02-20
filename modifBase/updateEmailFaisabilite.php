<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include_once '../outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$db = BD::connecter();
$manager = new Manager($db);
include_once '../class/Securite.php';
if(isset($_POST['emailfaisabilite'])&& !empty($_POST['emailfaisabilite'])){
    $emailfaisabilite = $_POST['emailfaisabilite'];
    $idemail = $manager->getSingle("select max(idemail) from emailrenatech");
    $o_emailfaisabilite=new EmailRenatech($idemail, $emailfaisabilite);
    $manager->updateEmailRenatech($o_emailfaisabilite, $idemail);
    header('location: /' . REPERTOIRE . '/Manage_label3/' . $lang . '/msgupdatesiteweb');
    header('location:/' . REPERTOIRE . '/param/' . $lang.'/e');
    exit();
} else {
    header('location:/' . REPERTOIRE . '/param/' . $lang . '/erreur');
}
BD::deconnecter();
