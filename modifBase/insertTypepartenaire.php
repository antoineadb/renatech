<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';

if (empty($_POST['modiftypepartenaire'])) {
    header('location:/'.REPERTOIRE.'/insert_typepartErr1/' . $lang . '/TXT_MESSAGEERREURTYPEPARTENAIRENONSAISIE');
    exit;
}
if (empty($_POST['modiftypepartenaireen'])) {
    header('location:/'.REPERTOIRE.'/insert_typeentErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPARTENAIRENONSAISIE');
    exit;
} else {
    $modiftypepartenaire = stripslashes(Securite::bdd($_POST['modiftypepartenaire']));
    $modiftypepartenaireen = stripslashes(Securite::bdd($_POST['modiftypepartenaireen']));
    $idtypepartenaire = $manager->getSingle2("SELECT libelletypepartenairefr FROM typepartenaire Where trim(libelletypepartenairefr) =?", trim($modiftypepartenaire));
    if (!empty($idtypepartenaire)) {
        header('location:/'.REPERTOIRE.'/insert_typepartenaireErr3/' . $lang . '/TXT_MESSAGESERVEURTYPEPARTENAIRESEEXISTE');
        exit;
    } else {
        $idnewtypepartenaire = $manager->getSingle("SELECT max(idtypepartenaire) FROM typepartenaire;") + 1;
        if ($lang == 'fr') {            
            $typepartenaire = new TypePartenaire($idnewtypepartenaire, $modiftypepartenaire, $modiftypepartenaireen,FALSE);
        } elseif ($lang == 'en') {
            $typepartenaire = new TypePartenaire($idnewtypepartenaire, $modiftypepartenaireen,$modiftypepartenaire,FALSE);
        }
        $manager->addTypepartenaire($typepartenaire);
        header('location:/'.REPERTOIRE.'/insert_typepart/' . $lang . '/TXT_MESSAGESERVEURTYPEPARTENAIRE');
        exit;
    }
}
BD::deconnecter();
?>