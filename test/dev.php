<?php

include_once '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
$db = BD::connecter();
$manager = new Manager($db);

$array = $manager->getList2("SELECT idprojet FROM projet,concerne WHERE idprojet = idprojet_projet and idcentrale_centrale =?",5);

for ($i = 0; $i < count($array); $i++) {
    $manager->getRequete("update projet set devtechnologique = true where idprojet=?", array($array[$i]['idprojet']));
}


$db = BD::deconnecter();

