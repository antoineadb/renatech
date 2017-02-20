<?php

session_start();

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once	'../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
if (empty($_POST['idtypeformationactuel'])) {
    header('location:/'.REPERTOIRE.'/hide_typeformationErr1' . $lang . 'hide_typeformationErr1TXT_MESSAGEERREURGETYPEFORMATIONSELECT');
    exit;
} else {
    $idtypeformationactuel = $_POST['idtypeformationactuel'];
}
if (empty($_POST['modiftypeformation'])) {
    header('location:/'.REPERTOIRE.'/hide_typeformationErr2/' . $lang .'/TXT_MESSAGEERREURTYPEFORMATIONNONSAISIE');
    exit;
}else if (empty($_POST['modiftypeformationen'])) {
    header('location:/'.REPERTOIRE.'/hide_typeformationErr2/' . $lang .'/TXT_MESSAGEERREURTYPEFORMATIONNONSAISIE');
    exit;
} else {
    $modiftypeformation = stripslashes(Securite::bdd($_POST['modiftypeformation']));
    $modiftypeformationen = stripslashes(Securite::bdd($_POST['modiftypeformationen']));
    if ($lang == 'fr') {
        $idtypeformationactuel1 = $manager->getSingle2("SELECT  idtypeformation FROM typeformation where libelletypeformation =?", $modiftypeformation);
    } elseif ($lang == 'en') {
        $idtypeformationactuel1 = $manager->getSingle2("SELECT  idtypeformation FROM typeformation where libelletypeformationen =?", $modiftypeformationen);
    }    
    if (empty($idtypeformationactuel1)) {
        header('location:/'.REPERTOIRE.'/hide_typeformationErr2/' . $lang .'/TXT_MESSAGEERREURTYPEFORMATIONNONSAISIE');
        exit;
    } else {
        if (isset($_POST['masquetypeformation']) && $_POST['masquetypeformation'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $typeformation= new Typeformation($idtypeformationactuel, $modiftypeformation,  $modiftypeformationen,TRUE);
            } elseif ($lang == 'en') {
                $typeformation= new Typeformation($idtypeformationactuel, $modiftypeformationen, $modiftypeformation,TRUE);
            }
            $manager->afficheHideTypeformation($typeformation, $idtypeformationactuel);
            header('location:/'.REPERTOIRE.'/hide_typeformation/' . $lang . '/TXT_MESSAGESERVEURTYPEFORMATIONMASQUER');
            exit;
        } elseif (isset($_POST['affichetypeformation']) && $_POST['affichetypeformation'] ==TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $typeformation= new Typeformation($idtypeformationactuel, $modiftypeformation,  $modiftypeformationen,FALSE);
            } elseif ($lang == 'en') {
                $typeformation= new Typeformation($idtypeformationactuel, $modiftypeformationen, $modiftypeformation,FALSE);
            }            
            $manager->afficheHideTypeformation($typeformation, $idtypeformationactuel);
            header('location:/'.REPERTOIRE.'/show_typeformation/' . $lang . '/TXT_MESSAGESERVEURTYPEFORMATIONAFFICHE');
            exit;
        }
    }
}
BD::deconnecter();