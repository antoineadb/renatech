<?php

session_start();
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
include_once '../class/Securite.php';
$dossier = '../uploadlogo/';
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$db = BD::connecter();
$fichierlogo = basename($_FILES['filelogo']['name']);
$fichierlogocentrale = basename($_FILES['filelogocentrale']['name']);
$fichierfigure = basename($_FILES['figure']['name']);

if (!empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
    $numero = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
    $idstatutprojet = $manager->getSingle2("select ", $idprojet);
}
$idrapport = $manager->getSingle("select max(idrapport) from rapport") + 1;

if (!empty($_POST['author'])) {
    $author = substr(Securite::bdd($_POST['author']), 0, 60);
} else {
    $author = '';
}
if (!empty($_POST['entity'])) {
    $entity = substr(Securite::bdd($_POST['entity']), 0, 75);
} else {
    $entity = '';
}
if (!empty($_POST['villepays'])) {
    $villepays = substr(Securite::bdd($_POST['villepays']), 0, 60);
} else {
    $villepays = '';
}
if (!empty($_POST['instituteinterest'])) {
    $instituteinterest = substr(Securite::bdd($_POST['instituteinterest']), 0, 50);
} else {
    $instituteinterest = '';
}
if (!empty($_POST['fundingsource'])) {
    $fundingsource = substr(Securite::bdd($_POST['fundingsource']), 0, 80);
} else {
    $fundingsource = '';
}

if (!empty($_POST['collaborator'])) {
    $collaborator = substr(Securite::bdd($_POST['collaborator']), 0, 64);
} else {
    $collaborator = '';
}

if (!empty($_POST['thematics'])) {
    $thematics = Securite::bdd($_POST['thematics']);
} else {
    $thematics = '';
}

if (!empty($_POST['startingdate'])) {
    $startingdate = $_POST['startingdate'];
} else {
    $startingdate = date('m,d,Y');
}

if (!empty($_POST['contexteobjectif'])) {
    $objectif = substr(clean(strip_tags($_POST['contexteobjectif'])), 0, 1250);
} else {
    $objectif = '';
}
$results = strip_tags($_POST['resultats']);
if (!empty($results)) {
    $results = substr(clean(strip_tags($_POST['resultats'])), 0, 1250);
} else {
    $results = "";
}
$valorisation = strip_tags($_POST['valorisation']);
if (!empty($valorisation)) {
    $valorization = substr(clean(strip_tags($_POST['valorisation'])), 0, 850);
} else {
    $valorization = '';
}
if (!empty($_POST['technicalwork'])) {
    $technologicalwc = substr(clean(strip_tags($_POST['technicalwork'])), 0, 110);
} else {
    $technologicalwc = '';
}
if (!empty($_POST['titre'])) {
    $title = substr(clean(strip_tags($_POST['titre'])), 0, 300);
} else {
    $title = '';
}
if (!empty($_POST['legende'])) {
    $legend = substr(clean(strip_tags($_POST['legende'])), 0, 115);
} else {
    $legend = '';
}

$image = $manager->getSingle2("select logo from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['filelogo']['name'])) {
    $arraytaille = getimagesize($_FILES['filelogo']['tmp_name']);
    $logo = $_FILES['filelogo']['name'];
} elseif (!empty($image)) {
    $logo = $image;
} else {
    $logo = '';
}
$imagecentrale = $manager->getSingle2("select logocentrale from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['filelogocentrale']['name'])) {
    $arraytaille = getimagesize($_FILES['filelogocentrale']['tmp_name']);
    $logocentrale = $_FILES['filelogocentrale']['name'];
} elseif (!empty($imagecentrale)) {
    $logocentrale = $imagecentrale;
} else {
    $logocentrale = '';
}
$imagefigure = $manager->getSingle2("select figure from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['figure']['name'])) {
    $figure = $_FILES['figure']['name'];
} elseif (!empty($imagefigure)) {
    $figure = $imagefigure;
} else {
    $figure = '';
}
if (empty($_FILES['figure']['name'])) {
    $_bFigure = 'FALSE';
} else {
    $_bFigure = 'TRUE';
}
if (empty($_FILES['filelogo']['name'])) {
    $_bLogo = 'FALSE';
} else {
    $_bLogo = 'TRUE';
}
if (empty($_FILES['filelogocentrale']['name'])) {
    $_bLogocentrale = 'FALSE';
} else {
    $_bLogocentrale = 'TRUE';
}
$image = $manager->getSingle2("select logo from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['filelogo']['name'])) {
    $arraytaille = getimagesize($_FILES['filelogo']['tmp_name']);
    include_once '../rapport/updateRapportCommun.php';
    supprLogoFigure();
    header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    exit();
} elseif (!empty($image)) {
    $logo = $image;
} else {
    $logo = '';
}
$imagecentrale = $manager->getSingle2("select logocentrale from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['filelogocentrale']['name'])) {
    $arraytaille = getimagesize($_FILES['filelogocentrale']['tmp_name']);
    include_once '../rapport/updateRapportCommun.php';
    header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    exit();
} elseif (!empty($imagecentrale)) {
    $logocentrale = $imagecentrale;
} else {
    $logocentrale = '';
}

