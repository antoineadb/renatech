<?php

$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
if (empty($_POST['modifcentralenom'])) {
    header('location:/' . REPERTOIRE . '/insert_nom_centraleErr2/' . $lang . '/ok');
    exit;
} else {
    $modifCentrale = stripslashes(Securite::bdd($_POST['modifcentralenom']));
}
//CONTROLER QUE LA CENTRALE N'EST PAS DEJA DANS LA BASE DE DONNEE
$idCentrale = $manager->getSingle2("SELECT idcentrale FROM centrale Where libellecentrale = ?", $modifCentrale);
if (!empty($idCentrale)) {
    header('location:/' . REPERTOIRE . '/insert_nom_centraleErr1/' . $lang . '/TXT_MESSAGESERVEURCENTRALEEXISTE');
    exit;
} else {
    $idcentrale = $manager->getSingle("select max (idcentrale) from centrale") + 1;
    $centrale = new CentraleName($idcentrale, $modifCentrale, FALSE);
    $manager->addCentrale($centrale);
    $villeCentrale = new villeCentrale("TO DO", $idcentrale);
    $manager->updateVilleCentrale($villeCentrale, $idcentrale);
    header('location:/' . REPERTOIRE . '/insert_nom_centrale/' . $lang . '/TXT_MESSAGESERVEURCENTRALE');
}
BD::deconnecter();
