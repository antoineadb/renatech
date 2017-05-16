<?php

session_start();
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_GET['action']) && !empty($_GET['action'])) {    
    $requete = $manager->getList("select * from demande_faisabilite");    
    for ($i = 0; $i < count($requete); $i++) {
        var_dump($requete);
    }
} 
