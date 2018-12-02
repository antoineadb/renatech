<?php

session_start();
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (isset($_GET['id_centrale']) && !empty($_GET['id_centrale'])) {
    $idacronyme = $manager->getSingle2("SELECT id_acronyme FROM config_acronyme WHERE idcentrale=?", $_GET['id_centrale']);
    if (isset($idacronyme) && !empty($idacronyme)) { //requete de mise à jour de la valeur de l'acronyme pour la centrale        
        if($_GET['ref_interne']=="on"){
            $refinterne = TRUE;
        }else{
            $refinterne = FALSE;
        }
        if($_GET['num_projet']=="on"){
            $numProjet = TRUE;
        }else{
            $numProjet = FALSE;
        }            
        $configAcronyme = new ConfigAcronyme($_GET['id_centrale'], $refinterne, $numProjet);
        $manager->updateConfigAcronyme($configAcronyme, $idacronyme);
    } else {//requete d'insertion de la valeur de l'acronyme et la centrale
        if($_GET['ref_interne']=="on"){
            $refinterne = TRUE;
        }else{
            $refinterne = FALSE;
        }
        if($_GET['num_projet']=="on"){
            $numProjet = TRUE;
        }else{
            $numProjet = FALSE;
        }
        $configAcronyme = new ConfigAcronyme($_GET['id_centrale'], $refinterne, $numProjet);
        $manager->addConfigAcronyme($configAcronyme);
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}





BD::deconnecter();
