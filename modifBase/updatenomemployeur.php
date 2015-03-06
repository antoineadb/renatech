<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (empty($_POST['idnomemployeuractuel'])) {
    header('location:/'.REPERTOIRE.'/update_employeurErr1/' . $lang . '/TXT_MESSAGEERREUREMPLOYEURSELECT');
    exit;
} else {
    $idemployeur = $_POST['idnomemployeuractuel'];
}
if (empty($_POST['modifnomemployeur'])) {
    header('location:/'.REPERTOIRE.'/update_employeurErr2/' . $lang . '/TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE');
    exit;
}
if (empty($_POST['modifnomemployeuren'])) {
    header('location:/'.REPERTOIRE.'/update_employeurErr2/' . $lang . '/TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE');
    exit;
} else {
    $modifnomemployeur = stripslashes(Securite::bdd($_POST['modifnomemployeur']));
    $modifnomemployeuren = stripslashes(Securite::bdd($_POST['modifnomemployeuren']));
    $boolmasqueemployeur = $manager->getSingle2("select masquenomemployeur from nomemployeur where idemployeur=? ", $_POST['idnomemployeuractuel']);
    if ($boolmasqueemployeur == 1) {
        $boolmasqueemployeur = 'TRUE';
    } else {
        $boolmasqueemployeur = 'FALSE';
    }
    if ($lang == 'fr') {
        $nomEmployeur = new Nomemployeur($idemployeur, $modifnomemployeur, $boolmasqueemployeur, $modifnomemployeuren);
    } elseif ($lang == 'en') {
        $nomEmployeur = new Nomemployeur($idemployeur, $modifnomemployeuren, $boolmasqueemployeur, $modifnomemployeur);
    }
    $manager->updateNomemployeur($nomEmployeur, $idemployeur);
    header('location:/'.REPERTOIRE.'/update_employeur/' . $lang . '/TXT_MESSAGEEMPLOYEURUPDATE');
    exit;
}

BD::deconnecter();
?>