<?php

session_start();
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$bool=FALSE;var_dump($_GET);die;
if ($_GET['idutilisateur']!='-1') {
    $idutilisateur = $_GET['idutilisateur'];
    $bool= TRUE;
}else{
    $bool=false;
}
if($bool){
    $id = $manager->getSingle2("SELECT id FROM config_accueil_defaut WHERE idcentrale=?", $_GET['idcentrale']);
    $accueilCentrale =  new ConfigAccueilDefault($idutilisateur, $_GET['idcentrale']);
    $manager->updateConfigAccueil($accueilCentrale, $id);
}
BD::deconnecter();
