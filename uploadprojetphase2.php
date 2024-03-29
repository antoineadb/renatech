<?php

include_once 'outils/constantes.php';
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} else {
    $idprojet = $_SESSION['idprojet'];
}

if (!empty($_POST['centrale'])) {
    $idCentrale = (int) substr($_POST['centrale'], 2);
} else {
    $idCentrale = AUTRECENTRALE;
}

if (isset($_POST['enregistre']) && $_POST['enregistre'] == 'oui') {
    $cas = 'enregistrement';
} elseif (isset($_POST['creerprojetphase2']) && $_POST['creerprojetphase2'] == 'oui' && $_POST['enregistre'] == 'non') {
    if (isset($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {
        $cas = 'creationprojetphase2etape';
    } else {
        $cas = 'creationprojetphase2';
    }
}
$idautrecentrale = '';
if (isset($_POST['autrecentrale']) && !empty($_POST['autrecentrale'])) {
    for ($i = 0; $i < count($_POST['autrecentrale']); $i++) {
        $idautrecentrale.=$manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_POST['autrecentrale'][$i]) . ',';
    }
}
$idAutrecentrale = substr($idautrecentrale, 0, -1);
if (isset($_POST['etautrecentrale']) && !empty($_POST['etautrecentrale'])) {
    $etautrecentrale = Securite::bdd(trim($_POST['etautrecentrale']));
} else {
    $etautrecentrale = '';
}
if (isset($_POST['integerspinner']) && !empty($_POST['integerspinner'])) {
    $nbpersonnecentrale = $_POST['integerspinner'];
} else {
    $nbpersonnecentrale = 0;
}
$numero = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
if ($cas == 'enregistrement') {//OK VALIDE    
    createLogInfo(NOW, TXT_CREATIONPROJETPHASE2 .' : '. $numero, NOMUSER . ' ' . PRENOMUSER .' : '. getCentrale($numero, $manager), TXT_ENATTENTEPHASE2, $manager,$idCentrale);
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nombrePersonneCentrale . '/' . $idCentrale);
} elseif ($cas == 'creationprojetphase2' || $cas == 'creationprojetphase2etape'  ) {    
    include 'EmailProjetphase2.php';
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nombrePersonneCentrale . '/' . $idCentrale);
} elseif ($cas == 'creerprojetphase2') {    
    include 'emailprojetphase2/creerphase2.php';
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nombrePersonneCentrale . '/' . $idCentrale);
    exit();
} else {
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nombrePersonneCentrale . '/' . $idCentrale);
}
BD::deconnecter();
