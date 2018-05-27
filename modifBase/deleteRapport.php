<?php
session_start();
include '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if(isset($_GET['idrapport'])){
    $idrapport = $_GET['idrapport'];
}
if(isset($_GET['idstatut'])){
    $idstatut = $_GET['idstatut'];
}
if(isset($_GET['numProjet'])){
    $numProjet = $_GET['numProjet'];
}
$manager->delRapport($idrapport);
$_SESSION['messageDelete']='The report has been deleted';
header('Location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/' .  $idstatut.'/'.rand(0,10000).'/ok');
