<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../decide-lang.php';
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../class/Securite.php';
if (empty($_POST['idlibellepaysactuel'])) {
    header('location:/'.REPERTOIRE.'/update_paysErr2/' . $lang . '/TXT_MESSAGEERREURPAYSENONSELECT');
    exit;
} else {
    $idpays = $_POST['idlibellepaysactuel'];
}
if (empty($_POST['libellesituationgeo'])) {
    $idsituation = $manager->getSingle2("SELECT idsituation_situationgeographique FROM situationgeographique,pays WHERE idsituation_situationgeographique = idsituation and idpays =?", $idpays);
} else {
    $idsituation = $manager->getSingle2("select idsituation from situationgeographique where libellesituationgeo=?", stripslashes(Securite::bdd($_POST['libellesituationgeo'])));
}

if (empty($_POST['modifpays'])) {
    header('location:/'.REPERTOIRE.'/update_paysErr4' . $lang . '/TXT_MESSAGEERREURPAYSNONSAISIE');
    exit;
}if (empty($_POST['modifpaysen'])) {
    header('location:/'.REPERTOIRE.'/update_paysErr3/' . $lang . '/TXT_MESSAGEERREURPAYSENNONSAISIE');
    exit;
} else {
    $boolmasquepays = $manager->getSingle2("select masquepays from pays where idpays=? ", $idpays);
    if ($boolmasquepays == 1) {
        $boolmasquepays = 'TRUE';
    } else {
        $boolmasquepays = 'FALSE';
    }
    $modifpays = stripslashes(Securite::bdd($_POST['modifpays']));
    $modifpaysen = stripslashes(Securite::bdd($_POST['modifpaysen']));
    if($lang=='fr'){
        $pays = new Pays($idpays, $modifpays, $idsituation, $modifpaysen, $boolmasquepays);
    }elseif($lang=='en'){
        $pays = new Pays($idpays, $modifpaysen, $idsituation, $modifpays, $boolmasquepays);
    }
    $manager->updatePays($pays, $idpays);
    header('location:/'.REPERTOIRE.'/update_pays/' . $lang . '/TXT_MESSAGESERVEURUPDATEPAYS');
    exit;
}
BD::deconnecter();