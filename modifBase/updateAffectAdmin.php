<?php

session_start();

if (is_file('../outils/toolBox.php')) {
    include_once '../outils/toolBox.php';
} elseif (is_file('outils/toolBox.php')) {
    include_once 'outils/toolBox.php';
}
showError($_SERVER['PHP_SELF']);
if (is_file('../outils/constantes.php')) {
    include_once '../outils/constantes.php';
} elseif (is_file('outils/constantes.php')) {
    include_once 'outils/constantes.php';
}
if (is_file('../decide-lang.php')) {
    include_once '../decide-lang.php';
} elseif (is_file('decide-lang.php')) {
    include_once 'decide-lang.php';
}
include_once '../class/Manager.php';

$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {    
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}

if (isset($_GET['idutilisateur'])) {
    $idutilisateur = $_GET['idutilisateur'];
}
if (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
}elseif (isset($_SESSION['idprojet'])) {
    $idprojet = $_SESSION['idprojet'];
}

if (!empty($idutilisateur) && !empty($idprojet)) {
    //INSERTION DANS LA TABLE UTILISATEURPORTEURPROJET
    date_default_timezone_set('Europe/London');
    $dateaffectation = date("Y-m-d");
    //CONTROLE SI LE PROJET N'A PAS DEJA ETE AFFECTE AU PORTEUR
    $datedejaaffect = $manager->getSinglebyArray("select dateaffectation from utilisateuradministrateur where idutilisateur=? and idprojet=?", array($idutilisateur, $idprojet));
    if (empty($datedejaaffect)) {
        $utilisateuradminprojet = new UtilisateurAdmin($idutilisateur, $idprojet, $dateaffectation);
        $manager->addUtilisateurAdmin($utilisateuradminprojet);
//ENVOI DE L'EMAIL        
        //include	'../EmailProjetAffecte.php';// DEMANDE A FAIRE  POUR ENVOYER OU NON UN EMAIL
        if(!empty($_GET['idprojet'])) {            
            header('Location:/'.REPERTOIRE.'/projet_centrale_admin/' . $lang . '/' . $idprojet.'/'.$idutilisateur.'/ok' );            
            exit();            
        }else{
            header('Location:/'.REPERTOIRE.'/affecte_AdminProjet/' . $lang . '/' . $idutilisateur );            
            exit();
        }
    } else {
         if(!empty($_GET['idprojet'])) {
            header('Location:/'.REPERTOIRE.'/projet_deja_admin/' . $lang . '/' . $idutilisateur.'/ok');   
            exit();
        }else{
            header('Location:/'.REPERTOIRE.'/projet_adminaffecte/' . $lang . '/' . $idutilisateur . '/ok');
            exit();
        }
    }
} else {
    header('Location:/'.REPERTOIRE.'/projet_affecteerreur/' . $lang . '/messageerreur');
    exit();
}