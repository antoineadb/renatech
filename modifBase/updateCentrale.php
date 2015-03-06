<?php

session_start();
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (empty($_POST['idcentralactuelnom'])) {
    header('location:/'.REPERTOIRE.'/update_nom_centraleErr1/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSELECT');
    exit;
} else {
    $idcentralactuelnom = $_POST['idcentralactuelnom'];
}

if (empty($_POST['modifcentralenom'])) {
    header('location:/'.REPERTOIRE.'/update_nom_centraleErr2/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSAISIE');
    exit;
} else {
    $modifcentralenom = stripslashes(Securite::bdd($_POST['modifcentralenom']));
    $boolmasquecentrale = $manager->getSingle2("select masquecentrale from centrale where idcentrale=? ", $idcentralactuelnom);
    if ($boolmasquecentrale == 1) {
        $boolmasquecentrale = 'TRUE';
    } else {
        $boolmasquecentrale = 'FALSE';
    }
    $centraleName = new CentraleName($idcentralactuelnom, $modifcentralenom, $boolmasquecentrale);
    $manager->updateNomCentrale($centraleName, $idcentralactuelnom);
    header('location:/'.REPERTOIRE.'/update_nom_centrale/' . $lang . '/TXT_MESSAGESERVEURUPDATECENTRALE');
    exit();
}
BD::deconnecter();
