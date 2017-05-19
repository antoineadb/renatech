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

if (empty($_POST['idtypepartenaireactuel'])) {
    header('location:/'.REPERTOIRE.'/update_typeentErr1/' . $lang . '/TXT_MESSAGEERREURTYPEPARTENAIRESELECT');
    exit;
} else {
    $idtypepartenaire = $_POST['idtypepartenaireactuel'];
}
if (empty($_POST['modiftypePartenaire'])) {
    header('location:/'.REPERTOIRE.'/update_typepartErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPARTENAIRENONSAISIE');
    exit;
}if (empty($_POST['modiftypepartenaireen'])) {
    header('location:/'.REPERTOIRE.'/update_typepartErr2/' . $lang . '/TXT_MESSAGEERREURTYPEPARTENAIRENONSAISIE');
    exit;
} else {
    $modiftypepartenaire = stripslashes(Securite::bdd($_POST['modiftypePartenaire']));
    $modiftypepartenaireen = stripslashes(Securite::bdd($_POST['modiftypepartenaireen']));

    $booltypepartenairemasquesecteur = $manager->getSingle2("select masquetypepartenaire from typepartenaire where idtypepartenaire=? ", $idtypepartenaire);
    if ($booltypepartenairemasquesecteur == 1) {
        $booltypepartenaire = 'TRUE';
    } else {
        $booltypepartenaire = 'FALSE';
    }
    if ($lang == 'fr') {        
        $typepartenaire = new TypePartenaire($idtypepartenaire, $modiftypepartenaire, $modiftypepartenaireen,$booltypepartenaire);
    } elseif ($lang == 'en') {        
        $typepartenaire = new TypePartenaire($idtypepartenaire, $modiftypepartenaireen, $modiftypepartenaire,$booltypepartenaire);
    }    
    $manager->updateTypepartenaire($typepartenaire, $idtypepartenaire);
    header('location:/'.REPERTOIRE.'/update_typepart/' . $lang . '/TXT_MESSAGESERVEURTYPEPARTENAIREUPDATE');
    exit;
}
BD::deconnecter();
?>