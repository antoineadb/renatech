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
$numero = $manager->getSingle2("select numero from projet where idprojet=?", $_GET['idprojet']);    
/*$idrapport = $manager->getSingle("select max(idrapport) from rapport") + 1;*/
$ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $_GET['idprojet']);
if (!empty($_POST['author'])) {
    $author = substr(Securite::bdd($_POST['author']), 0, 60);
} else {
    $author=null;
}
if (!empty($_POST['entity'])) {
    $entity = substr(Securite::bdd($_POST['entity']), 0, 75);
} else {
    $entity=null;
}
if (!empty($_POST['villepays'])) {
    $villepays = substr(Securite::bdd($_POST['villepays']), 0, 60);
} else {
    $villepays = null;
}
if (!empty($_POST['instituteinterest'])) {
    $instituteinterest = substr(Securite::bdd($_POST['instituteinterest']), 0, 50);
} else {
    $instituteinterest = null;
}
if (!empty($_POST['fundingsource'])) {
    $fundingsource = substr(Securite::bdd($_POST['fundingsource']), 0, 80);
} else {
    $fundingsource = null;
}

if (!empty($_POST['collaborator'])) {
    $collaborator = substr(Securite::bdd($_POST['collaborator']), 0, 64);
} else {
    $collaborator = null;
}

if (!empty($_POST['thematics'])) {
    $thematics = Securite::bdd($_POST['thematics']);
} else {
    $thematics = null;
}

if (!empty($_POST['startingdate'])) {
    $startingdate = $_POST['startingdate'];
} else {
    $startingdate = date('m,d,Y');
}

if (!empty($_POST['contexteobjectif'])) {
    $objectif = substr(clean($_POST['contexteobjectif']), 0, 1250);
} else {
    $objectif = null;
}
$results = strip_tags($_POST['resultats']);
if (!empty($results)) {
    $results = substr(clean($_POST['resultats']), 0, 1250);
} else {
    $results = null;
}
if (!empty($_POST['valorisation'])) { 
    $valorization = substr(clean($_POST['valorisation']), 0, 1250);
} else {
    $valorization = null;
}
if (!empty($_POST['technicalwork'])) {
    $technologicalwc = substr(clean($_POST['technicalwork']), 0, 110);
} else {
    $technologicalwc = null;
}
if (!empty($_POST['titre'])) {
    $title = substr(clean($_POST['titre']), 0, 300);
} else {
    $title = null;
}
if (!empty($_POST['legende'])) {
    $legend = substr(clean($_POST['legende']), 0, 115);
} else {
    $legend = null;
}

$imagelogo = $manager->getSingle2("select logo from rapport where idprojet=?", $_GET['idprojet']);
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
$imagecentrale = $manager->getSingle2("select logocentrale from rapport where idprojet=?", $_GET['idprojet']);
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
$imagefigure = $manager->getSingle2("select figure from rapport where idprojet=?", $_GET['idprojet']);
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

$centrale = $manager->getSingle2("SELECT libellecentrale FROM concerne,centrale WHERE idcentrale = idcentrale_centrale and idprojet_projet=?",$_GET['idprojet']);
$nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $_GET['idprojet']);
$statut = $manager->getSingle2("SELECT libellestatutprojet FROM concerne,statutprojet WHERE idstatutprojet = idstatutprojet_statutprojet AND idprojet_projet=?", $_GET['idprojet']);
$idcentrale = $manager->getsingle2('select idcentrale_centrale from concerne where idprojet_projet=?',$_GET['idprojet']);
createLogInfo(NOW, 'Mise à jour du rapport du projet n° '.$numero, 'Demandeur: '.$nomPrenomDemandeur[0]['nom'] . ' ' .$nomPrenomDemandeur[0]['prenom'] , removeDoubleQuote($statut), $manager,$idcentrale);

header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));