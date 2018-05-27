<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'formulaireListe2.php') {
    include_once '../class/Manager.php';
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    if (empty($_POST['region'])) {
        header('location:/'.REPERTOIRE.'/insert_paysErr3/' . $lang . '/TXT_MESSAGEERREURPAYSNONSAISIE');
        exit;
    } else {
        $libelleRegion = stripslashes(Securite::bdd($_POST['region']));
    }
    
    $idregion = $manager->getSingle2("select idregion from region where libelleregion=?", $libelleRegion);
    if (!empty($idregion)) {
        header('location:/'.REPERTOIRE.'/insert_paysErr1/' . $lang . '/TXT_MESSAGESERVEURPAYSEXISTE');
        exit;
    } else {
        //AJOUT DE LA REGION DANS LA BASE DE DONNEE 
        $idNewRegion = $manager->getSingle("select max (idregion) from region") + 1;
        $masqueRegion = FALSE;
        $region = new Region($idNewRegion, $libelleRegion, $masqueRegion);
        $manager->addRegion($region);
        
        header('location:/'.REPERTOIRE.'/ManageRegion/' . $lang .'/' .$idNewRegion.'/ar');
        exit;
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
BD::deconnecter();