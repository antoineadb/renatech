<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../class/Securite.php';
include '../decide-lang.php';
include '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../class/Cache.php';
define('ROOT', dirname(__FILE__));

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'emailRelanceCustom.php') {
    $db = BD::connecter();
    $manager = new Manager($db);
    include_once '../class/Securite.php';
    $emailFR = $manager->getSingle2("SELECT libelleFrancais from libelleapplication where reflibelle=?", "TXT_RELANCEEMAIL_" . $_POST['idcentrale'] . "");
    $emailEN = $manager->getSingle2("SELECT libelleAnglais from libelleapplication where reflibelle=?", "TXT_RELANCEEMAIL_" .  $_POST['idcentrale'] . "");
    
    if (empty($emailFR) && empty($emailEN)) {
        $libelleFrancais = stripslashes(Securite::bdd($_POST['modifEmailRelanceFR']));
        $libelleAnglais = stripslashes(Securite::bdd($_POST['modifEmailRelanceEN']));
        $refLibelle = "TXT_RELANCEEMAIL_" . $_POST['idcentrale']."";        
        $libelleapplication = new Libelleapplication($libelleFrancais, $libelleAnglais, $refLibelle);      
        $manager->addLibelleApplication($libelleapplication);
    }else{
        if (empty($_POST['modifEmailRelanceFR'])) {
            $_POST['modifEmailRelanceFR'] = $manager->getSingle2("SELECT libellefrancais from libelleapplication where reflibelle=?", "TXT_RELANCEEMAIL_" . $_POST['idcentrale']);
        }
        if (empty($_POST['modifEmailRelanceEN'])) {
            $_POST['modifEmailRelanceEN'] = $manager->getSingle2("SELECT libelleanglais from libelleapplication where reflibelle=?", "TXT_RELANCEEMAIL_" . $_POST['idcentrale']);
        }
        
        $libelleFrancais = stripslashes(Securite::bdd($_POST['modifEmailRelanceFR']));
        $libelleAnglais = stripslashes(Securite::bdd($_POST['modifEmailRelanceEN']));
        $refLibelle = "TXT_RELANCEEMAIL_" . $_POST['idcentrale']."";
        $libelleapplication = new Libelleapplication($libelleFrancais, $libelleAnglais, $refLibelle);
        $manager->updateLibelleApplication($libelleapplication, $refLibelle);
    }

    BD::deconnecter();
    header('location: /' . REPERTOIRE . '/custom_email/' . $lang . '/msgupdateemail');
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}