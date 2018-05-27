<?php

include_once '../outils/constantes.php';
include_once '../class/Manager.php';
include_once '../class/secure/Cryptage.php';
$login = Cryptage::crypt($_POST['login']);
$mdp = Cryptage::crypt($_POST['password']);
$host = Cryptage::crypt($_POST['host']);
$port = Cryptage::crypt($_POST['port']);


$db = BD::connecter();
$manager = new Manager($db);

if($_POST['parametres']=='bdd'){
    $param = new Params("Paramètre de configuration de la base de donnée", $login, $mdp, $host, $port);
    $manager->updateParams($param, 1);
}elseif($_POST['parametres']=='msgs'){
    $param = new Params("Paramètre de configuration du serveur de messagerie", $login, $mdp, $host, $port);
    $manager->updateParams($param, 2);
}



BD::deconnecter();
header('Location: /' . REPERTOIRE . '/adminApplication/' . $lang . '/t');
