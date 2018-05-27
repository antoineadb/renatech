<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (empty($_POST['modiftypeformation'])) {
    header('location:/'.REPERTOIRE.'/insert_typeformationErr1/' . $lang . '/TXT_MESSAGEERREURTYPEFORMATIONNONSAISIE');
    exit;
}
if (empty($_POST['modiftypeformationen'])) {
    header('location:/'.REPERTOIRE.'/insert_typeformationErr1/' . $lang . '/TXT_MESSAGEERREURTYPEFORMATIONNONSAISIE');
    exit;
} else {
    $modiftypeformation = stripslashes(Securite::bdd($_POST['modiftypeformation']));
    $modiftypeformationen = stripslashes(Securite::bdd($_POST['modiftypeformationen']));
    $idtype = $manager->getSingle2("SELECT libelletypeformation FROM typeformation Where libelletypeformation like ?", $modiftypeformation);
    if (!empty($idtype)) {
        header('location:/'.REPERTOIRE.'/insert_typeformationErr2/' . $lang . '/TXT_MESSAGESERVEURTYPEFORMATIONEXISTE');
        exit;
    } else {
        $idnewtype = $manager->getSingle("SELECT max(idtypeformation) FROM typeformation;") + 1;
        if ($lang == 'fr') {
            $typeformation = new Typeformation($idnewtype, $modiftypeformation,$modiftypeformationen, FALSE);
        } elseif ($lang == 'en') {
            $typeformation = new Typeformation($idnewtype, $modiftypeformationen, $modiftypeformation,FALSE);
        }
        $manager->addTypeFormation($typeformation);
        header('location:/'.REPERTOIRE.'/insert_typeformation/' . $lang . '/TXT_MESSAGESERVEURTYPEFORMATION');
    }
}
BD::deconnecter();