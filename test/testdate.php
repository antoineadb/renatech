<?php

$datedepart = strtotime('1965-11-18');
$duree = 255;
$dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
$dureeproche = ($duree * 30) - 15;
$dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
$annee = (int) date('Y', strtotime($dateFinproche));
if ($annee > 1970) {
    echo 'dans la boucle';
}else{
    echo 'Pas dans la boucle';
}