if ($_bFigure == 'FALSE' && $_bLogo == 'FALSE' && $_bLogocentrale == 'FALSE') {//aucun fichier downloadé
    include_once '../rapport/updateRapportCommun.php';
    header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
} elseif ($_bFigure == 'FALSE' && $_bLogo == 'TRUE' && $_bLogocentrale == 'FALSE') {//Un logo mais pas d'image downloadé  ni de logocentrale    
    if (move_uploaded_file($_FILES['filelogo']['tmp_name'], $dossier . $fichierlogo)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné
        chmod($dossier . $fichierlogo, 0777);
        include_once '../rapport/updateRapportCommun.php';
        header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    }
} elseif ($_bFigure == 'FALSE' && $_bLogo == 'FALSE' && $_bLogocentrale == 'TRUE') {//Un logocentrale mais pas d'image ni de logo labo downloadé      
    if (move_uploaded_file($_FILES['filelogocentrale']['tmp_name'], $dossier . $fichierlogocentrale)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné
        chmod($dossier . $fichierlogocentrale, 0777);
        include_once '../rapport/updateRapportCommun.php';
        header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    }
} elseif ($_bFigure == 'TRUE' && $_bLogo == 'FALSE' && $_bLogocentrale == 'FALSE') {//Une image downloadé mais pas de logo
    $ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);
    include_once '../rapport/updateRapportCommun.php';
    header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
} elseif ($_bFigure == 'TRUE' && $_bLogo == 'TRUE' && $_bLogocentrale == 'FALSE') {//Une image et un logo downloadé    
    if (move_uploaded_file($_FILES['filelogo']['tmp_name'], $dossier . $fichierlogo)) {
        chmod($dossier . $fichierlogo, 0777);
        include_once '../rapport/updateRapportCommun.php';
        header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    }
} elseif ($_bFigure == 'TRUE' && $_bLogo == 'FALSE' && $_bLogocentrale == 'TRUE') {//Une image et un logo downloadé    
    if (move_uploaded_file($_FILES['filelogocentrale']['tmp_name'], $dossier . $fichierlogocentrale)) {
        chmod($dossier . $fichierlogocentrale, 0777);
        include_once '../rapport/updateRapportCommun.php';
        header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    }
} elseif ($_bFigure == 'FALSE' && $_bLogo == 'TRUE' && $_bLogocentrale == 'TRUE') {//Une image et un logo downloadé    
    if (move_uploaded_file($_FILES['filelogocentrale']['tmp_name'], $dossier . $fichierlogocentrale) && (move_uploaded_file($_FILES['filelogo']['tmp_name'], $dossier . $fichierlogo))) {
        chmod($dossier . $fichierlogocentrale, 0777);
        chmod($dossier . $fichierlogo, 0777);
        include_once '../rapport/updateRapportCommun.php';
        header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    }
} elseif ($_bFigure == 'TRUE' && $_bLogo == 'TRUE' && $_bLogocentrale == 'TRUE') {//Une image et un logo downloadé    
    if (move_uploaded_file($_FILES['filelogocentrale']['tmp_name'], $dossier . $fichierlogocentrale) && move_uploaded_file($_FILES['filelogo']['tmp_name'], $dossier . $fichierlogo) && move_uploaded_file($_FILES['figure']['tmp_name'], $dossier . $fichierfigure)) {
        chmod($dossier . $fichierlogocentrale, 0777);
        chmod($dossier . $fichierlogo, 0777);
        include_once '../rapport/updateRapportCommun.php';
        header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    }
}

