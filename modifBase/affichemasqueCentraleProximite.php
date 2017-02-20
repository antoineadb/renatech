<?php

session_start();
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'formulaireListe2.php') {
    if (empty($_POST['idlibellecentraleProximiteactuel'])) {
        header('location:/' . REPERTOIRE . '/update_nom_centraleErr1/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSELECT');
        exit;
    } else {
        $idCentraleProximite = $_POST['idlibellecentraleProximiteactuel'];
    }

    if (empty($_POST['masquecentraleproximite'])) {
        header('location:/' . REPERTOIRE . '/update_nom_centraleErr2/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSAISIE');
        exit;
    } else {
        $libelleCentraleProximite = stripslashes(Securite::bdd($_POST['masquecentraleproximite']));        
        $masqueCentraleProximite=TRUE;
        $centraleName = new CentraleProximite($idCentraleProximite, $libelleCentraleProximite, $masqueCentraleProximite);
        $manager->hideCentraleProximite($centraleName, $idCentraleProximite);;
        header('location:/' . REPERTOIRE . '/update_nom_centrale/' . $lang . '/TXT_MESSAGESERVEURUPDATECENTRALE');
        exit();
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
BD::deconnecter();
