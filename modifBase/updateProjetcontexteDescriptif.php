<?php

session_start();
include '../decide-lang.php';
include '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../class/Manager.php';
include '../class/Securite.php';
include_once '../outils/constantes.php';
//include_once	'../outils/donneeSession.php';exit();
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'vueModifProjet.html') {
    $idprojet = $_GET['idprojet'];
    $statut = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
//------------------------------------------------------------------------------------------------------------------
//																																																CONTEXTE
//------------------------------------------------------------------------------------------------------------------
    $context = str_replace('<br />', '', $manager->getSingle2("select contexte from projet where idprojet=?", $idprojet));
    if (!empty($_POST['contextValeur'])) {
        $contexte = filterEditor(Securite::bdd(trim($_POST['contextValeur'])));
    }
//------------------------------------------------------------------------------------------------------------------
//																																																DESCRIPTIF
//------------------------------------------------------------------------------------------------------------------
    $descr = str_replace('<br />', '', $manager->getSingle2("select description from projet where idprojet=? ", $idprojet));
    if (!empty($_POST['descriptifValeur'])) {
        $descriptif = filterEditor(Securite::bdd(trim($_POST['descriptifValeur'])));
    }
    if (!empty($_POST['acronyme'])) {
        $acronyme = $_POST['acronyme'];
    } else {
        $acronyme = $manager->getSingle2("select acronyme from projet where idprojet=? ", $idprojet);
    }
    if (!empty($_POST['titreProjet'])) {
        $titre = $_POST['titreProjet'];
    } else {
        $titre = $manager->getSingle2("select titre from projet where idprojet=? ", $idprojet);
    }

    if (!empty($_FILES['fichierProjet']['name'])) {
        $attachement = stripslashes(Securite::bdd($_FILES['fichierProjet']['name']));
    } else {
        $attachement = $manager->getSingle2("select attachement from projet where idprojet = ?", $idprojet);
        if (empty($attachement)) {
            $attachement = '';
        }
    }
    $confidentiel = $_POST['confid'];
//------------------------------------------------------------------------------------------------------------------
    $projetcontextedescriptif = new Projetcontextedescriptif($idprojet, $descriptif, $contexte, $confidentiel, $titre, $acronyme, $attachement);
    $manager->updateprojetcontextedescriptif($projetcontextedescriptif, $idprojet);
    //MISE A JOUR DE LA PIECE JOINTE
    $dossier1 = '../upload/';
    $fichierPhase1 = basename($_FILES['fichierProjet']['name']);
    $taille_maxi1 = 1048576;
    $taille1 = filesize($_FILES['fichierProjet']['tmp_name']);
    $extensions = array('.pdf', '.PDF');
    $extension1 = strrchr($_FILES['fichierProjet']['name'], '.');
    if (!empty($_FILES['fichierProjet']['name'])) {
        if (!in_array($extension1, $extensions)) {//VERIFICATION DU FORMAT SI IL N'EST PAS BON ON SORT
            $erreur1 = TXT_ERREURUPLOAD;
            header('Location: /' . REPERTOIRE . '/Upload_Errorphase1/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
            exit();
        } elseif ($taille1 > $taille_maxi1) {//VERIFICATION DE LA TAILLE SI ELLE EST >1mo ON SORT
            $erreur1 = TXT_ERREURTAILLEFICHIER;
            header('Location: /' . REPERTOIRE . '/Upload_Errorsizephase1/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
            exit();
        } elseif (!isset($erreur1)) {//S'il n'y a pas d'erreur, on upload
            if (move_uploaded_file($_FILES['fichierProjet']['tmp_name'], $dossier1 . $fichierPhase1)) {
                chmod($dossier1 . $fichierPhase1, 0777);
            }
        }
    }
    header('Location:/' . REPERTOIRE . '/update_project_phase2/' . $lang . '/' . $_GET['idprojet'] . '/' . $statut);
    exit();
    BD::deconnecter(); //DECONNEXION A LA BASE DE DONNEE
} else {
    include_once '../decide-lang.php';
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}