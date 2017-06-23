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

if (isset($_GET['idUser']) && !empty($_GET['idUser'])) {
    $infoUser = $manager->getList2("select nom,prenom from utilisateur where idutilisateur=?", $_GET['idUser']);
    //Recupération des projets éventuels créé par l'utilisateur
    $projetsCreer = $manager->getList2("select idprojet_projet from creer where idutilisateur_utilisateur=?", $_GET['idUser']);
    $projetsAdmin = $manager->getList2("select idprojet from utilisateurAdministrateur where idutilisateur=?", $_GET['idUser']);
    $projetsPorteur = $manager->getList2("select idprojet_projet from utilisateurporteurprojet where idutilisateur_utilisateur=?", $_GET['idUser']);
    if(!empty($projetsCreer)){
        $status='creer';
        createLogInfo(NOW, "Tentative de suppression de l'utilisateur  " . $infoUser[0]['nom'].' '. $infoUser[0]['prenom'] . " sans succés.  ", null, null, $manager, IDCENTRALEUSER);
    }elseif (!empty($projetsAdmin)){
        $status='admin';
        createLogInfo(NOW, "Tentative de suppression de l'utilisateur  " . $infoUser[0]['nom'].' '. $infoUser[0]['prenom'] . " sans succés.  ", null, null, $manager, IDCENTRALEUSER);
    }elseif(!empty($projetsPorteur)){
        $status='porteur';
        createLogInfo(NOW, "Tentative de suppression de l'utilisateur  " . $infoUser[0]['nom'].' '. $infoUser[0]['prenom'] . " sans succés.  ", null, null, $manager, IDCENTRALEUSER);        
    }else{
        //Suppression de l'utilisateur
        $idlogin = $manager->getSingle2("select idlogin_loginpassword from utilisateur where idutilisateur=?", $_GET['idUser']);
        $manager->deleteIntervient($_GET['idUser']);
        $manager->deleteAppartient($_GET['idUser']);
        $manager->deleteUtilisateur($_GET['idUser']);
        $manager->deleteLogin($idlogin);
        
        
        $status='ok';       
        createLogInfo(NOW, " l'utilisateur  " . $infoUser[0]['nom'].' '. $infoUser[0]['prenom'] . " a été  supprimé.  ", null, null, $manager, IDCENTRALEUSER);
    }
    BD::deconnecter();        
}

header('Location: /' . REPERTOIRE . '/delete_user/' . $lang . '/'.$status);



