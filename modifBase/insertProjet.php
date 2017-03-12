<?php

session_start();
include '../decide-lang.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../outils/constantes.php';
$_SESSION['titreProjet'] = '';
$_SESSION['acronyme'] = '';
$_SESSION['attachement'] = '';
$_SESSION['context'] = '';
$_SESSION['descrip'] = '';

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'createProjet.html') {
    include_once '../class/Manager.php';
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    include '../class/Securite.php';
    if (!empty($_POST['titreProjet'])) {
        $titreProjet = stripslashes(Securite::bdd($_POST['titreProjet']));
    }
    if (!empty($_POST['acronyme'])) {
        $acronyme = stripslashes(Securite::bdd($_POST['acronyme']));
    } else {
        $acronyme = "";
    }
    /*if (!empty($_FILES['fichierProjet']['name'])) {
        $attachement = stripslashes(Securite::bdd($_FILES['fichierProjet']['name']));
    } else {
        $attachement = "";
    }*/
    
    if (!empty($_FILES['fichierProjet']['name'])) {
        $attachement = stripslashes(Securite::bdd($_FILES['fichierProjet']['name']));
        $dossier1 = '../upload/';
        $fichierPhase1 = basename($_FILES['fichierProjet']['name']);
        $taille_maxi1 = 1048576;
        $taille1 = filesize($_FILES['fichierProjet']['tmp_name']);
        $extensions = array('.pdf', '.PDF');
        $extension1 = strrchr($_FILES['fichierProjet']['name'], '.');
        if (!empty($_FILES['fichierProjet']['name'])) {
            if (!in_array($extension1, $extensions)) {//VERIFICATION DU FORMAT SI IL N'EST PAS BON ON SORT                
                    $erreur = TXT_ERREURUPLOAD;
                    $attachement = "";
            } elseif ($taille1 > $taille_maxi1) {//VERIFICATION DE LA TAILLE SI ELLE EST >1mo ON SORT
                    $erreur1 = TXT_ERREURTAILLEFICHIER;
                    $attachement = "";
            } elseif (!isset($erreur)&&!isset($erreur1)) {//S'il n'y a pas d'erreur, on upload
                if (move_uploaded_file($_FILES['fichierProjet']['tmp_name'], $dossier1 . $fichierPhase1)) {
                    $erreur = '';
                    $erreur1 = '';
                    chmod($dossier1 . $fichierPhase1, 0777);
                }
            }
        }
    } else {
        $attachement = "";
    }
    
    $confidentiel = $_POST['confid'];
    $_SESSION['confid'] = $confidentiel;
    if (!empty($_POST['contextValeur'])) {
        $contexte = filterEditor(Securite::bdd(trim($_POST['contextValeur'])));
        $_SESSION['contextValeur'] = $_POST['contextValeur'];
    } elseif (!empty($_SESSION['contextValeur'])) {
        $contexte = filterEditor(Securite::bdd(trim($_SESSION['contextValeur'])));
    }
    if (!empty($_POST['descriptifValeur'])) {
        $descriptif = filterEditor(Securite::bdd(trim($_POST['descriptifValeur'])));
        $_SESSION['descriptifValeur'] = $_POST['descriptifValeur'];
    } elseif (!empty($_SESSION['descriptifValeur'])) {
        $descriptif = filterEditor(Securite::bdd(trim($_SESSION['descriptifValeur'])));
    }
    date_default_timezone_set('Europe/London');
    $dateprojet = date("m,d,Y");
    $_SESSION['dateProjet'] = $dateprojet;
//RECUPERATION DE L'IDUTILISATEUR
    if (!empty($_SESSION['idutilisateur'])) {
        $idutilisateur_utilisateur = $_SESSION['idutilisateur'];
    } else {
        $idutilisateur_utilisateur = $manager->getSingle2("SELECT idutilisateur FROM loginpassword, utilisateur WHERE
	  idlogin_loginpassword = idlogin and pseudo =?", $_SESSION['pseudo']);
    }
    $idtypeprojet_typeprojet = $manager->getSingle2("select idtypeprojet from typeprojet where libelletype =?", 'n/a'); //"n/a" par défaut    
// NUMERO DE PROJET
    $numero = $manager->getSingle("select max(numero) from projet");
    if (!empty($numero)) {
        $numProjet = createNumProjet($numero);
    } else {
        $numProjet = 'P-' . date("y") . '-' . '00001'; //CAS DU 1ER PROJET
    }
    $_SESSION['numprojet'] = $numProjet;
    
    if (!empty($_POST['centrale'])) {
        $centrale = $_POST['centrale'];
        $idprojet = $manager->getSingle("SELECT max(idprojet)FROM projet;") + 1;
        $_SESSION['idprojet'] = $idprojet;
        //MISE A JOUR DE LA TABLE CONCERNE
        //INSERSION EN BASE DE DONNEE
        $projet = new Projetphase1($idprojet, $titreProjet, $numProjet, $confidentiel, $descriptif, $dateprojet, $contexte, $idtypeprojet_typeprojet, $attachement, $acronyme);
        $manager->addProjetphase1($projet);
        //MISE A JOUR DE LA TABLE CREER
        $creer = new Creer($idutilisateur_utilisateur, $idprojet);
        $manager->addCreer($creer);
        foreach ($centrale as $chkbx) {
            $arraycentrale = $manager->getListbyArray("SELECT idcentrale,libellecentrale FROM centrale where libellecentrale =?", array($chkbx));
            for ($i = 0; $i < count($arraycentrale); $i++) {
                $idCentrale = $arraycentrale[$i]['idcentrale'];
                $libellecentrale = $arraycentrale[$i]['libellecentrale'];
                $_SESSION['libellecentrale'] = array();
                $tablibellecentrale[] = $libellecentrale;
                $_SESSION['libellecentrale'] = $tablibellecentrale;
                $_SESSION['idcentrale'] = array();
                $tabidcentrale[] = $idCentrale;
                $_SESSION['idcentrale'] = $tabidcentrale;
                //MISE A JOUR DE LA TABLE CONCERNE
                $concerne = new Concerne($idCentrale, $idprojet, ENATTENTE, '');
                $manager->addConcerne($concerne);
                checkConcerne($idprojet, $idCentrale, ENATTENTE); //SUPPRESSION DE BOUBLON SI IL EXITES
            }
        }
    }
    if(isset($erreur)&&!empty($erreur) || isset($erreur1)&&!empty($erreur1)){
        $concerne = new Concerne($centrale, $idprojet, ENATTENTEPHASE2, '');
        $manager->updateConcerne($concerne, $idprojet);
        if(!empty($erreur1)){
            header('Location: /' . REPERTOIRE . '/Upload_Error_Size/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet.'/'.ENATTENTE);
        }elseif(!empty ($erreur)){
            header('Location: /' . REPERTOIRE . '/Upload_Error/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet.'/'.ENATTENTE);
        }
        //modifProjet.php?lang=$1&erreurtaille=$2&idprojet=$3&statut=$4
        BD::deconnecter();
        exit();
    }
    header('location: /' . REPERTOIRE . '/waitingproject/' . $lang . '/' . $idprojet);
    //include '../upload.php';
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
BD::deconnecter(); //DECONNEXION DE LA BASE DE DONNEE