<?php
include_once '../class/Manager.php';

$db = BD::connecter();
$manager = new Manager($db);


$nom_Id= array
(
    array("nom"=>"Laur"),array("nom"=>"Leclerc"),array("nom"=>"Leichle"),array("nom"=>"Levallois"),array("nom"=>"Llopis"),array("nom"=>"LOHIER"),array("nom"=>"LOMBEZ"),array("nom"=>"Mangeat"),
    array("nom"=>"Marcoux"),array("nom"=>"MARIE"),array("nom"=>"Mazenq"),array("nom"=>"Menini"),array("nom"=>"MORILLON"),array("nom"=>"Nicu"),array("nom"=>"Parra"),array("nom"=>"Pasquet"),array("nom"=>"Pech"),
    array("nom"=>"PELLION"),array("nom"=>"PEMPIE"),array("nom"=>"PERILHON"),array("nom"=>"Perrossier"),array("nom"=>"Perrot"),array("nom"=>"PLOURABOUE"),array("nom"=>"Pons"),array("nom"=>"Presmanes"),
    array("nom"=>"Prudkovki"),array("nom"=>"REBIERE"),array("nom"=>"Respaud"),array("nom"=>"RESSIER"),array("nom"=>"RIBER"),array("nom"=>"Rossi"),array("nom"=>"SAADAOUI"),array("nom"=>"saias"),
    array("nom"=>"SALUT"),array("nom"=>"Séguy"),array("nom"=>"Solihi"),array("nom"=>"SUAREZ"),array("nom"=>"Sudor"),array("nom"=>"TABERNA"),array("nom"=>"Taliercio"),array("nom"=>"TASSELLI"),array("nom"=>"Temple"),
    array("nom"=>"Teodor"),array("nom"=>"Thibault"),array("nom"=>"TOIMIL-MOLARES"),array("nom"=>"Tremouilles"),array("nom"=>"Vieu"),array("nom"=>"Villeneuve"),array("nom"=>"VIOLLEAU")
);



for ($i = 0; $i < count($nom_Id); $i++) {
    //echo 'nom => '.$nom_Id[$i]['nom'].' '.$manager->getSingle2("select idutilisateur from utilisateur where nom =?",$nom_Id[$i]['nom']).'<br>';    
    $idnom = $manager->getSingle2("select idutilisateur from utilisateur where nom =?",$nom_Id[$i]['nom']);
    if(empty($idnom)){
        echo 'nom sans id => '.$nom_Id[$i]['nom'].'<br>';
        
    }
    
}














 

BD::deconnecter();