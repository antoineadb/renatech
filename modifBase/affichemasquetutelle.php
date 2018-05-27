<?php

session_start();
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
if (empty($_POST['idtutelleactuel'])) {
    header('location:/' . REPERTOIRE . '/hide_tutelleerr1/' . $lang . '/TXT_MESSAGEERREURTUTELLESELECT');
    exit;
} else {
    $idtutelleactuel = $_POST['idtutelleactuel'];
}
if (empty($_POST['modiftutelle'])) {
    header('location:/' . REPERTOIRE . '/hide_tutelleerr2/' . $lang . '/TXT_MESSAGEERREURTUTELLENONSAISIE');
    exit;
} else if (empty($_POST['modiftutelleen'])) {
    header('location:/' . REPERTOIRE . '/hide_tutelleerr2/' . $lang . '/TXT_MESSAGEERREURTUTELLENONSAISIE');
    exit;
} else {
    $modiftutelle = stripslashes(Securite::bdd($_POST['modiftutelle']));
    $modiftutelleen = stripslashes(Securite::bdd($_POST['modiftutelleen']));
    if ($lang == 'fr') {
        $idtutelleactuel1 = $manager->getSingle2("SELECT  idtutelle FROM tutelle where libelletutelle =?", $modiftutelle);
    } elseif ($lang == 'en') {
        $idtutelleactuel1 = $manager->getSingle2("SELECT  idtutelle FROM tutelle where libelletutelleen =?", $modiftutelleen);
    }
    if (empty($idtutelleactuel1)) {
        header('location:/' . REPERTOIRE . '/hide_tutelleerr2/' . $lang . '/TXT_MESSAGEERREURTUTELLENONSAISIE');
        exit;
    } else {
        if (isset($_POST['masquetutelle']) && $_POST['masquetutelle'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $tutelle = new Tutelle($idtutelleactuel, $modiftutelle, TRUE, $modiftutelleen);
            } elseif ($lang == 'en') {
                $tutelle = new Tutelle($idtutelleactuel, $modiftutelleen, TRUE, $modiftutelle);
            }
            $manager->afficheHidetutelle($tutelle, $idtutelleactuel);
            header('location:/' . REPERTOIRE . '/hide_tutelle/' . $lang . '/TXT_MESSAGESERVEURTUTELLEMASQUER');
            exit;
        } elseif (isset($_POST['affichetutelle']) && $_POST['affichetutelle'] == TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $tutelle = new Tutelle($idtutelleactuel, $modiftutelle, FALSE, $modiftutelleen);
            } elseif ($lang == 'en') {
                $tutelle = new Tutelle($idtutelleactuel, $modiftutelleen, FALSE, $modiftutelle);
            }
            $manager->afficheHidetutelle($tutelle, $idtutelleactuel);
            header('location:/' . REPERTOIRE . '/show_tutelle/' . $lang . '/TXT_MESSAGESERVEURTUTELLEAFFICHE');
            exit;
        }
    }
}
BD::deconnecter();
