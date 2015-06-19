<?php

session_start();
include '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../outils/constantes.php';
include_once '../decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (isset($_GET['idprojet']) && !empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
    $idcentralelocal = $manager->getSingle2("select idcentrale_centrale from utilisateur,loginpassword where idlogin_loginpassword=idlogin and pseudo=?", $_SESSION['pseudo']);
    $numero = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
    //MISE A LA CORBEILLE
    $manager->restoreProject($idprojet);    
}
BD::deconnecter();
header('Location: /' . REPERTOIRE . '/delete_projet/' . $lang.'/'.$idprojet);

