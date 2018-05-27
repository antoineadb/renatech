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
if (empty($_POST['idtypepartenaireactuel'])) {
    header('location:/' . REPERTOIRE . '/show_typepartErr1/' . $lang . '/TXT_MESSAGEERREURTYPEPARTENAIRESELECT');
    exit();
} else {
    $idtypepartenaire = $_POST['idtypepartenaireactuel'];
}

if (empty($_POST['modiftypepartenaire'])) {
    header('location:/' . REPERTOIRE . '/hide_typepartErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPARTENAIRENONSAISIE');
    exit();
} else if (empty($_POST['modiftypepartenaireen'])) {
    header('location:/' . REPERTOIRE . '/hide_typepartErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPARTENAIRENONSAISIE');
    exit();
} else {
    $modiftypepartenaire = trim(stripslashes(Securite::bdd($_POST['modiftypepartenaire'])));
    $modiftypepartenaireen =trim(stripslashes(Securite::bdd($_POST['modiftypepartenaireen'])));
    if ($lang == 'fr') {
        $idtypepartenaire1 = $manager->getSingle2("SELECT  idtypepartenaire FROM typepartenaire where trim(libelletypepartenairefr) =?", $modiftypepartenaire);
    } elseif ($lang == 'en') {
        $idtypepartenaire1 = $manager->getSingle2("SELECT  idtypepartenaire FROM typepartenaire where trim(libelletypepartenaireen) =?", $modiftypepartenaireen);
    }
  
    if (empty($idtypepartenaire1)) {
        header('location:/' . REPERTOIRE . '/hide_typepartErr3/' . $lang . '/TXT_MESSAGEERREURTYPEPARTENAIRENONSAISIE');
        exit();
    } else {
        if (isset($_POST['masquetypepartenaire']) && $_POST['masquetypepartenaire'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $typepartenaire = new TypePartenaire($idtypepartenaire, $modiftypepartenaire, $modiftypepartenaireen, TRUE);
            } elseif ($lang == 'en') {
                $typepartenaire = new TypePartenaire($idtypepartenaire, $modiftypepartenaireen, $modiftypepartenaire, TRUE);
            }

            $manager->afficheHideTypepartenaire($typepartenaire, $idtypepartenaire);
            header('location:/' . REPERTOIRE . '/show_typepart/' . $lang . '/TXT_MESSAGESERVEURTYPEPARTENAIREMASQUER');
            exit();
        } elseif (isset($_POST['affichetypepartenaire']) && $_POST['affichetypepartenaire'] == TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $typepartenaire = new TypePartenaire($idtypepartenaire, $modiftypepartenaire, $modiftypepartenaireen, FALSE);
            } elseif ($lang == 'en') {
                $typepartenaire = new TypePartenaire($idtypepartenaire, $modiftypepartenaireen, $modiftypepartenaire, FALSE);
            }
            $manager->afficheHideTypepartenaire($typepartenaire, $idtypepartenaire);
            header('location:/' . REPERTOIRE . '/hide_typepart/' . $lang . '/TXT_MESSAGESERVEURTYPEPARTENAIREAFFICHE');
            exit();
        }
    }
}
