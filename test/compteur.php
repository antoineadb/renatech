<?php

include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
include_once '../class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$date = date('Y-m-d');
$loginconnect = $manager->getSinglebyArray("select max(c_login) from compteur where c_login like ? and c_firstvisit =?", array('thierry.chevolleau' . '%', $date));
echo 'loginconnect ='.$loginconnect;
if (empty($loginconnect)) {
    $loginconnect = $_SESSION['pseudo'] . '_0';
    echo '$loginconnect = '.$loginconnect;
} else {
    $arrayindice = explode('_', $loginconnect);
    echo '<pre>';print_r($arrayindice);echo '</pre>';
    $indice = +$arrayindice['1'];
    $loginconnect ='thierry.chevolleau'. '_' . ($indice + 1);
    echo '$loginconnect else = '.$loginconnect;
}