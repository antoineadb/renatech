<?php
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
//-------------------------------------------------------------------------------------------------------------
//                                          RECHERCHE    
//-------------------------------------------------------------------------------------------------------------
$arrayporteur = $manager->getList("select porteur from tmprecherche where porteur is not null");

$porteur='';
for ($i = 0; $i < count($arrayporteur); $i++) {
    $porteur .=$arrayporteur[$i]['porteur'].' / ';
}
$tmprecherche = new Tmprecherche(substr(trim($porteur),0,-1));
$manager->updateRecherche1($tmprecherche);

