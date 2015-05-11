<?php

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (strlen($_GET['id']) > 3) {
    $id = (int) substr($_GET['id'], 2, 2);
} else {
    $id = (int) substr($_GET['id'], 2, 1);
}

$centrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $id);
echo $centrale;
