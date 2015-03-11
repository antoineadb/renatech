<?php
//session_start();
include_once 'outils/constantes.php';
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} else {
    $idprojet = $_SESSION['idprojet'];
}

$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale	FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$numero = $manager->getSingle2('select numero from projet where idprojet=?', $idprojet);

if ($_POST['save'] == 'non') {//SAUVEGARDE ON NE TIENT PAS COMPTE DU STATUT
    if (isset($_POST['statutProjet'])) {
        $idstatutprojet = (int) substr($_POST['statutProjet'], 2, 1);
    } elseif (isset($_SESSION['idstatutprojet'])) {
        $idstatutprojet = (int) $_SESSION['idstatutprojet'];
    }
}elseif($_POST['save'] == 'oui'){
    $idstatutprojet =$manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
}
if (isset($_POST['etautrecentrale']) && !empty($_POST['etautrecentrale'])) {
    $etautrecentrale = Securite::bdd(trim($_POST['etautrecentrale']));
} else {
    $etautrecentrale = '';
}
$idautrecentrale = '';
if (isset($_POST['autrecentrale']) && !empty($_POST['autrecentrale'])) {
    for ($i = 0; $i < count($_POST['autrecentrale']); $i++) {
        $idautrecentrale.=$manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_POST['autrecentrale'][$i]) . ',';
    }
}
$idAutrecentrale = substr($idautrecentrale, 0, -1);
if ($cas == 'mise a jour') {
    header('Location: /' . REPERTOIRE . '/update_project2/' . $lang . '/' . $idprojet . '/' . $idstatutprojet . '/' . $_POST['nombrePersonneCentrale']);
} elseif ($cas == 'mise a jourEmail') {
    header('Location: /' . REPERTOIRE . '/EmailProjephase2tMAJSession.php?lang=' . $lang . '&idprojet=' . $idprojet . '&statut=' . $idstatutprojet . '&nbpersonne=' . $_POST['nombrePersonneCentrale']);
} elseif ($cas == 'mise a jourEmailAutreEmail') {
    include 'EmailProjephase2tMAJ.php';
    header('Location: /' . REPERTOIRE . '/update_project2/' . $lang . '/' . $idprojet . '/' . $idstatutprojet . '/' . $_POST['nombrePersonneCentrale']);    
} elseif($cas=='creationprojetphase2etape' || $cas=='creerprojetphase2'){
    header('Location: /' . REPERTOIRE . '/EmailProjetphase2Session.php?lang=' . $lang . '&idprojet=' . $idprojet . '&idautrecentrale=' . $idAutrecentrale . '&statut=' . $idstatutprojet . '&nbpersonne=' . $_POST['nombrePersonneCentrale'] . '&etautrecentrale=' . $etautrecentrale);
}elseif ($cas == 'changement de statut') {
    if ($idstatutprojet == CLOTURE) {
        header('Location: /' . REPERTOIRE . '/closed_project/' . $lang . '/' . $idprojet . '/' . $idstatutprojet);
        exit();
    } elseif ($idstatutprojet != REFUSE) {
        header('Location: /' . REPERTOIRE . '/update_project2/' . $lang . '/' . $idprojet . '/' . $idstatutprojet . '/' . $_POST['nombrePersonneCentrale']);
        exit();
    } else {
        header('Location: /' . REPERTOIRE . '/RefusedProject/' . $lang . '/' . $idprojet . '/' . $numero . '/' . $idcentrale . '/' . rand(0, 100000));
        exit();
    }
}else {
    header('Location: /' . REPERTOIRE . '/update_project2/' . $lang . '/' . $idprojet . '/' . $idstatutprojet . '/' . $_POST['nombrePersonneCentrale']);
}