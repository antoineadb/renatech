<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'formulaireListe2.php') {
    include_once '../class/Manager.php';
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER    
    if (empty($_POST['addCentraleProximite'])) {
        header('location:/'.REPERTOIRE.'/insertCpErr/' . $lang . '/cpns');
        exit;
    } else {
        $libelleCentraleProximite = stripslashes(Securite::bdd($_POST['addCentraleProximite']));
    }    
    if(empty($_POST['regionCorrespondante'])){
        header('location:/'.REPERTOIRE.'/insertCpErr1/' . $lang . '/rens');
        exit;
    }else{
        $idregion= $_POST['regionCorrespondante'];
    }
   
    $idCentraleProximite = $manager->getSingle2("select idcentraleproximite from centraleproximite where libellecentraleproximite=?", $libelleCentraleProximite);
    if (!empty($idCentraleProximite)) {
        header('location:/'.REPERTOIRE.'/insertCpErr2/' . $lang . '/cpexist');
        exit;
    } else {
        //AJOUT DE LA CENTRALE DE PROXIMITE DANS LA BASE DE DONNEE 
        $idNewCentraleProximite = $manager->getSingle("select max (idcentraleproximite) from centraleproximite") + 1;
        $masqueCentraleProximite = FALSE;
        if($_POST['idutilisateur']=='non'){
            $id_responsable_centrale_proximite=null;
        }else{
            $id_responsable_centrale_proximite=$_POST['idutilisateur'];
        }
        $CentraleProximite = new CentraleProximite($idNewCentraleProximite, $libelleCentraleProximite, $masqueCentraleProximite, $idregion, $id_responsable_centrale_proximite);
        $manager->addCentralProximite($CentraleProximite);
        header('location:/'.REPERTOIRE.'/insertCp/' . $lang . '/cpadd');
        exit;
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
BD::deconnecter();