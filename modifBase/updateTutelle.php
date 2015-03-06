<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../decide-lang.php';
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (empty($_POST['idtutelleactuel'])) {
    header('location:/'.REPERTOIRE.'/update_tutelleErr1/' . $lang . '/TXT_MESSAGEERREURTUTELLESELECT');
    exit;
} else {
    $idtutelle = $_POST['idtutelleactuel'];
}
if (empty($_POST['modiftutelle'])) {
    header('location:/'.REPERTOIRE.'/update_tutelleErr2/' . $lang . '/TXT_MESSAGEERREURTUTELLENONSAISIE');
    exit;
}
if (empty($_POST['modiftutelleen'])) {
    header('location:/'.REPERTOIRE.'/update_tutelleErr2/' . $lang . '/TXT_MESSAGEERREURTUTELLENONSAISIE');
    exit;
} else {
    $modiftutelle = stripslashes(Securite::bdd($_POST['modiftutelle']));
    $modiftutelleen = stripslashes(Securite::bdd($_POST['modiftutelleen']));
    $boolmasquetutelle = $manager->getSingle2("select masquetutelle from tutelle where idtutelle=? ", $idtutelle);
    if ($boolmasquetutelle == 1) {
        $boolmasquetutelle = 'TRUE';
    } else {
        $boolmasquetutelle = 'FALSE';
    }
    if ($lang == 'fr') {
        $tutelle = new Tutelle($idtutelle, $modiftutelle, $boolmasquetutelle, $modiftutelleen);
    } elseif ($lang == 'en') {
        $tutelle = new Tutelle($idtutelle, $modiftutelleen, $boolmasquetutelle, $modiftutelle);
    }
    $manager->updateTutelle($tutelle, $idtutelle);
    header('location:/'.REPERTOIRE.'/update_tutelle/' . $lang . '/TXT_MESSAGETUTELLEUPDATE');
    exit;
}
BD::deconnecter();