<?php

session_start();

include_once '../class/Chiffrement.php';
include '../class/Manager.php';
include_once '../decide-lang.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
include_once '../class/Cache.php';

showError($_SERVER['PHP_SELF']);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);

    if (!is_dir(REP_ROOT . '/cache' . LIBELLECENTRALEUSER . '/')) {
        mkdir(REP_ROOT . '/cache' . LIBELLECENTRALEUSER . '/');
        chmod(REP_ROOT . '/cache' . LIBELLECENTRALEUSER . '/', 0777);
    }


    $cache = new Cache(REP_ROOT . '/cache' . LIBELLECENTRALEUSER, 60); //1 heure

    $db = BD::connecter();
    $manager = new Manager($db);
    if (isset($_SESSION['passe'])) {
        unset($_SESSION['passe']);
    } else {
        unset($_SESSION['mot_de_passe_1']);
    }
    if (isset($_POST['email'])) {
        $mail = pg_escape_string($_POST['email']);
        $_SESSION['mail'] = $mail;
    } elseif (isset($_SESSION['email'])) {
        $mail = $_SESSION['email'];
        $_SESSION['mail'] = $mail;
    } else {
        $mail = $_SESSION['mail'];
        $_SESSION['mail'] = $mail;
    }
    if (isset($_POST['pseudo'])) {
        $pseudo = pg_escape_string($_POST['pseudo']);
        $_SESSION['pseudo'] = $pseudo;
    } elseif (isset($_SESSION['pseudo'])) {
        $pseudo = $_SESSION['pseudo'];
    }
    nomEntete($mail, $pseudo); //AFFICHAGE DU NOM EN HAUT A DROITE DE LA PAGE ---
//  RECUPERATION DU LIBELLE DE LA CENTRALE DU RESPONSABLE CENTRALE
    $idtypeuser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo =?", $pseudo);
    if ($idtypeuser == ADMINLOCAL) {
        $libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale , utilisateur ,loginpassword  WHERE idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);
        $idcentrale = $manager->getSingle2("SELECT idcentrale FROM centrale , utilisateur ,loginpassword  WHERE idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);
    } elseif ($idtypeuser == ADMINNATIONNAL) {
        if (!empty($_POST['centrale'])) {
            $idcentrale = (int) $_POST['centrale'];
            $libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale WHERE idcentrale =?", $idcentrale);
        }
    }

    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('tous_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonTous.php';
        }
    } else {
        include_once './ctrlJson/jsonTous.php';
    }
    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('encours_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonEncours.php';
        }
    } else {
        include_once './ctrlJson/jsonEncours.php';
    }
    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('accepte_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonAccepte.php';
        }
    } else {
        include_once './ctrlJson/jsonAccepte.php';
    }
    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('attente_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonEnAttente.php';
        }
    } else {
        include_once './ctrlJson/jsonEnAttente.php';
    }
    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('refuse_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonRefuse.php';
        }
    }else{
        include_once './ctrlJson/jsonRefuse.php';
    }
    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('refuseTransfert_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonRefuseTrs.php';
        }
    }else{
            include_once './ctrlJson/jsonRefuseTrs.php';
    }
    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('finis_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonFinis.php';
        }
    }else{
        include_once './ctrlJson/jsonFinis.php';
    }
    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('cloture_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonCloturer.php';
        }
    }else{
        include_once './ctrlJson/jsonCloturer.php';
    }
    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('soustraitance_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonSoutraitance.php';
        }
    }else{
        include_once './ctrlJson/jsonSoutraitance.php';        
    }
    if ($idtypeuser == ADMINLOCAL) {
        if (!$cache->read('rapport_' . LIBELLECENTRALEUSER)) {
            include_once './ctrlJson/jsonRapport.php';
        }
    } else {
        include_once './ctrlJson/jsonRapport.php'; 
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------
    $_SESSION['email'] = $mail;
    $_SESSION['pseudo'] = $pseudo;
    if (isset($_GET['changeApplicant'])) {
        header('location:/' . REPERTOIRE . '/projetCentrale/' . $lang . '/' . $libellecentrale . '/' . $_GET['idprojet'] . '/' . $_GET['idutilisateur'] . '/ok');
    } elseif (isset($_GET['porteur'])) {
        header('Location:/' . REPERTOIRE . '/projet_centrale_affect/' . $lang . '/' . $_GET['idprojet'] . '/' . $_GET['idutilisateur'] . '/ok');
    } elseif (isset($_GET['administrateur'])) {
        header('Location:/' . REPERTOIRE . '/projetCentraleAdmin/' . $lang . '/' . $_GET['idprojet'] . '/' . $_GET['idutilisateur'] . '/ok');
    } else {
        header('location:/' . REPERTOIRE . '/projet_centrale/' . $lang . '/' . $libellecentrale);
    }
    BD::deconnecter();
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}