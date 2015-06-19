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
$idcentrale = $manager->getSingle2("select idcentrale_centrale from utilisateur,loginpassword where idlogin_loginpassword=idlogin and pseudo=?", $_SESSION['pseudo']);
$arrayIdProjetToDelete = $manager->getList2("SELECT idprojet FROM  projet,concerne WHERE idprojet_projet = idprojet and trashed =TRUE and idcentrale_centrale =? ", $idcentrale);

for ($i = 0; $i < count($arrayIdProjetToDelete); $i++) {
    $manager->deleteconcerneprojet($arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deletecreerprojet($arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deleteprojetsourcefinancement($arrayIdProjetToDelete[$i]['idprojet']);
    $arrayidpersonneaccueilcentrale = $manager->getList2("select idpersonneaccueilcentrale_personneaccueilcentrale from projetpersonneaccueilcentrale where idprojet_projet=?", $arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deleteprojetpersonneaccueilcentrale($arrayIdProjetToDelete[$i]['idprojet']);
    $nbid = count($arrayidpersonneaccueilcentrale);
    if ($nbid > 0) {
        for ($j = 0; $j < $nbid; $j++) {
            $manager->deletepersonnesaccueilcentrale($arrayidpersonneaccueilcentrale[$j]['idpersonneaccueilcentrale_personneaccueilcentrale']);
        }
    }
    //PROJETPARTENAIRE
    $arrayidprojetpartenaire = $manager->getList2("select idpartenaire_partenaireprojet from projetpartenaire where idprojet_projet=?", $arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deleteprojetpartenaire($arrayIdProjetToDelete[$i]['idprojet']);
    $nbidpartneaire = count($arrayidprojetpartenaire);
    if ($nbidpartneaire > 0) {
        for ($k = 0; $k < $nbidpartneaire; $k++) {
            $manager->deletepartenaireprojets($arrayidprojetpartenaire[$k]['idpartenaire_partenaireprojet']);
        }
    }
    //-----------------------------------
    $manager->deleteprojetautrecentrale($arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deleteressourceprojet($arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deleteUtilisateurAdministrateur($arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deleteUtilisateurPorteur($arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deleterapport($arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deleteprojet($arrayIdProjetToDelete[$i]['idprojet']);
    $manager->deleteautrethematique($arrayIdProjetToDelete[$i]['idprojet']);
}
header('Location: /' . REPERTOIRE . '/delete_projet/' . $lang );