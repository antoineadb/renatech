<?php
session_start();
include '../class/Manager.php';
include_once	'../decide-lang.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}

$db = BD::connecter();
$manager = new Manager($db);
if (!empty($_GET['numProjet'])) {
    $numProjet = $_GET['numProjet'];
    $_SESSION['numProjet'] = $numProjet;
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $numProjet);
    if(!empty($_GET['statut'])){
        $idstatutprojet= $_GET['statut'];
    }else{    
        $idstatutprojet = $manager->getSinglebyArray("select idstatutprojet_statutprojet from concerne where idprojet_projet=? and idcentrale_centrale =?", array($idprojet, $idcentrale));        
        if (empty($idstatutprojet)) {
            $idstatutprojet = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
        }
    }
    
//-----------------------------------------------------------------------------------------------------------------------------------------------
//        phase2.php?lang=$1&numProjet=$2&statut=$3&report=$4                                                         
//-----------------------------------------------------------------------------------------------------------------------------------------------    
    switch ($idstatutprojet) {        
        case ACCEPTE:// ACCEPTE                       
            header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.$idstatutprojet.'/'.rand(0,10000));
            break;        
        case FINI://FINI             
            header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.$idstatutprojet.'/'.rand(0,10000));
            break;       
        case CLOTURE: // CLOTURE             
                header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.$idstatutprojet.'/'.rand(0,10000));
            break;
        case ENCOURSREALISATION: // EN COURS DE REALISATION
                header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.$idstatutprojet.'/'.rand(0,10000));
            break;
        default:
            exit();
    }
}
BD::deconnecter();