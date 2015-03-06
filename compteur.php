<?php

include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
showError($_SERVER['PHP_SELF']);
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
    $cfirstvisit = null;
    $clastvisit = $date;
    $ctotal = 0;
    $login = 'temoin';
    $ctime = null;
    $compteur = new Compteur($cfirstvisit, $clastvisit, $ctotal, $login, $ctime);
    $manager->updatecompteur($compteur, $login);
    //$exceed = $now - ($keep * 60 * 60);
    //$cpt = new Compteur(null, $exceed, $date, null, 'temoin',null);    
    //$manager->deletecompteur($cpt);
}

/* * ************************
 * Traitement des visites *
 * ************************ */
$idlogin = $manager->getSingle2("select max(c_id) from compteur where c_login like ? ", $_SESSION['pseudo'] . '%');
if(!empty($idlogin)){
    //$loginconnect = $manager->getSinglebyArray("select max(c_login) from compteur where c_login like ? and c_firstvisit =?", array($_SESSION['pseudo'] . '%', $date));
    $loginconnect = $manager->getSingle2("select c_login from compteur where c_id =?",$idlogin);
}else{
    $loginconnect=null;
}
if (empty($loginconnect)) {
    $loginconnect = $_SESSION['pseudo'] . '_0';
} else {
    $arrayindice = explode('_', $loginconnect);
    $indice = +$arrayindice['1'];
    $loginconnect = $_SESSION['pseudo'] . '_' . ($indice + 1);
}
$ip = $loginconnect;
    $heure = date("H:i");
    $compteur = new Compteur($date, $date, 1, $ip, $heure);
    $manager->addcompteur($compteur);
    $manager->exeRequete("UPDATE compteur SET c_total = c_total+1 WHERE c_login = 'temoin'");

// Nombre de visites total
$c_alltime = $manager->getSingle2("SELECT c_total FROM compteur WHERE c_login =?", 'temoin');

// Nombres de visiteurs quotidiens
$c_today = $manager->getSingle2("SELECT SUM(c_total) AS c_total FROM compteur WHERE c_login !=?", 'temoin');

// Nombre de visiteurs en ligne
$lastmin = $now - ($interval * 60);
$c_online = $manager->getSinglebyArray("SELECT COUNT(*) FROM compteur WHERE (c_login !=? AND extract(epoch from(c_firstvisit)) >=?)", array('temoin', $lastmin));