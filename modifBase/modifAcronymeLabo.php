<?php

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if(isset($_GET['acronyme'])&&!empty($_GET['acronyme'])){
    $acronymelaboratoire = $_GET['acronyme'];
    $iddemandeur = $_GET['iddemandeur'];
    $userAcronyme = new UtilisateurAcronymelabo($acronymelaboratoire, $iddemandeur);    
    $manager->updateAcronymelaboratoire($userAcronyme,$iddemandeur);
    echo  'TRUE';
}else {
    echo 'FALSE';    
}
