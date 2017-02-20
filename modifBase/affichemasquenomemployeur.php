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
if (empty($_POST['idnomemployeuractuel'])) {
    header('location:/' . REPERTOIRE . '/hide_employeurErr1/' . $lang . '/TXT_MESSAGEERREUREMPLOYEURSELECT');
    exit;
} else {
    $idnomemployeuractuel = $_POST['idnomemployeuractuel'];
}
if (empty($_POST['modifnomemployeur'])) {
    header('location:/' . REPERTOIRE . '/hide_employeurErr2/' . $lang . '/TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE');
    exit;
} else if (empty($_POST['modifnomemployeuren'])) {
    header('location:/' . REPERTOIRE . '/hide_employeurErr2/' . $lang . '/TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE');
    exit;
} else {
    $modifnomemployeur = stripslashes(Securite::bdd($_POST['modifnomemployeur']));
    $modifnomemployeuren = stripslashes(Securite::bdd($_POST['modifnomemployeuren']));
    if ($lang == 'fr') {
        $idnomemployeuractuel1 = $manager->getSingle2("SELECT  idemployeur FROM nomemployeur where libelleemployeur =?", $modifnomemployeur);
    } elseif ($lang == 'en') {
        $idnomemployeuractuel1 = $manager->getSingle2("SELECT  idemployeur FROM nomemployeur where libelleemployeuren =?", $modifnomemployeuren);
    }
    if (empty($idnomemployeuractuel1)) {
        header('location:/' . REPERTOIRE . '/hide_employeurErr3/' . $lang . '/TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE');
        exit;
    } else {
        if (isset($_POST['masqueNomemployeur']) && $_POST['masqueNomemployeur'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $nomemployeur = new Nomemployeur($idnomemployeuractuel, $modifnomemployeur, TRUE, $modifnomemployeuren);
            } elseif ($lang == 'en') {
                $nomemployeur = new Nomemployeur($idnomemployeuractuel, $modifnomemployeuren, TRUE, $modifnomemployeur);
            }

            $manager->afficheHidenomemployeur($nomemployeur, $idnomemployeuractuel);
            header('location:/' . REPERTOIRE . '/hide_employeur/' . $lang . '/TXT_MESSAGESERVEURNOMEMPLOYEURMASQUER');
            exit;
        } elseif (isset($_POST['afficheNomemployeur']) && $_POST['afficheNomemployeur'] == TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $nomemployeur = new Nomemployeur($idnomemployeuractuel, $modifnomemployeur, FALSE, $modifnomemployeuren);
            } elseif ($lang == 'en') {
                $nomemployeur = new Nomemployeur($idnomemployeuractuel, $modifnomemployeuren, FALSE, $modifnomemployeur);
            }
            $manager->afficheHidenomemployeur($nomemployeur, $idnomemployeuractuel);
            header('location:/' . REPERTOIRE . '/show_employeur/' . $lang . '/TXT_MESSAGESERVEURNOMEMPLOYEURAFFICHE');
            exit;
        }
    }
}
BD::deconnecter();
