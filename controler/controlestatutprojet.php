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
if (!empty($_GET['centrale'])) {
    $libellecentrale = $_GET['centrale'];
    $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale =?", $libellecentrale);
}
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
    if(!$idstatutprojet){
       $idstatutprojet = $manager->getSingle2("SELECT idstatutprojet_statutprojet FROM concerne WHERE idprojet_projet=?", $idprojet);
    }
    
    if (!empty($_SESSION['idTypeUser'])) {
        $idtypeuser = $_SESSION['idTypeUser'];
    } else {
        $idutilisateur = $manager->getSingle2('select idutilisateur_utilisateur from creer where idprojet_projet=?', $idprojet);
        $idtypeuser = $manager->getSingle2("select idtypeutilisateur_typeutilisateur from utilisateur where idutilisateur=?", $idutilisateur);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------------
//                                                                  SI PROJET EN SOUS TRAITANCE
//-----------------------------------------------------------------------------------------------------------------------------------------------
$idprojetsoustraitance=$manager->getList2("SELECT  pa.idcentrale,co.idstatutprojet_statutprojet FROM  projetautrecentrale pa,projet p,creer c,concerne co WHERE  pa.idprojet = p.idprojet AND c.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet and p.idprojet =?", $idprojet);

if(!empty($idprojetsoustraitance[0]['idcentrale'])){
    $idcentralesoustraitance =$idprojetsoustraitance[0]['idcentrale'];
}else{
    $idcentralesoustraitance='';
}
//-----------------------------------------------------------------------------------------------------------------------------------------------
//                                                                  
//-----------------------------------------------------------------------------------------------------------------------------------------------    
    switch ($idstatutprojet) {     
        case ACCEPTE:// ACCEPTE
            if(!empty($idcentralesoustraitance) && $idcentralesoustraitance==IDCENTRALEUSER){
                header('location: /' . REPERTOIRE . '/Run_projectsoustraitance/' . $lang . '/' . $numProjet . '/'.ACCEPTE.'');
            }else{                
                header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.ACCEPTE.'');
            }
            break;
        case REFUSE://REFUSE
             if(!empty($idcentralesoustraitance) && $idcentralesoustraitance==IDCENTRALEUSER){
                header('location: /' . REPERTOIRE . '/Run_projectsoustraitance/' . $lang . '/' . $numProjet . '/'.ACCEPTE.'');
            }else{
                header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.REFUSE);
            }
            break;
        case FINI://FINI
             if(!empty($idcentralesoustraitance) && $idcentralesoustraitance==IDCENTRALEUSER){
                header('location: /' . REPERTOIRE . '/Run_projectsoustraitance/' . $lang . '/' . $numProjet . '/'.ACCEPTE.'');
            }else{
                header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.FINI.'');
            }
            break;
        case ENATTENTEPHASE2://EN ATTENTE PHASE 2
             if(!empty($idcentralesoustraitance) && $idcentralesoustraitance==IDCENTRALEUSER){
                header('location: /' . REPERTOIRE . '/Run_projectsoustraitance/' . $lang . '/' . $numProjet . '/'.ACCEPTE.'');
            }else{
                header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.ENATTENTEPHASE2.'');
            }
            break;
        case CLOTURE: // CLOTURE
             if(!empty($idcentralesoustraitance) && $idcentralesoustraitance==IDCENTRALEUSER){
                header('location: /' . REPERTOIRE . '/Run_projectsoustraitance/' . $lang . '/' . $numProjet . '/'.ACCEPTE.'');
            }else{
                header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.CLOTURE.'');
            }
            break;
        case ENCOURSREALISATION: // EN COURS DE REALISATION
             if(!empty($idcentralesoustraitance) && $idcentralesoustraitance==IDCENTRALEUSER){
                header('location: /' . REPERTOIRE . '/Run_projectsoustraitance/' . $lang . '/' . $numProjet . '/'.ACCEPTE.'');
            }else{
                header('location: /' . REPERTOIRE . '/Run_project/' . $lang . '/' . $numProjet . '/'.ENCOURSREALISATION.'');
            }
            break;
    }
}
BD::deconnecter();