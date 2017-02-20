<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (empty($_POST['idcentraleactuelnom'])) {
    header('location:/' . REPERTOIRE . '/update_ville_centraleErr1/' . $lang . '/TXT_MESSAGEERREURVILLENONSELECT');
    exit;
} else {
    $idcentraleactuelnom = $_POST['idcentraleactuelnom'];
}
if (empty($_POST['modifcentraleville'])) {
    header('location:/' . REPERTOIRE . '/update_ville_centraleErr2/' . $lang . '/TXT_MESSAGEERREURVILLENONSAISIE');
    exit;
} else {
    $modifcentraleville = stripslashes(Securite::bdd($_POST['modifcentraleville']));
    $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $modifcentraleville);
    if (!empty($idcentrale)) {
        header('location:/' . REPERTOIRE . '/update_ville_centraleErr3/' . $lang . '/TXT_MESSAGESERVEURVILLEEXISTE');
        exit;
    } else {
        $villecentrale = new Villecentrale($modifcentraleville, $idcentraleactuelnom);
        $manager->updateVilleCentrale($villecentrale, $idcentraleactuelnom);
        header('location:/' . REPERTOIRE . '/update_ville_centrale/' . $lang . '/TXT_MESSAGEVILLEUPDATE');
        exit;
    }
}
BD::deconnecter();
