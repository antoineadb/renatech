<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../decide-lang.php';
include '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
include_once '../class/Securite.php';;
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (empty($_POST['copiestatut'])) {
    header('location:../admin.php?lang=' . $lang . '&msgErrstatutselect=TXT_MESSAGEERREURSTATUTSELECT');
    exit;
} elseif (empty($_POST['modifstatuts'])) {
    header('location:../admin.php?lang=' . $lang . '&msgErrstatutnonsaisie=TXT_MESSAGEERREURSTATUTNONSAISIE');
    exit;
}
if (isset($_POST['copiestatut'])) {
    $copiestatut = stripslashes(Securite::bdd($_POST['copiediscipline']));
    $idStatut = $manager->getSingle2("SELECT iddstatut FROM statutprojet where libellestatut =?",$copiestatut);
}

if (isset($_POST['modifstatuts'])) {
    $modifStatut = stripslashes(Securite::bdd($_POST['modifstatuts']));
    $idStatut1 = $manager->getSingle2("SELECT idStatut FROM Statutscientifique Where libelleStatut = ?",$modifStatut);
    if (!empty($idStatut1)) {
        header('location:../admin.php?lang=' . $lang . '&msgErrstatutexiste=TXT_MESSAGESERVEURSTATUTEXISTE');
        exit;
    } else {
        $statut = new Statutprojet($idStatut, $modifStatut);
        $manager->updateStatut($statut, $idStatut);
        header('location:../admin.php?lang=' . $lang . '&msgStatutupdate=TXT_MESSAGEStatutUPDATE');
        exit;
    }
}
BD::deconnecter();
?>
