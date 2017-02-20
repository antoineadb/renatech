<?php

session_start();
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';

$db = BD::connecter();
$manager = new Manager($db);
$arrayProjetCentrale= $manager->getListbyArray("select idprojet from projet,concerne where idprojet_projet=idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=?", array(IDCENTRALEUSER,ENCOURSREALISATION));
$nbarrayProjetCentrale = count($arrayProjetCentrale);
if($nbarrayProjetCentrale>0){
    foreach ($arrayProjetCentrale as $key => $idprojet) {
        $projetDateEnvoiEmail = new ProjetDateEnvoiEmail(NULL, $idprojet[0]);
        $manager->updateDateenvoiemail($projetDateEnvoiEmail, $idprojet[0]);
    }
}
header('location:/'.REPERTOIRE.'/relance/' . $lang );



BD::deconnecter();
