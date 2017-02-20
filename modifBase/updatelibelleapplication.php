<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';

if (empty($_POST['idlibelleactuel'])) {
    header('location:../admin.php?lang=' . $lang . '&msgErrsecteurselect=TXT_MESSAGEERREURGESECTEURONSELECT');
    exit;
} else {
    $idlibelleactuel = $_POST['idlibelleactuel'];
}

if (empty($_POST['modiflibelleapplication'])) {
    header('location:../admin.php?lang=' . $lang . '&msgErrSecteurnonsaisie=TXT_MESSAGEERREURSECTEURNONSAISIE');
    exit;
}if (empty($_POST['modiflibelleapplicationen'])) {
    header('location:../admin.php?lang=' . $lang . '&msgErrSecteurnonsaisie=TXT_MESSAGEERREURSECTEURNONSAISIE');
    exit;
} else {
    $modiflibelleapplication = stripslashes(Securite::bdd($_POST['modiflibelleapplication']));
    $modiflibelleapplicationen = stripslashes(Securite::bdd($_POST['modiflibelleapplicationen']));   
BD::deconnecter();

}