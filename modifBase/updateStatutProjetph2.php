<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../decide-lang.php';
include_once '../outils/constantes.php';
include_once '../class/Manager.php';
include_once '../class/Securite.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_GET['page_precedente']) && $_GET['page_precedente'] == 'VueSuiviTousProjet') {
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $_GET['numProjet']);
    $idutilisateur = $_SESSION['idutilisateur'];
    $idcentrale = $manager->getSingle2("select idcentrale_centrale from utilisateur where idutilisateur =?", $idutilisateur);

    $commentaire = $manager->getSingle2("select commentaire projet from concerne where idprojet=?", $idprojet);
    if (empty($commentaire)) {
        $commentaire = '';
    }
    $typeprojet = $manager->getSingle2("select idtypeprojet_typeprojet from projet where idprojet=? ", $idprojet);
    $duree = $manager->getSingle2("select dureeprojet from projet where idprojet=?", $idprojet);
    $concerne = new Concerne($idcentrale, $idprojet, ENCOURSREALISATION, $commentaire);
    $manager->updateConcerne($concerne, $idprojet); //mise à jour du statut du projet
    include '../EmailProjetEncoursrealisation.php';
    header('Location: /' . REPERTOIRE . '/controler/controlerapidetousprojets.php?lang=' . $lang);

    BD::deconnecter();
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}