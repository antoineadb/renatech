<?php

session_start();
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
if (empty($_POST['idcentralactuelnom'])) {
    header('location:/'.REPERTOIRE.'/hide_nom_centraleErr1/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSELECT');
    exit;
} else {
    $idcentralactuelnom = $_POST['idcentralactuelnom'];
}
if (empty($_POST['modifcentralenom'])) {
    header('location:/'.REPERTOIRE.'/hide_nom_centraleErr2/' . $lang . $lang . '/TXT_MESSAGEERREURCENTRALENONSAISIE');
    exit;
} else {
    $modifcentralenom = stripslashes(Securite::bdd($_POST['modifcentralenom']));
    $idcentralactuelnom1 = $manager->getSingle2("SELECT  idcentrale FROM centrale where libellecentrale =?", $modifcentralenom);

    if (empty($idcentralactuelnom1)) {
        header('location:/'.REPERTOIRE.'/hide_nom_centraleErr2/' . $lang . $lang . '/TXT_MESSAGEERREURCENTRALENONSAISIE');
        exit;
    } else {
        if (isset($_POST['masquenomcentrale']) && $_POST['masquenomcentrale'] == TXT_MASQUER) {
            $centrale = new CentraleName($idcentralactuelnom, $modifcentralenom, TRUE);
            $manager->afficheHideCentrale($centrale, $idcentralactuelnom);
            header('location:/'.REPERTOIRE.'/hide_nom_centrale/' . $lang . '/TXT_MESSAGESERVEURCENTRALEMASQUER');
            exit;
        } elseif (isset($_POST['affichenomcentrale']) && $_POST['affichenomcentrale'] == TXT_REAFFICHER) {
            $centrale = new CentraleName($idcentralactuelnom, $modifcentralenom, FALSE);
            $manager->afficheHideCentrale($centrale, $idcentralactuelnom);
            header('location:/'.REPERTOIRE.'/show_nom_centrale/' . $lang . '/TXT_MESSAGESERVEURCENTRALEAFFICHE');
            exit;
        }
    }
}
BD::deconnecter();