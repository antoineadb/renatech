<?php

session_start();

include_once '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';

if (empty($_POST['idlibellepaysactuel'])) {
    header('location:/' . REPERTOIRE . '/hide_paysErr1/' . $lang . '/TXT_MESSAGEERREURPAYSENONSELECT');
    exit();
} else {
    $idlibellepaysactuel = $_POST['idlibellepaysactuel'];
}
if (empty($_POST['libellesituationgeo'])) {
    $idsituation = $manager->getSingle2("SELECT idsituation_situationgeographique FROM situationgeographique,pays WHERE idsituation_situationgeographique = idsituation and idpays =?", $idlibellepaysactuel);
} else {
    $idsituation = $manager->getSingle2("select idsituation from situationgeographique where libellesituationgeo=?", stripslashes(Securite::bdd($_POST['libellesituationgeo'])));
}

if (empty($_POST['modifpays'])) {
    header('location:/' . REPERTOIRE . '/hide_paysErr2/' . $lang . '/TXT_MESSAGEERREURPAYSNONSAISIE');
    exit();
} else if (empty($_POST['modifpaysen'])) {
    header('location:/' . REPERTOIRE . '/hide_paysErr2/' . $lang . '/TXT_MESSAGEERREURPAYSENNONSAISIE');
    exit();
} else {
    $modifpays = stripslashes(Securite::bdd($_POST['modifpays']));
    $modifpaysen = stripslashes(Securite::bdd($_POST['modifpaysen']));
    if ($lang == 'fr') {
        $idpays1 = $manager->getSingle2("SELECT  idpays FROM pays where nompays =?", $modifpays);
    } elseif ($lang == 'en') {
        $idpays1 = $manager->getSingle2("SELECT  idpays FROM pays where nompaysen =?", $modifpaysen);
    }

    if (empty($idpays1)) {
        header('location:/' . REPERTOIRE . '/hide_paysErr3/' . $lang . '/TXT_MESSAGESERVEURPAYSEXISTE');
        exit();
    } else {

        if (isset($_POST['masquepays']) && $_POST['masquepays'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $pays = new Pays($idlibellepaysactuel, $modifpays, $idsituation, $modifpaysen, TRUE);
            } elseif ($lang == 'en') {
                $pays = new Pays($idlibellepaysactuel, $modifpaysen, $idsituation, $modifpays, TRUE);
            }
            $manager->afficheHidePays($pays, $idlibellepaysactuel);
            header('location:/' . REPERTOIRE . '/hide_pays/' . $lang . '/TXT_MESSAGESERVEURPAYSMASQUER');
            exit();
        } elseif (isset($_POST['affichepays']) && $_POST['affichepays'] == TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $pays = new Pays($idlibellepaysactuel, $modifpays, $idsituation, $modifpaysen, FALSE);
            } elseif ($lang == 'en') {
                $pays = new Pays($idlibellepaysactuel, $modifpaysen, $idsituation, $modifpays, FALSE);
            }
            $manager->afficheHidePays($pays, $idlibellepaysactuel);
            header('location:/' . REPERTOIRE . '/show_pays/' . $lang . '/TXT_MESSAGESERVEURPAYSAFFICHE');
            exit();
        }
    }
}
BD::deconnecter();
