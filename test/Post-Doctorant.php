<?php

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER


 

//POST-DOCTORANT


$arrayIdPostDoc = array(
    array("idpersonne" => 659),
    array("idpersonne" => 5410),
    array("idpersonne" => 2554),
    array("idpersonne" => 659),
    array("idpersonne" => 6032),
    array("idpersonne" => 162),
    array("idpersonne" => 176),
    array("idpersonne" => 655),
    array("idpersonne" => 657),
    array("idpersonne" => 3299),
    array("idpersonne" => 3453),
    array("idpersonne" => 6480),
    array("idpersonne" => 6408),
    array("idpersonne" => 3942),
    array("idpersonne" => 5399),
    array("idpersonne" => 3708),
    array("idpersonne" => 3466),
    array("idpersonne" => 5191),
    array("idpersonne" => 4636),
    array("idpersonne" => 3308),
    array("idpersonne" => 2797),
    array("idpersonne" => 4765),
    array("idpersonne" => 3592),
    array("idpersonne" => 3778),
    array("idpersonne" => 2993),
    array("idpersonne" => 4860),
    array("idpersonne" => 5357),
    array("idpersonne" => 3816),
    array("idpersonne" => 3215),
    array("idpersonne" => 5118),
    array("idpersonne" => 3703),
    array("idpersonne" => 5692),
    array("idpersonne" => 5667),
    array("idpersonne" => 5623),
    array("idpersonne" => 3884),
    array("idpersonne" => 4326),
    array("idpersonne" => 3830),
    array("idpersonne" => 5386),
    array("idpersonne" => 3505),
    array("idpersonne" => 3745),
    array("idpersonne" => 3785),
    array("idpersonne" => 2269),
    array("idpersonne" => 4855),
    array("idpersonne" => 5362),
    array("idpersonne" => 6511),
    array("idpersonne" => 5451),
    array("idpersonne" => 6517),
    array("idpersonne" => 3599),
    array("idpersonne" => 5417),
    array("idpersonne" => 5668),
    array("idpersonne" => 2373),
    array("idpersonne" => 5273),
    array("idpersonne" => 3832),
    array("idpersonne" => 4107),
    array("idpersonne" => 6509),
    array("idpersonne" => 6510),
    array("idpersonne" => 6411),
    array("idpersonne" => 5870),
    array("idpersonne" => 5884),
    array("idpersonne" => 5735),
    array("idpersonne" => 5659),
    array("idpersonne" => 5126),
    array("idpersonne" => 6110),
    array("idpersonne" => 5537),
    array("idpersonne" => 2655),
    array("idpersonne" => 3511),
    array("idpersonne" => 3785),
    array("idpersonne" => 5887),
    array("idpersonne" => 5405),
    array("idpersonne" => 4086),
    array("idpersonne" => 2825),
    array("idpersonne" => 4536),
    array("idpersonne" => 4415),
    array("idpersonne" => 4595),
    array("idpersonne" => 4223),
    array("idpersonne" => 6245),
    array("idpersonne" => 6507)
);


for ($i = 0; $i < count($arrayIdPostDoc); $i++) {
    $manager->getRequete("update personneaccueilcentrale set idpersonnequalite = 3 where idpersonneaccueilcentrale=?", array($arrayIdPostDoc[$i]['idpersonne']));
    $manager->getRequete("update personneaccueilcentrale set idqualitedemandeuraca_qualitedemandeuraca = 3 where idpersonneaccueilcentrale=?", array($arrayIdPostDoc[$i]['idpersonne']));    
}
BD::deconnecter();