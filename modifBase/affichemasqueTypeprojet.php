<?php

session_start();

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
if (empty($_POST['idtypeprojetactuel'])) {
    header('location:/'.REPERTOIRE.'/hide_typeprojetErr1' . $lang . '/TXT_MESSAGEERREURGETYPEPROJETSELECT');
    exit;
} else {
    $idtypeprojetactuel = $_POST['idtypeprojetactuel'];
}
if (empty($_POST['modiftypeprojet'])) {
    header('location:/'.REPERTOIRE.'/hide_typeprojetErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPROJETNONSAISIE');
    exit;
}
if (empty($_POST['modiftypeprojeten'])) {
    header('location:/'.REPERTOIRE.'/hide_typeprojetErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPROJETNONSAISIE');
    exit;
} else {
    $modiftypeprojet = stripslashes(Securite::bdd($_POST['modiftypeprojet']));
    $modiftypeprojeten = stripslashes(Securite::bdd($_POST['modiftypeprojeten']));
    if ($lang == 'fr') {
        $idtypeprojetactuel1 = $manager->getSingle2("SELECT  idtypeprojet FROM typeprojet where libelletype =?", $modiftypeprojet);
    } elseif ($lang == 'en') {
        $idtypeprojetactuel1 = $manager->getSingle2("SELECT  idtypeprojet FROM typeprojet where libelletypeen =?", $modiftypeprojet);
    }    
    if (empty($idtypeprojetactuel1)) {
        header('location:/'.REPERTOIRE.'/hide_typeprojetErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPROJETNONSAISIE');
        exit;
    } else {
        if (isset($_POST['masquetypeprojet']) && $_POST['masquetypeprojet'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $typeprojet= new Typeprojet($idtypeprojetactuel, $modiftypeprojet, TRUE, $modiftypeprojeten);
            } elseif ($lang == 'en') {
                $typeprojet= new Typeprojet($idtypeprojetactuel, $modiftypeprojeten, TRUE, $modiftypeprojet);
            }
            $manager->afficheHideTypeprojet($typeprojet, $idtypeprojetactuel);
            header('location:/'.REPERTOIRE.'/hide_typeprojet/' . $lang . '/TXT_MESSAGESERVEURTYPEPROJETMASQUER');
            exit;
        } elseif (isset($_POST['affichetypeprojet']) && $_POST['affichetypeprojet'] ==TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $typeprojet= new Typeprojet($idtypeprojetactuel, $modiftypeprojet, FALSE, $modiftypeprojeten);
            } elseif ($lang == 'en') {
                $typeprojet= new Typeprojet($idtypeprojetactuel, $modiftypeprojeten, FALSE, $modiftypeprojet);
            }
            $manager->afficheHideTypeprojet($typeprojet, $idtypeprojetactuel);
            header('location:/'.REPERTOIRE.'/show_typeprojet/' . $lang . '/TXT_MESSAGESERVEURTYPEPROJETAFFICHE');
            exit;
        }
    }
}
BD::deconnecter();