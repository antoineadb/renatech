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
if (isset($_GET['idprojet']) && !empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
    $idcentralelocal = $manager->getSingle2("select idcentrale_centrale from utilisateur,loginpassword where idlogin_loginpassword=idlogin and pseudo=?", $_SESSION['pseudo']);
    $numero = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
    $idcentrale = $manager->getList2("select idcentrale_centrale from concerne where idprojet_projet =?", $idprojet);
    if (count($idcentrale) > 1) {
        $manager->deleteConcerneProjetCentrale($idprojet, $idcentralelocal);
    } else {
        //RECUPERATION DE L'UTILISATEUR QUI A CREER LE PROJET
        $manager->deleteconcerneprojet($idprojet);
        $manager->deletecreerprojet($idprojet);
        $manager->deleteprojetsourcefinancement($idprojet);
        //CAS PARTICULIERS
        //PERSONNEACCUEIL CENTRALE
        $arrayidpersonneaccueilcentrale =$manager->getList2("select idpersonneaccueilcentrale_personneaccueilcentrale from projetpersonneaccueilcentrale where idprojet_projet=?", $idprojet);
        $nbid=count($arrayidpersonneaccueilcentrale);
        $manager->deleteprojetpersonneaccueilcentrale($idprojet);
        for ($i = 0; $i < $nbid; $i++) {
            $manager->deletepersonnesaccueilcentrale($arrayidpersonneaccueilcentrale[$i]['idpersonneaccueilcentrale_personneaccueilcentrale']);
        }
        //PROJETPARTENAIRE
        $arrayidprojetpartenaire =$manager->getList2("select idpartenaire_partenaireprojet from projetpartenaire where idprojet_projet=?", $idprojet);
        $nbidpartneaire=count($arrayidprojetpartenaire);
        $manager->deleteprojetpartenaire($idprojet);
        for ($i = 0; $i < $nbidpartneaire; $i++) {
            $manager->deletepartenaireprojets($arrayidprojetpartenaire[$i]['idpartenaire_partenaireprojet']);
        }
        //-----------------------------------
        $manager->deleteprojetautrecentrale($idprojet);
        $manager->deleteressourceprojet($idprojet);
        $manager->deleteUtilisateurAdministrateur($idprojet);
        $manager->deleteUtilisateurPorteur($idprojet);
        $manager->deleterapport($idprojet);
        $manager->deleteprojet($idprojet);
        $manager->deleteautrethematique($idprojet);
    }
    header('Location: /' . REPERTOIRE . '/delete_projet/' . $lang . '/' . $numero);
}
BD::deconnecter();
