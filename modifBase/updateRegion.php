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
    if (empty($_POST['idregionActuel'])) {
        header('location:/' . REPERTOIRE . '/update_nom_centraleErr1/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSELECT');
        exit;
    } else {
        $idregion = $_POST['idregionActuel'];
    }

    if (empty($_POST['modifregion'])) {
        header('location:/' . REPERTOIRE . '/update_nom_centraleErr2/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSAISIE');
        exit;
    } else {
        $libelleRegion = stripslashes(Securite::bdd($_POST['modifregion']));
        $masqueRegion = $manager->getSingle2("select masquecentraleregion from region where idregion=? ", $idregion);
        if ($masqueRegion == 1) {
            $masqueRegion = 'TRUE';
        } else {
            $masqueRegion = 'FALSE';
        }
        $region = new Region($idregion, $libelleRegion, $masqueRegion);
        $manager->updateRegion($region, $idregion);
        header('location:/' . REPERTOIRE . '/updateRegion/' . $lang .'/'.$idregion. '/mr');
        exit();
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
BD::deconnecter();
