<?php
include_once '../class/Manager.php';
$dossier = '../uploadlogo/';
$db = BD::connecter();
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$db = BD::connecter();

$idprojet = $_GET['idprojet'];
// On efface le fichiier si il n'est pas utilisé dans un autre rapport
$figure = $manager->getSingle2("SELECT figure FROM rapport WHERE idprojet=?", $idprojet);
// Vérification que la figure n'est pas déjka utilisé ailleur
$figures =$manager->getSingle2("SELECT count(idrapport) FROM rapport  WHERE figure =?", trim($figure));

if($figures==1){//alors on supprime la figure et on efface le lien dans la BD
    $idrapport = $manager->getSingle2("SELECT idrapport  FROM rapport WHERE idprojet=?", $idprojet);
    $manager->getRequete("update rapport set figure =NULL where idrapport =?", array($idrapport));    
    if(unlink('../uploadlogo/'.$figure)){
        echo "suppression ok";
    }else{
        echo "suppression KO";
    }
}
