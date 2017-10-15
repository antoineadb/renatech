<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../class/Cache.php';
define('ROOT',  dirname(__FILE__));
$cache = new Cache(ROOT.'/cache', 60);

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestionsiteweb.html') {
    $db = BD::connecter();
    $manager = new Manager($db);
    include_once '../class/Securite.php';
    if(empty($_POST['modifcentraleProximiteValeurFr'])){
        $_POST['modifcentraleProximiteValeurFr'] = $manager->getSingle2("SELECT libellefrancais from libelleapplication where reflibelle=?", "TXT_AIDECENTRALEPROXIMITE");
    }
    if(empty($_POST['modifcentraleProximiteValeurEn'])){
        $_POST['modifcentraleProximiteValeurEn'] = $manager->getSingle2("SELECT libelleanglais from libelleapplication where reflibelle=?", "TXT_AIDECENTRALEPROXIMITE");
    }    
    $libellefrancais = $_POST['modifcentraleProximiteValeurFr'];
    $libelleanglais = $_POST['modifcentraleProximiteValeurEn'];
    $reflibelle ="TXT_AIDECENTRALEPROXIMITE";
    $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $reflibelle);
    $manager->updateLibelleApplication($libelleapplication, $reflibelle);

    $cache->clear();
    BD::deconnecter();
    header('location: /' . REPERTOIRE . '/Manage_label3/' . $lang . '/msgupdatesiteweb');
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}