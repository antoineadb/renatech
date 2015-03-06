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

if (empty($_POST['idtypeformationactuel'])) {
    header('location:/'.REPERTOIRE.'/update_typeformationErr1/' . $lang . '/TXT_MESSAGEERREURGETYPEFORMATIONSELECT');
    exit;
} else {
    $idtypeformation = $_POST['idtypeformationactuel'];
}
if (empty($_POST['modiftypeformation'])) {
    header('location:/'.REPERTOIRE.'/update_typeformationErr2/' . $lang . '/TXT_MESSAGEERREURTYPEFORMATIONNONSAISIE');
    exit;
}
if (empty($_POST['modiftypeformationen'])) {
    header('location:/'.REPERTOIRE.'/update_typeformationErr2/' . $lang . '/TXT_MESSAGEERREURTYPEFORMATIONNONSAISIE');
    exit;
} else {
    $modiftypeformation = stripslashes(Securite::bdd($_POST['modiftypeformation']));
    $modiftypeformationen = stripslashes(Securite::bdd($_POST['modiftypeformationen']));
    $booltypeformation = $manager->getSingle2("select masquetypeformation from typeformation where idtypeformation=? ", $idtypeformation);
    if ($booltypeformation == 1) {
        $booltypeformation = 'TRUE';
    } else {
        $booltypeformation = 'FALSE';
    }
    if ($lang == 'fr') {
        $typeformation = new Typeformation($idtypeformation, $modiftypeformation,  $modiftypeformationen,$booltypeformation);
    } elseif ($lang == 'en') {
        $typeformation = new Typeformation($idtypeformation, $modiftypeformation,$modiftypeformationen,$booltypeformation);
    }
    
    $manager->updatetypeformation($typeformation, $idtypeformation);
    header('location:/'.REPERTOIRE.'/update_typeformation/' . $lang . '/TXT_MESSAGETYPEFORMATIONUPDATE');
    exit;
}
BD::deconnecter();