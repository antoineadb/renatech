<?php

session_start();

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
if (empty($_POST['idlibellesecteuractiviteactuel'])) {
    header('location:/' . REPERTOIRE . '/hide_sectorErr1/' . $lang . '/TXT_MESSAGEERREURSELECTSECTEURACTIVITE');
    exit;
} else {
    $idlibellesecteuractiviteactuel = $_POST['idlibellesecteuractiviteactuel'];
}
if (empty($_POST['modifsecteuractivite'])) {
    header('location:/' . REPERTOIRE . '/hide_sectorErr2/' . $lang . '/TXT_MESSAGEERREURSECTEURACTIVITENONSAISIE');
    exit;
} else if (empty($_POST['modifsecteuractiviteen'])) {
    header('location:/' . REPERTOIRE . '/hide_sectorErr2/' . $lang . '/TXT_MESSAGEERREURSECTEURACTIVITENONSAISIE');
    exit;
} else {
    $modifsecteuractivite = stripslashes(Securite::bdd($_POST['modifsecteuractivite']));
    $modifsecteuractiviteen = stripslashes(Securite::bdd($_POST['modifsecteuractiviteen']));
    if ($lang == 'fr') {
        $idsecteuractivite1 = $manager->getSingle2("SELECT  idsecteuractivite FROM secteuractivite where libellesecteuractivite =?", $modifsecteuractivite);
    } elseif ($lang == 'en') {
        $idsecteuractivite1 = $manager->getSingle2("SELECT  idsecteuractivite FROM secteuractivite where libellesecteuractiviteen =?", $modifsecteuractiviteen);
    }

    if (empty($idsecteuractivite1)) {
        header('location:/' . REPERTOIRE . '/hide_sectorErr3/' . $lang . '/TXT_MESSAGEERREURSECTEURACTIVITEEXISTE');
        exit;
    } else {
        if (isset($_POST['masquesecteuractivite']) && $_POST['masquesecteuractivite'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $secteuractivite = new Secteuractivite($idlibellesecteuractiviteactuel, $modifsecteuractivite, $modifsecteuractiviteen, TRUE);
            } elseif ($lang == 'en') {
                $secteuractivite = new Secteuractivite($idlibellesecteuractiviteactuel, $modifsecteuractiviteen, $modifsecteuractivite, TRUE);
            }

            $manager->afficheHideSecteurActivite($secteuractivite, $idlibellesecteuractiviteactuel);
            header('location:/' . REPERTOIRE . '/hide_sector/' . $lang . '/TXT_MESSAGESERVEURSECTEURACTIVITEMASQUER');
            exit;
        } elseif (isset($_POST['affichesecteuractivite']) && $_POST['affichesecteuractivite'] == TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $secteuractivite = new Secteuractivite($idlibellesecteuractiviteactuel, $modifsecteuractivite, $modifsecteuractiviteen, FALSE);
            } elseif ($lang == 'en') {
                $secteuractivite = new Secteuractivite($idlibellesecteuractiviteactuel, $modifsecteuractiviteen, $modifsecteuractivite, FALSE);
            }
            $manager->afficheHideSecteurActivite($secteuractivite, $idlibellesecteuractiviteactuel);
            header('location:/' . REPERTOIRE . '/show_sector/' . $lang . '/TXT_MESSAGESERVEURSECTEURACTIVITEAFFICHE');
            exit;
        }
    }
}
BD::deconnecter();