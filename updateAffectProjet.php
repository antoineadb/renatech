<?php

session_start();
if (is_file('../outils/toolBox.php')) {
    include_once '../outils/toolBox.php';
} elseif (is_file('outils/toolBox.php')) {
    include_once 'outils/toolBox.php';
}
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
include_once 'class/Manager.php';

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
if (isset($_SESSION['idprojet'])) {
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
//ENVOI DE L'EMAIL        
        include	'EmailProjetAffecte.php';
        header('Location:/'.REPERTOIRE.'/affecte_projet/' . $lang . '/' . $idutilisateur .'/update/ok');
        exit();
    } else {
        header('Location:/'.REPERTOIRE.'/projet_dejaaffecte/' . $lang . '/' . $idutilisateur . '/messagedejaaffecte/ok');
        exit();
    }
} else {
    header('Location:/'.REPERTOIRE.'/projet_affecteerreur/' . $lang . '/messageerreur');
    exit();
}