<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../class/Securite.php';

if (empty($_POST['idressourceactuel'])) {
    header('location:/' . REPERTOIRE . '/update_ressourceErr1/' . $lang . '/TXT_MESSAGEERREURRESSOURCESELECT');
    exit;
} else {
    $idressource = $_POST['idressourceactuel'];
}

if (empty($_POST['modifressource'])) {//MODIFICATION DE LA RESSOURCE
    header('location:/' . REPERTOIRE . '/update_ressourceErr2/' . $lang . '/TXT_MESSAGEERREURRESSOURCENONSAISIE');
    exit;
}
if (empty($_POST['modifressourceen'])) {//MODIFICATION DE LA RESSOURCE
    header('location:/' . REPERTOIRE . '/update_ressourceErr2/' . $lang . '/TXT_MESSAGEERREURRESSOURCENONSAISIE');
    exit;
} else {
    $modifressource = stripslashes(Securite::bdd($_POST['modifressource']));
    $modifressourceen = stripslashes(Securite::bdd($_POST['modifressourceen']));
}
$boolmasqueressource = $manager->getSingle2("select masqueressource from ressource where idressource=? ", $idressource);
if ($boolmasqueressource == 1) {
    $boolmasqueressource = 'TRUE';
} else {
    $boolmasqueressource = 'FALSE';
}
if ($lang == 'fr') {
    $ressource = new Ressource($idressource, $modifressource, $boolmasqueressource, $modifressourceen);
} elseif ($lang == 'en') {
    $ressource = new Ressource($idressource, $modifressourceen, $boolmasqueressource, $modifressource);
}

$manager->updateressource($ressource, $idressource);
header('location:/' . REPERTOIRE . '/update_ressource/' . $lang . '/TXT_MESSAGERESSOURCEUPDATE');
BD::deconnecter();
