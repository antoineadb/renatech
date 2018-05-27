<?php

session_start();
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_POST['page_precedente']) && ($_POST['page_precedente'] == 'formulaireListe2.php' || $_POST['page_precedente'] == 'gestionCentraleProximite.php')) {
    if (empty($_POST['idlibellecentraleProximiteactuel'])) {
        header('location:/' . REPERTOIRE . '/update_nom_centraleErr1/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSELECT');
        exit;
    } else {
        $idCentraleProximite = $_POST['idlibellecentraleProximiteactuel'];
    }

    if (empty($_POST['modifcentraleProximite'])) {
        header('location:/' . REPERTOIRE . '/update_nom_centraleErr2/' . $lang . '/TXT_MESSAGEERREURCENTRALENONSAISIE');
        exit;
    } else {
        $libelleCentraleProximite = stripslashes(Securite::bdd($_POST['modifcentraleProximite']));
        $masqueCentraleProximite = $manager->getSingle2("select masquecentraleproximite from centraleproximite where idcentraleproximite=? ", $idCentraleProximite);
        if ($masqueCentraleProximite == 1) {
            $masqueCentraleProximite = 'TRUE';
        } else {
            $masqueCentraleProximite = 'FALSE';
        }
        
        $idregion = $manager->getSingle2("select idregion from centraleproximite where idcentraleproximite=?", $idCentraleProximite);
        if($_POST['idutilisateur']=='non'){
            $id_responsable_centrale_proximite= null;
        }else{
            $id_responsable_centrale_proximite=$_POST['idutilisateur'];
        }
        $centraleName = new CentraleProximite($idCentraleProximite, $libelleCentraleProximite, $masqueCentraleProximite,$idregion, $id_responsable_centrale_proximite);
        $manager->updateCentraleProximit($centraleName, $idCentraleProximite);
        if($_POST['page_precedente'] == 'formulaireListe2.php'){
            header('location:/'. REPERTOIRE.'/update_nom_centrale_p/'. $lang . '/TXT_MESSAGESERVEURUPDATECENTRALEP');
        }elseif($_POST['page_precedente'] == 'gestionCentraleProximite.php'){
            header('location:/'. REPERTOIRE.'/updateCentraleP/'. $lang . '/msgupdate');
        }
        exit();
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
BD::deconnecter();
