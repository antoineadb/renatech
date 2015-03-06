<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include_once '../class/Manager.php';
include_once	'../decide-lang.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (empty($_POST['idthematiqueactuel'])) {
    header('location:/'.REPERTOIRE.'/update_thematiqueErr1/' . $lang . "/TXT_MESSAGEERREURTHEMATIQUESELECT");
    exit;
} else {
    $idthematique = $_POST['idthematiqueactuel'];
}

if (empty($_POST['modifthematique'])) {
    header('location:/'.REPERTOIRE.'/update_thematiqueErr2/' . $lang . "/TXT_MESSAGEERREURTHEMATIQUENONSAISIE");
    exit;
}
if (empty($_POST['modifthematiqueen'])) {
    header('location:/'.REPERTOIRE.'/update_thematiqueErr2/' . $lang . "/TXT_MESSAGEERREURTHEMATIQUENONSAISIE");
    exit;
} else {
    $modifthematique = stripslashes(Securite::bdd($_POST['modifthematique']));
    $modifthematiqueen = stripslashes(Securite::bdd($_POST['modifthematiqueen']));
 $boolmasquethematique = $manager->getSingle2("select masquethematique from thematique where idthematique=? ", $idthematique);
    if ($boolmasquethematique == 1) {
        $boolmasquethematique = 'TRUE';
    } else {
        $boolmasquethematique = 'FALSE';
    }
    if ($lang == 'fr') {
        $thematique = new Thematique($idthematique, $modifthematique, $boolmasquethematique, $modifthematiqueen);
    } elseif ($lang == 'en') {
        $thematique = new Thematique($idthematique, $modifthematiqueen, $boolmasquethematique, $modifthematique);
    }
    $manager->updatethematique($thematique, $idthematique);
    header('location:/'.REPERTOIRE.'/update_thematique/' . $lang . "/TXT_MESSAGETHEMATIQUEUPDATE");
    exit;
}


BD::deconnecter();
?>
