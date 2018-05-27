<?php

session_start();
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_GET['action']) && !empty($_GET['action'])) {
    if ($_SESSION['idTypeUser'] == ADMINLOCAL) {
        $logs = $manager->getList2("select id,dateheure,infos,nomprenom,statutprojet  from logs where idcentrale=? order by dateheure desc", IDCENTRALEUSER);
    } elseif ($_SESSION['idTypeUser'] == ADMINNATIONNAL) {
        $logs = $manager->getList("select id,dateheure,infos,nomprenom,statutprojet  from logs order by dateheure desc");
    }
    
    for ($i = 0; $i < count($logs); $i++) {
        $date = new DateTime();
        $date->setTimestamp($logs[$i]['dateheure']);
        if ($logs[$i][4] == '') {
            echo $date->format('d-m-Y, H:i:s') . ' : ' . $logs[$i][2] . ' : ' . $logs[$i][3] . '<hr>';
        } else {
            echo $date->format('d-m-Y, H:i:s') . ' : ' . $logs[$i][2] . ' : ' . $logs[$i][3] . ': statut : ' . $logs[$i][4] . '<hr>';
        }
    }
} 
