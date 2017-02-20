<?php

session_start();
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (!empty($_POST['valeur'])) {    
    $valeur = $_POST['valeur'];
    $pseudo = $_SESSION['pseudo'];
    $loginParam = new LoginParam($pseudo, $valeur);
    $manager->updateLoginParam($loginParam);
    header('location:/' . REPERTOIRE . '/param/' . $lang.'/'.rand(0,10000));
    exit();
} else {
    header('location:/' . REPERTOIRE . '/param/' . $lang . '/erreur');
}
BD::deconnecter();
