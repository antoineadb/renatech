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
if ($idutilisateur > 0 && $idprojet > 0) {
    //INSERTION DANS LA TABLE UTILISATEURPORTEURPROJET
    date_default_timezone_set('Europe/London');
    $dateaffectation = date("Y-m-d");
    //CONTROLE SI LE PROJET N'A PAS DEJA ETE AFFECTE AU PORTEUR
    $datedejaaffect = $manager->getSinglebyArray("select dateaffectation from utilisateurporteurprojet where idutilisateur_utilisateur=? and idprojet_projet=?", array($idutilisateur, $idprojet));
    if (empty($datedejaaffect)) {
        $utilisateurporteurprojet = new UtilisateurPorteurProjet($idutilisateur, $idprojet, $dateaffectation);        
        $manager->addUtilisateurPorteurProjet($utilisateurporteurprojet);
         include_once '../outils/creerJsonProjetCentrale.php';//MISE A JOUR DU FICHIER JSON
//ENVOI DE L'EMAIL
        $statutprojet = $manager->getSingle2("select libellestatutprojet from concerne,statutprojet where idprojet_projet=? and idstatutprojet=idstatutprojet_statutprojet", $idprojet);
        $centrale= $manager->getSingle2("select idcentrale_centrale from concerne,centrale where idprojet_projet=? and idcentrale_centrale=idcentrale", $idprojet);
        $numero = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
        $nomprenomdemandeur = $manager->getSingle2("select concat (nom,' - ',prenom) from utilisateur where idutilisateur=?", $idutilisateur);
            //Créer un log
            createLogInfo(NOW, "Affectation du projet n° ".$numero." en tant que porteur à l'utilisateur ", $nomprenomdemandeur." ", $statutprojet, $manager, $centrale);
        include	'../EmailProjetAffecte.php';       
        if(!empty($_GET['idprojet'])) {            
            //header('Location:/'.REPERTOIRE.'/projet_centrale_affect/' . $lang . '/' . $idprojet.'/'.$idutilisateur.'/ok' ); 
            //vider le cache
            effaceCache(LIBELLECENTRALEUSER);    
            header('Location:/' . REPERTOIRE . "/controler/controleSuiviProjetRespCentrale.php?lang=" . $lang . "&idprojet=".$idprojet."&idutilisateur=".$idutilisateur."&porteur=ok");
            exit();            
        }else{
            header('Location:/'.REPERTOIRE.'/affecte_projet/' . $lang . '/' . $idutilisateur );        
        }
    } else {
        if(!empty($_GET['idprojet'])) {
            header('Location:/'.REPERTOIRE.'/projet_deja_affecte/' . $lang . '/' . $idutilisateur.'/ok');   
            exit();
        }else{                        
            header('Location:/'.REPERTOIRE.'/projet_affecte/' . $lang . '/' . $idutilisateur . '/ok');
            exit();
        }
    }
} else {
    header('Location:/'.REPERTOIRE.'/projet_affecteerreur/' . $lang . '/messageerreur');
    exit();
}