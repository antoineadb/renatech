<?php

include_once '../class/Manager.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once	'../class/Securite.php';

if (empty($_POST['modiftutelle'])) {
    header('location:/'.REPERTOIRE.'/insert_tutelleErr1/' . $lang . '/TXT_MESSAGEERREURTUTELLENONSAISIE');
    exit;
}
if (empty($_POST['modiftutelleen'])) {
    header('location:/'.REPERTOIRE.'/insert_tutelleErr1/' . $lang . '/TXT_MESSAGEERREURTUTELLENONSAISIE');
    exit;
}else{
    $modiftutelle = stripslashes(Securite::bdd($_POST['modiftutelle']));
    $modiftutelleen = stripslashes(Securite::bdd($_POST['modiftutelleen']));
    $idtutelle = $manager->getSingle2("SELECT libelletutelle FROM tutelle Where libelletutelle = ?",$modiftutelle);
    if (!empty($idtutelle)) {
        header('location:/'.REPERTOIRE.'/insert_tutelleErr2/' . $lang . '/TXT_MESSAGESERVEURTUTELLEEXISTE');
        exit;
    } else {
        $idnewtutelle = $manager->getSingle("SELECT max(idtutelle) FROM tutelle") + 1;
        if($lang=='fr'){
            $tutelle = new Tutelle($idnewtutelle, $modiftutelle, FALSE, $modiftutelleen);
        }elseif ($lang=='en') {
            $tutelle = new Tutelle($idnewtutelle, $modiftutelleen, FALSE, $modiftutelle);
        }
        $manager->addtutelle($tutelle);
        header('location:/'.REPERTOIRE.'/insert_tutelle/' . $lang . '/TXT_MESSAGESERVEURTUTELLE');
        exit;
    }
}
BD::deconnecter();