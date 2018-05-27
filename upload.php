<?php

include_once 'decide-lang.php';
include_once 'outils/constantes.php';
include_once 'class/Manager.php';
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$dossier = '../upload/';
if (isset($_GET['idprojet'])) {
    $idProjet = $_GET['idprojet'];
} else {
    $idProjet = $_SESSION['idprojet'];
}
//Début des vérifications de sécurité...
$fichier = basename($_FILES['fichierProjet']['name']);
$taille_maxi = 1048576;
$taille = filesize($_FILES['fichierProjet']['tmp_name']);
$extensions = array('.pdf', '.PDF');
$extension = strrchr($_FILES['fichierProjet']['name'], '.');
if ((empty($_FILES['fichierProjet']['name']))) {// pas de fichier sélectionné
     header('location: /' . REPERTOIRE . '/waitingproject/' . $lang . '/' . $idProjet);
} else if (!in_array($extension, $extensions)) {
    $erreur = TXT_ERREURUPLOAD;
    header('location:/' . REPERTOIRE . '/Upload_Error/' . $lang . '/' . rand(0,100000) . '/' . $idProjet . '/' . ENATTENTE . '');
} else if ($taille > $taille_maxi) {
    $erreur = TXT_ERREURTAILLEFICHIER;
    header('location:/' . REPERTOIRE . '/Upload_Error_Size/' . $lang . '/' . rand(0,100000) . '/' . $idProjet . '/' . ENATTENTE . '');
} else if (!isset($erreur)) {//S'il n'y a pas d'erreur, on upload
    if (move_uploaded_file($_FILES['fichierProjet']['tmp_name'], $dossier . $fichier)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné
        chmod($dossier . $fichier, 0777);
        header('location: /' . REPERTOIRE . '/waitingproject/' . $lang . '/' . $idProjet);
    }
}