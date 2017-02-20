<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
if (empty($_POST['modifthematique'])) {
    header('location:/'.REPERTOIRE.'/insert_thematiqueErr1/' . $lang . '/TXT_MESSAGEERREURTHEMATIQUENONSAISIE');
    exit;
}
if (empty($_POST['modifthematiqueen'])) {
    header('location:/'.REPERTOIRE.'/insert_thematiqueErr1/' . $lang . '/TXT_MESSAGEERREURTHEMATIQUENONSAISIE');
    exit;
} else {
    $modifthematique = stripslashes(Securite::bdd($_POST['modifthematique']));
    $modifthematiqueen = stripslashes(Securite::bdd($_POST['modifthematiqueen']));
    $idthematique = $manager->getSingle2("SELECT idthematique FROM thematique Where libellethematique =?", $modifthematique);
    if (!empty($idthematique)) {
        header('location:/'.REPERTOIRE.'/insert_thematiqueErr2/' . $lang . '/TXT_MESSAGESERVEURTHEMATIQUEEXISTE');
        exit;
    } else {
        $idnewthematique = $manager->getSingle("SELECT max(idthematique) FROM thematique;") + 1;

        if ($lang == 'fr') {
            $thematique = new Thematique($idnewthematique, $modifthematique, FALSE, $modifthematiqueen);
        } elseif ($lang == 'en') {
            $thematique = new Thematique($idnewthematique, $modifthematiqueen, FALSE, $modifthematique);
        }
        $manager->addThematique($thematique);
        header('location:/'.REPERTOIRE.'/insert_thematique/' . $lang . '/TXT_MESSAGESERVEURTHEMATIQUE');
        exit;
    }
}

BD::deconnecter();
?>
