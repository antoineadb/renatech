<?php

session_start();
include '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../outils/constantes.php';
include '../decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (isset($_GET['idutilisateur']) && !empty($_GET['idutilisateur'])) {
    $idutilisateur = $_GET['idutilisateur'];
}
if (isset($_GET['idprojet']) && !empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
}
$manager->deleteUtilisateurAdmin($idprojet, $idutilisateur);
header('location:/'.REPERTOIRE.'/affecte_AdminProjet/' . $lang . '/' . $_GET['idutilisateur']);
BD::deconnecter();
