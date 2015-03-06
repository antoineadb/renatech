<?php

include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
// Existence du fichier config.inc.php
$db_table = 'compteur'; // nom de la table qui sera créée à l'installation
// Paramètres du compteur
$keep = 360; // durée de conservation (en heures) des IPs en base (défaut : 10 jours 24*15)
$interval = 1; // intervalle de temps (en minutes) pour compter le nombre de connectés des X dernières minutes (défaut : 1 minutes)
$unique = 2; // durée (en heures) pendant laquelle une IP est comptée comme unique (défaut : 2h)
$initial = 0; // nombre initial de visiteurs au compteur
$exclude = array(); // liste des IPs (partielles ou complètes) à ne pas comptabiliser. Ex : array('127.0.0.1', '168.254.')
// Connexion BDD
$db = BD::connecter();
$manager = new Manager($db);
// Récupération des données témoins
$data = $manager->getList2("SELECT c_lastvisit, c_total  FROM compteur  WHERE c_login = ?", 'temoin');
$total = $data[0]['c_total'];
// Nombre de visites total
// Dernier jour traité
$saved_date = $data[0]['c_lastvisit'];
// Aujourd'hui et maintenant
$today = date('Y-m-d');

$date = date('Y-m-d');
$now = time();

// Si changement de jour
if (($today != $saved_date)) {
// Le nombre de visites de chaque visiteur de la base est remis à 0
    $compteur = new Compteur(null, $date, 0, null, 'temoin');
    $manager->updatecompteur($compteur, 'temoin');
    $exceed = $now - ($keep * 60 * 60);
    $cpt = new Compteur(null, $exceed, $date, null, null, 'temoin');
    $manager->deletecompteur($cpt);
}
/* * *********************************
 * Fonction de vérification des IP *
 * ********************************* */

// Fonction qui vérifie si l'IP est exclue du comptage ou pas
function ipcheck($ip_to_match, $ip_array) {
    if (is_array($ip_array)) {
        foreach ($ip_array as $ip) {
            if (strpos($ip_to_match, $ip) === 0) {
                return true;
            }
        }
    }
    return false;
}

/* * ************************
 * Traitement des visites *
 * ************************ */

$loginconnect = $manager->getSinglebyArray("select max(c_login) from compteur where c_login like ? and c_lastvisit =?", array($_SESSION['pseudo'] . '%', $date));
if (empty($loginconnect)) {
    $loginconnect = $_SESSION['pseudo'] . '_0';
} else {
    $arrayindice = explode('_', $loginconnect);
    $indice = +$arrayindice['1'];
    $loginconnect = $_SESSION['pseudo'] . '_' . ($indice + 1);
}
$ip = $loginconnect;
// On compte le nombre d'entrées correspondant à l'IP de notre visiteur
//$match = $manager->getSingle2("select count(*) from compteur where c_login =?", $ip);
// Si aucune IP ne correspond, le visiteur est nouveau dans la base de données
//if ($match == 0) {
    $heure = date("H:i");    
    $compteur = new Compteur($date, $date, 1, $ip, $heure);
    $manager->addcompteur($compteur);
    $manager->exeRequete("UPDATE compteur SET c_total = c_total+1 WHERE c_login = 'temoin'");
/*}
else {
    // On récupère toutes les données qui lui correspondent        
    //$firstvisit = $manager->getSingle2("SELECT extract(epoch from(c_firstvisit)) AS c_firstvisit FROM compteur WHERE c_login =?", $ip);
    // Si la période est dépassée
    //if (($now - $firstvisit) > ($unique * 60 * 60)) {
        // Incrémentation du compteur total
    $heure = date("H:i");    
    $compteur = new Compteur($date, $date, 1, $ip, $heure);
    $manager->addcompteur($compteur);
        $manager->exeRequete("UPDATE compteur SET c_total = c_total+1 WHERE c_login = 'temoin'");
    //}
}*/
/* * **********************
 * Stockage des données *
 * ********************** */

// Nombre de visites total
$c_alltime = $manager->getSingle2("SELECT c_total FROM compteur WHERE c_login =?", 'temoin');

// Nombres de visiteurs quotidiens
$c_today = $manager->getSingle2("SELECT SUM(c_total) AS c_total FROM compteur WHERE c_login !=?", 'temoin');

// Nombre de visiteurs en ligne
$lastmin = $now - ($interval * 60);
$c_online = $manager->getSinglebyArray("SELECT COUNT(*) FROM compteur WHERE (c_login !=? AND extract(epoch from(c_firstvisit)) >=?)", array('temoin', $lastmin));