<?php

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if(isset($_GET['date'])&&!empty($_GET['date']) && isset($_GET['idprojet']) && !empty($_GET['idprojet'])){
    $idprojet = intval($_GET['idprojet']);
    $newDate  = $_GET['date'];
    $datedemande  = $manager->getSingle2("select dateprojet from projet where idprojet =? ", $idprojet);
    $datestatutfini = $manager->getSingle2("select datestatutfini from projet where idprojet=? ", $idprojet);
    if(isset($datestatutfini) && !empty($datestatutfini)){
        if($newDate > $datedemande && $newDate <$datestatutfini){
            $newDateDebutProjet = new DateDebutProjet($idprojet, $newDate);
            $manager->updateDateDebutProjet($newDateDebutProjet, $idprojet);
            echo  'TRUE';
        }
    }elseif($newDate > $datedemande){            
            $newDateDebutProjet = new DateDebutProjet($idprojet, $newDate);
            $manager->updateDateDebutProjet($newDateDebutProjet, $idprojet);
            echo  'TRUE';
    }else{
        echo  'FALSE';
    }
        
}else{
    echo  'FALSE';
}
