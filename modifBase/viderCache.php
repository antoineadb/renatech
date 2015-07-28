<?php

session_start();
include '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../outils/constantes.php';
include_once '../decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include_once '../class/Cache.php';
$videCache = new Cache(REP_ROOT . '/cache', 1);
        


if(isset($_POST['donnee'])&& $_POST['donnee']=='FALSE'){
    //UNIQUEMENT LES DONNEES    
    $videCache->delete('tous');
    $videCache->delete('soustraitance');
    $videCache->delete('refuse');
    $videCache->delete('realisation');
    $videCache->delete('rapport');
    $videCache->delete('accepte');
    $videCache->delete('analyse');
    $videCache->delete('attente');
    $videCache->delete('cloture');
    $videCache->delete('finis');
    header('Location: /' . REPERTOIRE . '/videCache/' . $lang.'/d');
    
}else{
    $videCache->clear();
    header('Location: /' . REPERTOIRE . '/videCache/' . $lang.'/a');
    //TOUS LE CACHE
}
BD::deconnecter();


