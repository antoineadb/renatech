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
    
    if(isset($_POST['adresseSite']) && !empty($_POST['adresseSite'])){
        $adressesitewebcentrale = $_POST['adresseSite'];
    }else{
        $adressesitewebcentrale = '';
    }
    if(isset($_POST['nomCentrale']) && !empty($_POST['nomCentrale'])){
        $refsiteweb=$_POST['nomCentrale'];
    }else{
        $refsiteweb="";
    }
    if(isset($_FILES['fileCentrale']['name']) && !empty($_FILES['fileCentrale']['name'])){
        $nomLogo ='styles/img/logoCentrales/'. $_FILES['fileCentrale']['name'];
    }else{
        $nomLogo = "";
    } 
    $nb = $manager->getSingle2("select count(refsiteweb) from sitewebapplication where refsiteweb=?", $refsiteweb);
    if($nb==0){
        $sitewebapplication = new Sitewebapplication($refsiteweb, $adressesitewebcentrale, $nomLogo);
        $manager->addSiteWebApplication($sitewebapplication);
    }else{
        $sitewebapplication = new Sitewebapplication($refsiteweb, $adressesitewebcentrale, $nomLogo);
        $manager->updatesitewebApplication($sitewebapplication, $refsiteweb);
    }
    $cache->clear();
    BD::deconnecter();
    header('location: /' . REPERTOIRE . '/Manage_label3/' . $lang . '/msgupdatesiteweb');
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}