<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'formulaireListe1.php') {
    include_once '../class/Manager.php';
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

    if (empty($_POST['modifpays'])) {
        header('location:/'.REPERTOIRE.'/insert_paysErr3/' . $lang . '/TXT_MESSAGEERREURPAYSNONSAISIE');
        exit;
    } else {
        $modifpays = stripslashes(Securite::bdd($_POST['modifpays']));
    }
    if (empty($_POST['modifpaysen'])) {
        header('location:/'.REPERTOIRE.'/insert_paysErr2/' . $lang . '/TXT_MESSAGEERREURPAYSENNONSAISIE');
        exit;
    } else {
        $modifpaysen = stripslashes(Securite::bdd($_POST['modifpaysen']));
    }
    if (empty($_POST['libellesituationgeo'])) {
        header('location:/'.REPERTOIRE.'/insert_paysErr4/' . $lang . '/TXT_MESSAGEERREURGEONONSELECT');
        exit;
    } else {
        $idsit = $manager->getSingle2("SELECT idsituation FROM situationgeographique where libellesituationgeo =?", $_POST['libellesituationgeo']);
        $idpays = $manager->getSingle2("select idpays from pays where nompays=?", $modifpays);
        if (!empty($idpays)) {
            header('location:/'.REPERTOIRE.'/insert_paysErr1/' . $lang . '/TXT_MESSAGESERVEURPAYSEXISTE');
            exit;
        } else {
            $idnewpays = $manager->getSingle("select max (idpays) from pays") + 1;
            if ($lang == 'fr') {
                $pays = new Pays($idnewpays, $modifpays, $idsit, $modifpaysen, FALSE);
            } elseif ($lang == 'en') {
                $pays = new Pays($idnewpays, $modifpaysen, $idsit, $modifpays, FALSE);
            }
            $manager->addPays($pays);
            header('location:/'.REPERTOIRE.'/insert_pays/' . $lang . '/TXT_MESSAGESERVEURPAYS');
            exit;
        }
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
BD::deconnecter();