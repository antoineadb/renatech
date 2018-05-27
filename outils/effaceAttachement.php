<?php

session_start();
include_once 'toolBox.php';
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../decide-lang.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (!empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
}
$statut = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=? ", $idprojet);
if ($statut == ACCEPTE || $statut==ENCOURSREALISATION) {//PROJET ACCEPTE PHASE 2
    $Fichier = $manager->getSingle2("select attachementdesc from projet where idprojet = ?", $idprojet);
    $fichier = str_replace("''", "\'", $Fichier);
    //EFFACE LE NOM DANS LA TABLE
    $projetattachementdesc = new Projetattachementdesc($idprojet, '');
    $manager->updateProjetattachementdesc($projetattachementdesc, $idprojet);
    unlink('../uploaddesc/' . $fichier); //EFFACE LE FICHIER SUR LE SERVEUR
}

BD::deconnecter();
