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
if (empty($_POST['idthematiqueactuel'])) {
    header('location:/' . REPERTOIRE . '/hide_thematiqueErr1/' . $lang . '/TXT_MESSAGEERREURTHEMATIQUESELECT');
    exit;
} else {
    $idthematiqueactuel = $_POST['idthematiqueactuel'];
}
if (empty($_POST['modifthematique'])) {
    header('location:/' . REPERTOIRE . '/hide_thematiqueErr2/' . $lang . '/TXT_MESSAGEERREURTHEMATIQUENONSAISIE');
    exit;
} else if (empty($_POST['modifthematiqueen'])) {
    header('location:/' . REPERTOIRE . '/hide_thematiqueErr2/' . $lang . '/TXT_MESSAGEERREURTHEMATIQUENONSAISIE');
    exit;
} else {
    $modifthematique = stripslashes(Securite::bdd($_POST['modifthematique']));
    $modifthematiqueen = stripslashes(Securite::bdd($_POST['modifthematiqueen']));
    if ($lang == 'fr') {
        $idthematiqueactuel1 = $manager->getSingle2("SELECT  idthematique FROM thematique where libellethematique =?", $modifthematique);
    } elseif ($lang == 'en') {
        $idthematiqueactuel1 = $manager->getSingle2("SELECT  idthematique FROM thematique where libellethematiqueen =?", $modifthematiqueen);
    }
    if (empty($idthematiqueactuel1)) {
        header('location:/' . REPERTOIRE . '/hide_thematiqueErr2/' . $lang . '/TXT_MESSAGEERREURTHEMATIQUENONSAISIE');
        exit;
    } else {
        if (isset($_POST['masquethematique']) && $_POST['masquethematique'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $thematique = new Thematique($idthematiqueactuel, $modifthematique, TRUE, $modifthematiqueen);
            } elseif ($lang == 'en') {
                $thematique = new Thematique($idthematiqueactuel, $modifthematiqueen, TRUE, $modifthematique);
            }
            $manager->afficheHideThematique($thematique, $idthematiqueactuel);
            header('location:/' . REPERTOIRE . '/hide_thematique/' . $lang . '/TXT_MESSAGESERVEURTHEMATIQUEMASQUER');
            exit;
        } elseif (isset($_POST['affichethematique']) && $_POST['affichethematique'] == TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $thematique = new Thematique($idthematiqueactuel, $modifthematique, FALSE, $modifthematiqueen);
            } elseif ($lang == 'en') {
                $thematique = new Thematique($idthematiqueactuel, $modifthematiqueen, FALSE, $modifthematique);
            }
            $manager->afficheHideThematique($thematique, $idthematiqueactuel);
            header('location:/' . REPERTOIRE . '/show_thematique/' . $lang . '/TXT_MESSAGESERVEURTHEMATIQUEAFFICHE');
            exit;
        }
    }
}
BD::deconnecter();
