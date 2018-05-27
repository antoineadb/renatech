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
if (empty($_POST['idtypeentrepriseactuel'])) {
    header('location:/' . REPERTOIRE . '/hide_typeentErr1/' . $lang . '/TXT_MESSAGEERREURTYPEENTRESELECT');
    exit();
} else {
    $idtypeentreprise = $_POST['idtypeentrepriseactuel'];
}
if (empty($_POST['modiftypeentreprise'])) {
    header('location:/' . REPERTOIRE . '/hide_typeentErr2/' . $lang . '/TXT_MESSAGEERREURTYPEENTREPRISENONSAISIE');
    exit();
} else if (empty($_POST['modiftypeentrepriseen'])) {
    header('location:/' . REPERTOIRE . '/hide_typeentErr2/' . $lang . '/TXT_MESSAGEERREURTYPEENTREPRISENONSAISIE');
    exit();
} else {
    $modiftypeentreprise = stripslashes(Securite::bdd($_POST['modiftypeentreprise']));
    $modiftypeentrepriseen = stripslashes(Securite::bdd($_POST['modiftypeentrepriseen']));
    if ($lang == 'fr') {
        $idtypeentreprise1 = $manager->getSingle2("SELECT  idtypeentreprise FROM typeentreprise where libelletypeentreprise =?", $modiftypeentreprise);
    } elseif ($lang == 'en') {
        $idtypeentreprise1 = $manager->getSingle2("SELECT  idtypeentreprise FROM typeentreprise where libelletypeentrepriseen =?", $modiftypeentrepriseen);
    }
    if (empty($idtypeentreprise1)) {
        header('location:/' . REPERTOIRE . '/hide_typeentErr3/' . $lang . '/TXT_MESSAGEERREURTYPEENTREPRISENONSAISIE');
        exit();
    } else {
        if (isset($_POST['masquetypeentreprise']) && $_POST['masquetypeentreprise'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $typeentreprise = new Typeentreprise($idtypeentreprise, $modiftypeentreprise, TRUE, $modiftypeentrepriseen);
            } elseif ($lang == 'en') {
                $typeentreprise = new Typeentreprise($idtypeentreprise, $modiftypeentrepriseen, TRUE, $modiftypeentreprise);
            }

            $manager->afficheHideTypeentreprise($typeentreprise, $idtypeentreprise);
            header('location:/' . REPERTOIRE . '/hide_typeent/' . $lang . '/TXT_MESSAGESERVEURTYPEENTREPRISEMASQUER');
            exit();
        } elseif (isset($_POST['affichetypeentreprise']) && $_POST['affichetypeentreprise'] == TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $typeentreprise = new Typeentreprise($idtypeentreprise, $modiftypeentreprise, FALSE, $modiftypeentrepriseen);
            } elseif ($lang == 'en') {
                $typeentreprise = new Typeentreprise($idtypeentreprise, $modiftypeentrepriseen, FALSE, $modiftypeentreprise);
            }
            $manager->afficheHideTypeentreprise($typeentreprise, $idtypeentreprise);
            header('location:/' . REPERTOIRE . '/show_typeent/' . $lang . '/TXT_MESSAGESERVEURTYPEENTREPRISEAFFICHE');
            exit();
        }
    }
}
