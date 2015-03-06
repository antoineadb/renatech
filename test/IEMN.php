<?php

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER




$test =
array(
array("idutilisateur"=>507,"idprojet"=>1264),
array("idutilisateur"=>875,"idprojet"=>1121),
array("idutilisateur"=>941,"idprojet"=>1207),
array("idutilisateur"=>847,"idprojet"=>1186),
array("idutilisateur"=>120,"idprojet"=>1138),
array("idutilisateur"=>497,"idprojet"=>1224),
array("idutilisateur"=>807,"idprojet"=>1230),
array("idutilisateur"=>509,"idprojet"=>785)
);

for ($i = 0; $i < count($test); $i++) {
    $dateaffectation  = date('Y-m-d');
    $admin = new UtilisateurAdmin($test[$i]['idutilisateur'], $test[$i]['idprojet'], $dateaffectation);    
    echo '<pre>';print_r($admin);
    $manager->addUtilisateurAdmin($admin);
}