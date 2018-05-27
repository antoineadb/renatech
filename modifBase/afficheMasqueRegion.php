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
    if (empty($_POST['idlibelleregionactuel'])) {
        header('location:/' . REPERTOIRE . '/update_nom_centraleErr1/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSELECT');
        exit;
    } else {
        $idRegion = $_POST['idlibelleregionactuel'];
    }

    if (empty($_POST['masqueregion'])) {
        header('location:/' . REPERTOIRE . '/update_nom_centraleErr2/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSAISIE');
        exit;
    } else {
        $libelleRegion = stripslashes(Securite::bdd($_POST['masqueregion']));
        
        if(isset($_POST['masqueRegion'])){
            $masqueregion=TRUE;
        }elseif (isset($_POST['afficheRegion'])) {
            $masqueregion=FALSE;
        }
        $region = new Region($idRegion, $libelleRegion, $masqueregion);
        $manager->hideRegion($region, $idRegion);
        if(isset($_POST['masqueRegion'])){
            header('location:/' . REPERTOIRE . '/MasqueRegion/' . $lang .'/'.$idRegion. '/mreg');
            exit();
        }elseif (isset($_POST['afficheRegion'])) {
            header('location:/' . REPERTOIRE . '/MasqueRegion/' . $lang .'/'.$idRegion. '/areg');
            exit();
        }
        
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
BD::deconnecter();
