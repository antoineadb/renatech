<?php

function affiche($reflib) {
    if(is_file('../class/Manager.php')){
        include_once '../class/Manager.php';
    }elseif(is_file('class/Manager.php')){
        include_once 'class/Manager.php';
    }
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db);
    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
    } else {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }
    
    if ($lang === 'fr') {
        return $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $reflib);
    } elseif ($lang === 'en') {
        return $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $reflib);
    }
    BD::deconnecter(); //CONNEXION A LA BASE DE DONNEE
}
