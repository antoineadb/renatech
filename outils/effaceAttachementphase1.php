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

    $Fichier = $manager->getSingle2("select attachement from projet where idprojet = ?", $idprojet);
    $fichier = str_replace("''", "\'", $Fichier);
    //EFFACE LE NOM DANS LA TABLE
    $projetattachement = new Projetattachement($idprojet, '');
    $manager->updateProjetattachement($projetattachement, $idprojet);
    unlink('../upload/' . $fichier); //EFFACE LE FICHIER SUR LE SERVEUR
BD::deconnecter();
