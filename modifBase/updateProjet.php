<?php

session_start();

include '../decide-lang.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../outils/constantes.php';
include '../class/Securite.php';

$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'modifProjet.html') {
    if (!empty($_POST['titreProjet'])) {
        $titreProjet = stripslashes(Securite::bdd($_POST['titreProjet']));
        $_SESSION['titreProjet'] = $titreProjet;
    }
    if (!empty($_POST['acronyme'])) {
        $acronyme = stripslashes(Securite::bdd($_POST['acronyme']));
        $_SESSION['acronyme'] = $acronyme;
    } elseif (!empty($_SESSION['acronyme'])) {
        $acronyme = stripslashes($_SESSION['acronyme']);
    } else {
        $acronyme = "";
    }
    $idprojet = $_GET['idprojet'];
    $numProjet = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);

    if (!empty($_POST['confid'])) {
        $confid = $_POST['confid'];
    } else {
        $confid = $manager->getSingle2("select confidentiel from projet where idprojet=?", $idprojet);
    }
    if ($confid == 1) {
        $confid = 'TRUE';
    }if (empty($confid)) {
        $confid = 'FALSE';
    }
    $arraydescriptContext = $manager->getList2("select contexte,description from projet where idprojet=?", $idprojet);
    $idstatutprojet_statutprojet = ENATTENTE;
    if (!empty($_POST['context'])) {
        $contexte = filterEditor(Securite::bdd(trim($_POST['context'])));
        $_SESSION['contextValeur'] = $_POST['context'];
    } else {
        $contexte = trim($_SESSION['contextValeur']);
    }
    if (!empty($_POST['descrip'])) {
        $descriptif = filterEditor(Securite::bdd(trim($_POST['descrip'])));
        $_SESSION['descrip'] = $_POST['descrip'];
    } else {
        $descriptif = strip_tags(trim($_SESSION['descrip']));
    }
    if (!empty($_POST['comment'])) {
        $commentaire = stripslashes(Securite::bdd(trim($_POST['comment'])));
        $_SESSION['commentaire'] = $commentaire;
    } elseif (!empty($_SESSION['commentaire'])) {
        $commentaire = stripslashes($_SESSION['commentaire']);
    } else {
        $commentaire = '';
    }
    date_default_timezone_set('Europe/London');
    $dateprojet = date("Y-m-d");
    $_SESSION['dateProjet'] = $dateprojet;


    if (!empty($_FILES['fichierProjet']['name'])) {
        $attachement = stripslashes(Securite::bdd($_FILES['fichierProjet']['name']));
    } else {
        $attachement = $manager->getSingle2("select attachement from projet where idprojet = ?", $idprojet);
        if (empty($attachement)) {
            $attachement = '';
        }
    }
    if (isset($_SESSION['numprojet'])) {
        $numero = addslashes($_SESSION['numprojet']);
    } else {
        $numero = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
    }
    $idtypeprojet = $manager->getSingle2("select idtypeprojet_typeprojet from projet where idprojet = ?", $idprojet);

//------------------------------------------------------------------------------------------------------------------------
//						       MISE A JOUR DE LA TABLE PROJET
//------------------------------------------------------------------------------------------------------------------------
    $projet = new Projetphase1($idprojet, $titreProjet, $numero, $confid, $descriptif, $dateprojet, $contexte, $idtypeprojet, $attachement, $acronyme, $commentaire);
    $manager->updateProjetphase1($projet, $idprojet);

//------------------------------------------------------------------------------------------------------------------------
//			 MISE A JOUR DE LA TABLE CONCERNE POUR LES CENTRALES, ON EFFACE TOUTES LES CENTRALES SELECTIONNEES
//------------------------------------------------------------------------------------------------------------------------
    $manager->deleteConcerne($idprojet);
//------------------------------------------------------------------------------------------------------------------------
//			 MISE A JOUR DES FICHIERS UPLOADES ON VERIFIE L'ECART ENTRE LES NOMS INSCRIT DANS LA TABLE PROJET
//			 ET LES FICHIERS PRESENTS SUR LE SERVEUR, ON EFFACE CEUX QUI NE SONT PAS REFERENCES DANS LA TABLE
//			 PROJET
//------------------------------------------------------------------------------------------------------------------------
    $uploadProjet = $manager->getdataArray("select attachement from projet where attachement !=''");
    $listerepertoire = getDirectoryList("../upload");
//$listerepertoire = tableau contenant la liste des fichiers dans le répertoire upload
    $resultEcart = array_diff($listerepertoire, $uploadProjet);
//$resultEcart = tableau contenant l'écart entre les fichiers du répertoire distant et le noms des fichier contenu dans la table projet
    for ($i = 0; $i < count($listerepertoire); $i++) {
        if (in_array($listerepertoire[$i], $resultEcart)) { //Vérification si l'
            unlink('../upload/' . $listerepertoire[$i]); //Suppression du fichier non référencés dans la table projet
        }
    }

    $centrale = $_POST['centrale'];
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
            $commentaireProjet = '';
            //MISE A JOUR DE LA TABLE CONCERNE
            $concerne = new Concerne($idCentrale, $idprojet, $idstatutprojet_statutprojet, $commentaireProjet);
            $manager->addConcerne($concerne);
												checkConcerne($idprojet,	$idCentrale,	$idstatutprojet_statutprojet);
            $_SESSION['nombre_de_centrale'] = $i;
        }
    }
    include '../upload.php';
    BD::deconnecter(); //DECONNEXION A LA BASE DE DONNEE
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
