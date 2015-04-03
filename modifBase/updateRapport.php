<?php

session_start();
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
include_once '../class/Securite.php';
$dossier = '../uploadlogo/';
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$db = BD::connecter();
//Début des vérifications de sécurité...
$fichierlogo = basename($_FILES['logorapport']['name']);
$fichierlogocentrale = basename($_FILES['logorapportcentrale']['name']);
$fichierfigure = basename($_FILES['figure']['name']);
$taille_maxi = 204800; //200ko
$taille_maxifigure= 819200; //800ko
$taillelogo = filesize($_FILES['logorapport']['tmp_name']);
$taillelogocentrale = filesize($_FILES['logorapportcentrale']['tmp_name']);
$taillefigure = filesize($_FILES['figure']['tmp_name']);
$extensions = array('.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
$extensionlogo = strrchr($_FILES['logorapport']['name'], '.');
$extensionlogocentrale = strrchr($_FILES['logorapportcentrale']['name'], '.');
$extensionfigure = strrchr($_FILES['figure']['name'], '.');

if (!empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
    $numero = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
    $idstatutprojet = $manager->getSingle2("select ", $idprojet);
}
$idrapport = $manager->getSingle("select max(idrapport) from rapport") + 1;

if (!empty($_POST['author'])) {
    $author = substr(Securite::bdd($_POST['author']),0,60);
} else {
    $author = '';
}
if (!empty($_POST['entity'])) {
    $entity =substr(Securite::bdd($_POST['entity']),0,75);
} else {
    $entity = '';
}
if (!empty($_POST['villepays'])) {
    $villepays =substr(Securite::bdd($_POST['villepays']),0,60);
} else {
    $villepays = '';
}
if (!empty($_POST['instituteinterest'])) {
    $instituteinterest = substr(Securite::bdd($_POST['instituteinterest']),0,50);
} else {
    $instituteinterest = '';
}
if (!empty($_POST['fundingsource'])) {
    $fundingsource = substr(Securite::bdd($_POST['fundingsource']),0,80);
} else {
    $fundingsource = '';
}

if (!empty($_POST['collaborator'])) {
    $collaborator = substr(Securite::bdd($_POST['collaborator']),0,64);
} else {
    $collaborator = '';
}

if (!empty($_POST['thematics'])) {
    $thematics = Securite::bdd($_POST['thematics']);
} else {
    $thematics = '';
}

if (!empty($_POST['startingdate'])) {
    $startingdate = Securite::bdd($_POST['startingdate']);
} else {
    $startingdate = '';
}

if (!empty($_POST['contexteobjectif'])) {    
    $objectif = substr(clean(strip_tags($_POST['contexteobjectif'])),0,1007);
} else {
    $objectif = '';
}
$results=strip_tags($_POST['resultats']);
if (!empty($results)) {
    $results = substr(clean(strip_tags($_POST['resultats'])),0,1009);
} else {
    $results =""; 
}

$valorisation = strip_tags($_POST['valorisation']);
if (!empty($valorisation)) {
    $valorization = substr(clean(strip_tags($_POST['valorisation'])),0,446);
} else {
    $valorization = '';
}
if (!empty($_POST['technicalwork'])) {
    $technologicalwc = substr(clean(strip_tags($_POST['technicalwork'])),0,110);
} else {
    $technologicalwc = '';
}
if (!empty($_POST['titre'])) {
    $title = substr(Securite::bdd($_POST['titre']),0,300);
} else {
    $title = '';
}
if (!empty($_POST['legend'])) {
    $legend = Securite::bdd($_POST['legend']);
} else {
    $legend = '';
}

$image = $manager->getSingle2("select logo from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['logorapport']['name'])) {
    $arraytaille = getimagesize($_FILES['logorapport']['tmp_name']);
    if($arraytaille[0]>400 || $arraytaille[1]>350){
        header('Location: /' . REPERTOIRE . '/errorrapportsize/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    }else{
        $logo = $_FILES['logorapport']['name'];
    }
} elseif (!empty($image)) {
    $logo = $image;
} else {
    $logo = '';
}
$imagecentrale = $manager->getSingle2("select logocentrale from rapport where idprojet=?", $idprojet);
if (!empty($_FILES['logorapportcentrale']['name'])) {
    $arraytaille = getimagesize($_FILES['logorapportcentrale']['tmp_name']);
    if($arraytaille[0]>400 || $arraytaille[1]>350){
        header('Location: /' . REPERTOIRE . '/errorrapportsize/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    }else{
        $logocentrale = $_FILES['logorapportcentrale']['name'];
    }
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
if (empty($_FILES['logorapport']['name'])) {
    $_bLogo = 'FALSE';
} else {
    $_bLogo = 'TRUE';
}
if (empty($_FILES['logorapportcentrale']['name'])) {
    $_bLogocentrale = 'FALSE';
} else {
    $_bLogocentrale = 'TRUE';
}

if ($_bFigure == 'FALSE' && $_bLogo == 'FALSE' && $_bLogocentrale=='FALSE') {//aucun fichier downloadé
    $ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);    
    if (!empty($ancienidrapport)) {
        $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);                        
        $manager->updateRapport($rapport, $idprojet);
    } else {
        $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);        
        $manager->addrapport($rapport);
    }
    header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
} elseif ($_bFigure == 'FALSE' && $_bLogo == 'TRUE'&& $_bLogocentrale == 'FALSE') {//Un logo mais pas d'image downloadé  ni de logocentrale
    if (!in_array($extensionlogo, $extensions)) {//logo downloadé
        $erreur = TXT_ERREURUPLOAD;
        header('Location: /' . REPERTOIRE . '/errorrapportextension/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    } else if ($taillelogo > $taille_maxi) {
        $erreur = TXT_ERREURTAILLEFICHIER;
        header('Location: /' . REPERTOIRE . '/errorrapportsize/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    } else if (!isset($erreur)) {//S'il n'y a pas d'erreur, on upload
        if (move_uploaded_file($_FILES['logorapport']['tmp_name'], $dossier . $fichierlogo)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné
            chmod($dossier . $fichierlogo, 0777);
            $ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);

            if (!empty($ancienidrapport)) {
                $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->updateRapport($rapport, $idprojet);
            } else {
                $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->addrapport($rapport);
            }
        }
        header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    }
}elseif ($_bFigure == 'FALSE' && $_bLogo == 'FALSE'&& $_bLogocentrale == 'TRUE') {//Un logocentrale mais pas d'image ni de logo labo downloadé  
    if (!in_array($extensionlogocentrale, $extensions)) {//logo downloadé
        $erreur = TXT_ERREURUPLOAD;
        header('Location: /' . REPERTOIRE . '/errorrapportextension/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    } else if ($taillelogocentrale > $taille_maxi) {
        $erreur = TXT_ERREURTAILLEFICHIER;
        header('Location: /' . REPERTOIRE . '/errorrapportsize/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet . '');
        exit();
    } else if (!isset($erreur)) {//S'il n'y a pas d'erreur, on upload
        if (move_uploaded_file($_FILES['logorapportcentrale']['tmp_name'], $dossier . $fichierlogocentrale)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné
            chmod($dossier . $fichierlogocentrale, 0777);
            $ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);
            if (!empty($ancienidrapport)) {
                $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->updateRapport($rapport, $idprojet);
            } else {
                $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->addrapport($rapport);
            }
        }
        header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    }
} elseif ($_bFigure == 'TRUE' && $_bLogo == 'FALSE'&& $_bLogocentrale == 'FALSE') {//Une image downloadé mais pas de logo
    $ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);
    if (!empty($ancienidrapport)) {
        $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
        $manager->updateRapport($rapport, $idprojet);
    } else {
        $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
        $manager->addrapport($rapport);
    }
    header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
    
} elseif ($_bFigure == 'TRUE' && $_bLogo == 'TRUE'&& $_bLogocentrale == 'FALSE') {//Une image et un logo downloadé
    if (!in_array($extensionlogo, $extensions)) {//Vérif des extensions
        $erreur = TXT_ERREURUPLOAD;
        header('Location: /' . REPERTOIRE . '/errorrapportextension/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();    
    } else if (!isset($erreur)) {//S'il n'y a pas d'erreur, on upload
        if (move_uploaded_file($_FILES['logorapport']['tmp_name'], $dossier . $fichierlogo)) {
            chmod($dossier . $fichierlogo, 0777);            
            $ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);
            if (!empty($ancienidrapport)) {
                $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->updateRapport($rapport, $idprojet);
            } else {
                $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->addrapport($rapport);
            }
        }
    }header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
}elseif ($_bFigure == 'TRUE' && $_bLogo == 'FALSE'&& $_bLogocentrale == 'TRUE') {//Une image et un logo downloadé
    if (!in_array( $extensionlogocentrale, $extensions)) {//Vérif des extensions
        $erreur = TXT_ERREURUPLOAD;
        header('Location: /' . REPERTOIRE . '/errorrapportextension/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    }else if (!isset($erreur)) {//S'il n'y a pas d'erreur, on upload
        if (move_uploaded_file($_FILES['logorapportcentrale']['tmp_name'], $dossier . $fichierlogocentrale)) {
            chmod($dossier . $fichierlogocentrale, 0777);
            $ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);
            if (!empty($ancienidrapport)) {
                $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->updateRapport($rapport, $idprojet);
            } else {
                $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->addrapport($rapport);
            }
        }
    }header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
}elseif ($_bFigure == 'FALSE' && $_bLogo == 'TRUE'&& $_bLogocentrale == 'TRUE') {//Une image et un logo downloadé
    if (!in_array($extensionlogo,  $extensions)) {//Vérif des extensions
        $erreur = TXT_ERREURUPLOAD;
        header('Location: /' . REPERTOIRE . '/errorrapportextension/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    }elseif (!in_array($extensionlogocentrale, $extensions)) {//Vérif des extensions
        $erreur = TXT_ERREURUPLOAD;
        header('Location: /' . REPERTOIRE . '/errorrapportextension/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    } else if ($taillelogo > $taille_maxi || $taillelogocentrale > $taille_maxi) {
        $erreur = TXT_ERREURTAILLEFICHIER;
        header('Location: /' . REPERTOIRE . '/errorrapportsize/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet . '');
        exit();
    } else if (!isset($erreur)) {//S'il n'y a pas d'erreur, on upload
        if (move_uploaded_file($_FILES['logorapportcentrale']['tmp_name'], $dossier . $fichierlogocentrale) && (move_uploaded_file($_FILES['logorapport']['tmp_name'], $dossier . $fichierlogo))) {
            chmod($dossier . $fichierlogocentrale, 0777);
            chmod($dossier . $fichierlogo, 0777);
            $ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);
            if (!empty($ancienidrapport)) {
                $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->updateRapport($rapport, $idprojet);
            } else {
                $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->addrapport($rapport);
            }
        }
    }header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
}elseif ($_bFigure == 'TRUE' && $_bLogo == 'TRUE'&& $_bLogocentrale == 'TRUE') {//Une image et un logo downloadé
    if (!in_array($extensionlogo, $extensions)) {//Vérif des extensions
        $erreur = TXT_ERREURUPLOAD;
        header('Location: /' . REPERTOIRE . '/errorrapportextension/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    }else if (!in_array($extensionlogocentrale, $extensions)) {//Vérif des extensions
        $erreur = TXT_ERREURUPLOAD;
        header('Location: /' . REPERTOIRE . '/errorrapportextension/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    } else if ($taillelogocentrale > $taille_maxi || $taillelogo > $taille_maxi) {
        $erreur = TXT_ERREURTAILLEFICHIER;
        header('Location: /' . REPERTOIRE . '/errorrapportsizelogfig/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet . '');
        exit();
    } else if (!isset($erreur)) {//S'il n'y a pas d'erreur, on upload
        if (move_uploaded_file($_FILES['logorapportcentrale']['tmp_name'], $dossier . $fichierlogocentrale) && move_uploaded_file($_FILES['logorapport']['tmp_name'], $dossier . $fichierlogo) && move_uploaded_file($_FILES['figure']['tmp_name'], $dossier . $fichierfigure)) {
            chmod($dossier . $fichierlogocentrale, 0777);
            chmod($dossier . $fichierlogo, 0777);
            $ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);
            if (!empty($ancienidrapport)) {
                $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->updateRapport($rapport, $idprojet);
            } else {
                $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet,$legend);
                $manager->addrapport($rapport);
            }
        }
    }header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numero . '/' . $_GET['idstatut'] . '/' . rand(0, 10000));
}
