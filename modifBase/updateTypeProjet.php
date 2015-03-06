<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
if (empty($_POST['idtypeprojetactuel'])) {
    header('location:/'.REPERTOIRE.'/update_typeprojetErr1/' . $lang . '/TXT_MESSAGEERREURGETYPEPROJETSELECT');
    exit;
} else {
    $idtypeprojet = $_POST['idtypeprojetactuel'];
}
if (empty($_POST['modiftypeprojet'])) {
    header('location:/'.REPERTOIRE.'/update_typeprojetErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPROJETNONSAISIE');
    exit;
}
if (empty($_POST['modiftypeprojeten'])) {
    header('location:/'.REPERTOIRE.'/update_typeprojetErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPROJETNONSAISIE');
    exit;
} else {
    $modiftypeprojet = stripslashes(Securite::bdd($_POST['modiftypeprojet']));
    $modiftypeprojeten = stripslashes(Securite::bdd($_POST['modiftypeprojeten']));
    $booltypeprojet = $manager->getSingle2("select masquetypeprojet from typeprojet where idtypeprojets=? ", $idtypeprojet);
    if ($booltypeprojet == 1) {
        $booltypeprojet = 'TRUE';
    } else {
        $booltypeprojet = 'FALSE';
    }
    if ($lang == 'fr') {
        $typeprojet = new Typeprojet($idtypeprojet, $modiftypeprojet, $booltypeprojet, $modiftypeprojeten);
    } elseif ($lang == 'en') {
        $typeprojet = new Typeprojet($idtypeprojet, $modiftypeprojeten, $booltypeprojet, $modiftypeprojet);
    }
    $manager->updateTypeprojet($typeprojet, $idtypeprojet);
    header('location:/'.REPERTOIRE.'/update_typeprojet/' . $lang . '/TXT_MESSAGETYPEPROJETUPDATE');
    exit;
}
BD::deconnecter();