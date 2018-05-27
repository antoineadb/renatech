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
$videCache = new Cache(REP_ROOT . '/cache' . LIBELLECENTRALEUSER , 1);

if (isset($_POST) && !empty($_POST)) {
    if (isset($_POST['donnee']) && $_POST['donnee'] == 'FALSE') {
        //UNIQUEMENT LES DONNEES    
        $videCache->delete('tous_'.LIBELLECENTRALEUSER);
        $videCache->delete('encours_'.LIBELLECENTRALEUSER);
        $videCache->delete('finis_'.LIBELLECENTRALEUSER);        
        $videCache->delete('rapportAutres_'.LIBELLECENTRALEUSER);
        $videCache->delete('rapportEnCours_'.LIBELLECENTRALEUSER);
        $videCache->delete('accepte_'.LIBELLECENTRALEUSER);
        $videCache->delete('attente_'.LIBELLECENTRALEUSER);        
        $videCache->delete('soustraitance_'.LIBELLECENTRALEUSER);
        $videCache->delete('refuse_'.LIBELLECENTRALEUSER); 
        $videCache->delete('analyse_'.LIBELLECENTRALEUSER);        
        $videCache->delete('cloture_'.LIBELLECENTRALEUSER);
        header('Location: /' . REPERTOIRE . '/videCache/' . $lang . '/d');
        
    } else {
        $videCache->clear();        
        header('Location: /' . REPERTOIRE . '/videCache/' . $lang . '/a');
        //TOUS LE CACHE
    }
} else {
    
        $videCache->delete('tous_'.LIBELLECENTRALEUSER);
        $videCache->delete('encours_'.LIBELLECENTRALEUSER);
        $videCache->delete('finis_'.LIBELLECENTRALEUSER);        
        $videCache->delete('rapportAutres_'.LIBELLECENTRALEUSER);
        $videCache->delete('rapportEnCours_'.LIBELLECENTRALEUSER);
        $videCache->delete('accepte_'.LIBELLECENTRALEUSER);
        $videCache->delete('attente_'.LIBELLECENTRALEUSER);        
        $videCache->delete('soustraitance_'.LIBELLECENTRALEUSER);
        $videCache->delete('refuse_'.LIBELLECENTRALEUSER); 
        $videCache->delete('analyse_'.LIBELLECENTRALEUSER);        
        $videCache->delete('cloture_'.LIBELLECENTRALEUSER);    header('Location: /' . REPERTOIRE . '/controler/controleSuiviProjetRespCentrale.php?lang='.$lang);
}
BD::deconnecter();