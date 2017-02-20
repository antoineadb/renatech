<?php

session_start();
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
include_once '../class/Securite.php';
$dossier = '../uploadlogo/';
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$db = BD::connecter();
$fichierlogo = nomFichierValidesansAccent(renameFile(basename($_FILES['filelogo']['name'])));
$fichierlogocentrale = nomFichierValidesansAccent(renameFile(basename($_FILES['filelogocentrale']['name'])));
$fichierfigure = nomFichierValidesansAccent(renameFile(basename($_FILES['figure']['name'])));
if (!empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
    $numero = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
    $idstatutprojet = $manager->getSingle2("select ", $idprojet);
}
$idrapport = $manager->getSingle("select max(idrapport) from rapport") + 1;
$ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);
if (!empty($_POST['author'])) {
    $author = substr(Securite::bdd($_POST['author']), 0, 60);
} else {
    $author=$manager->getSingle2("select author from rapport where idrapport=?", $ancienidrapport);
}
if (!empty($_POST['entity'])) {
    $entity = substr(Securite::bdd($_POST['entity']), 0, 75);
} else {
    $entity=$manager->getSingle2("select entity from rapport where idrapport=?", $ancienidrapport);
}
if (!empty($_POST['villepays'])) {
    $villepays = substr(Securite::bdd($_POST['villepays']), 0, 60);
} else {
    $villepays = $manager->getSingle2("select villepays from rapport where idrapport=?", $ancienidrapport);
}
if (!empty($_POST['instituteinterest'])) {
    $instituteinterest = substr(Securite::bdd($_POST['instituteinterest']), 0, 50);
} else {
    $instituteinterest = $manager->getSingle2("select instituteinterest from rapport where idrapport=?", $ancienidrapport);
}
if (!empty($_POST['fundingsource'])) {
    $fundingsource = substr(Securite::bdd($_POST['fundingsource']), 0, 80);
} else {
    $fundingsource = $manager->getSingle2("select fundingsource from rapport where idrapport=?", $ancienidrapport);
}

if (!empty($_POST['collaborator'])) {
    $collaborator = substr(Securite::bdd($_POST['collaborator']), 0, 64);
} else {
    $collaborator = $manager->getSingle2("select collaborator from rapport where idrapport=?", $ancienidrapport);
}

if (!empty($_POST['thematics'])) {
    $thematics = Securite::bdd($_POST['thematics']);
} else {
    $thematics = $manager->getSingle2("select thematics from rapport where idrapport=?", $ancienidrapport);
}

if (!empty($_POST['startingdate'])) {
    $startingdate = $_POST['startingdate'];
} else {
    $startingdate = date('m,d,Y');
}

if (!empty($_POST['contexteobjectif'])) {
    $objectif = substr(clean(strip_tags($_POST['contexteobjectif'])), 0, 1250);
} else {
    $objectif = $manager->getSingle2("select objectif from rapport where idrapport=?", $ancienidrapport);
}
$results = strip_tags($_POST['resultats']);
if (!empty($results)) {
    $results = substr(clean(strip_tags($_POST['resultats'])), 0, 1250);
} else {
    $results = $manager->getSingle2("select results from rapport where idrapport=?", $ancienidrapport);
}
if (!empty($_POST['valorisation'])) {
    $valorization = cleanRapportTPDF($_POST['valorisation']);
} else {
    $valorization = $manager->getSingle2("select valorization from rapport where idrapport=?", $ancienidrapport);
}
if (!empty($_POST['technicalwork'])) {
    $technologicalwc = substr(clean(strip_tags($_POST['technicalwork'])), 0, 110);
} else {
    $technologicalwc = $manager->getSingle2("select technicalwork from rapport where idrapport=?", $ancienidrapport);
}
if (!empty($_POST['titre'])) {
    $title = substr(clean(strip_tags($_POST['titre'])), 0, 300);
} else {
    $title = $manager->getSingle2("select title from rapport where idrapport=?", $ancienidrapport);
}
if (!empty($_POST['legende'])) {
    $legend = substr(clean(strip_tags($_POST['legende'])), 0, 115);
} else {
    $legend = $manager->getSingle2("select legend from rapport where idrapport=?", $ancienidrapport);
}

$imagelogo = $manager->getSingle2("select logo from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['filelogo']['name'])) {
    $logo = nomFichierValidesansAccent(renameFile($_FILES['filelogo']['name']));
    if (move_uploaded_file($_FILES['filelogo']['tmp_name'], $dossier . $fichierlogo)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné
        chmod($dossier . $fichierlogo, 0777);        
    }
} elseif (!empty($imagelogo)) {
    $logo = $imagelogo;
} else {
    $logo = '';
}
$imagecentrale = $manager->getSingle2("select logocentrale from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['filelogocentrale']['name'])) {
    $logocentrale = nomFichierValidesansAccent(renameFile($_FILES['filelogocentrale']['name']));
    if (move_uploaded_file($_FILES['filelogocentrale']['tmp_name'], $dossier . $fichierlogocentrale)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné
        chmod($dossier . $fichierlogocentrale, 0777);        
    }
} elseif (!empty($imagecentrale)) {
    $logocentrale = $imagecentrale;
} else {
    $logocentrale = '';
}
$imagefigure = $manager->getSingle2("select figure from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['figure']['name'])) {
    $figure = nomFichierValidesansAccent(renameFile($_FILES['figure']['name']));
    if (move_uploaded_file($_FILES['figure']['tmp_name'], $dossier . $fichierfigure)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné
        chmod($dossier . $fichierfigure, 0777);        
    }
} elseif (!empty($imagefigure)) {
    $figure = $imagefigure;
} else {
    $figure = '';
}
include_once '../rapport/updateRapportCommun.php';
supprLogoFigure();

$centrale = $manager->getSingle2("SELECT libellecentrale FROM concerne,centrale WHERE idcentrale = idcentrale_centrale and idprojet_projet=?",$idprojet);
$nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $idprojet);
$statut = $manager->getSingle2("SELECT libellestatutprojet FROM concerne,statutprojet WHERE idstatutprojet = idstatutprojet_statutprojet AND idprojet_projet=?", $idprojet);
$idcentrale = $manager->getsingle2('select idcentrale_centrale from concerne where idprojet_projet=?',$idprojet);
createLogInfo(NOW, 'Mise à jour du rapport du projet n° '.$numero, 'Demandeur: '.$nomPrenomDemandeur[0]['nom'] . ' ' .$nomPrenomDemandeur[0]['prenom'] , removeDoubleQuote($statut), $manager,$idcentrale);

header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));