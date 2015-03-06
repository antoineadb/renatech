<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (empty($_POST['modiftypeprojet'])) {
    header('location:/'.REPERTOIRE.'/insert_typeprojetErr1/' . $lang . '/TXT_MESSAGEERREURTYPEPROJETNONSAISIE');
    exit;
}
if (empty($_POST['modiftypeprojeten'])) {
    header('location:/'.REPERTOIRE.'/insert_typeprojetErr1/' . $lang . '/TXT_MESSAGEERREURTYPEPROJETNONSAISIE');
    exit;
} else {
    $modiftypeprojet = stripslashes(Securite::bdd($_POST['modiftypeprojet']));
    $modiftypeprojeten = stripslashes(Securite::bdd($_POST['modiftypeprojeten']));
    $idtype = $manager->getSingle2("SELECT libelletype FROM typeprojet Where libelletype like ?", $modiftypeprojet);
    if (!empty($idtype)) {
        header('location:/'.REPERTOIRE.'/insert_typeprojetErr2/' . $lang . '/TXT_MESSAGESERVEURTYPEEXISTE');
        exit;
    } else {
        $idnewtype = $manager->getSingle("SELECT max(idtypeprojet) FROM typeprojet;") + 1;
        if ($lang == 'fr') {
            $typeprojet = new Typeprojet($idnewtype, $modiftypeprojet, FALSE, $modiftypeprojeten);
        } elseif ($lang == 'en') {
            $typeprojet = new Typeprojet($idnewtype, $modiftypeprojeten, FALSE, $modiftypeprojet);
        }
        $manager->addTypeprojet($typeprojet);
        header('location:/'.REPERTOIRE.'/insert_typeprojet/' . $lang . '/TXT_MESSAGESERVEURTYPEPROJET');
    }
}
BD::deconnecter();