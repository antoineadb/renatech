<?php

session_start();
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_GET['page_precedente']) && $_GET['page_precedente'] == 'resultProjet.php') {
if (!empty($_POST['titreProjet'])) {
    $titreProjet = pg_escape_string($_POST['titreProjet']);
    $_SESSION['titreProjet'] = $titreProjet;
}
if (!empty($_POST['typeProjet'])) {
    $typeProjet = $_POST['typeProjet'];
    $_SESSION['typeProjet'] = $typeProjet;
}
if(isset($_GET['lang'])){
    $lang=$_GET['lang'];
}

$idprojet = $_GET['idprojet'];
$_SESSION['idprojet'] = $idprojet;
$concernephase1 = new ConcernePhase1($idprojet, ENCOURSANALYSE);
$manager->updateConcernePhase1($concernephase1, $idprojet);
$pseudo = $_SESSION['pseudo'];
responsablePorteur($pseudo,$idprojet);
//création de la variable de session pour bloquer l'éventuel double soumission du  formulaire
$_SESSION['soumission'] = 'soumis';
// envoie de l'email
include '../EmailProjet.php';
BD::deconnecter();
} else {
    include_once '../decide-lang.php';
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}