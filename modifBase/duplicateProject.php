<?php

session_start();
include '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../outils/constantes.php';
include_once '../decide-lang.php';
include_once '../class/Cache.php';
$videCache = new Cache(REP_ROOT . '/cache', 60);


if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (isset($_GET['idprojet']) && !empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
    $arrayProjet = $manager->getList2("select * from projet where idprojet=?", $idprojet);
    $newIdProjet = $manager->getSingle("select max(idprojet) from projet") + 1;
    $numero = createNumProjet($manager->getSingle("select max(numero) from projet"));

    $projetPhase1 = new Projetphase1($newIdProjet, '--copy--' . $arrayProjet[0]['titre'], $numero, $arrayProjet[0]['confidentiel'], $arrayProjet[0]['description'], $arrayProjet[0]['dateprojet'], $arrayProjet[0]['contexte'], $arrayProjet[0]['idtypeprojet_typeprojet'], $arrayProjet[0]['attachement'], $arrayProjet[0]['acronyme']);
    $manager->addProjetphase1($projetPhase1);


    $idutilisateur = $manager->getSingle2("SELECT idutilisateur FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword AND pseudo =?", $_SESSION['pseudo']);
    $creer = new Creer($idutilisateur, $newIdProjet);
    $manager->addCreer($creer);

    $idcentrale = $manager->getList2("select idcentrale_centrale from concerne where idprojet_projet=?", $idprojet);
    if (count($idcentrale) == 1) {
        $concerne = new Concerne($idcentrale[0]['idcentrale_centrale'], $newIdProjet, ENATTENTEPHASE2, "");
    } else {
        if ($_SESSION['idTypeUser'] == ADMINLOCAL) {
            $libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM loginpassword,utilisateur,centrale WHERE idlogin = idlogin_loginpassword AND idcentrale_centrale = idcentrale and pseudo=?", $_SESSION['pseudo']);
            header('Location: /' . REPERTOIRE . "/projet_centrale/" . $lang . "/" . $libellecentrale . '/err');
        } else {
            header('Location: /' . REPERTOIRE . "/mes_projets/" . $lang . "/err");
        }
    }
    $manager->addConcerne($concerne);
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              SourceFinancement
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------            
    $arraySourceFinancement = $manager->getList2("select idsourcefinancement_sourcefinancement,acronymesource from projetsourcefinancement where idprojet_projet=?", $idprojet);
    for ($i = 0; $i < count($arraySourceFinancement); $i++) {
        if (!empty($arraySourceFinancement[$i]['idsourcefinancement_sourcefinancement'])) {
            $projetSF = new Projetsourcefinancement($newIdProjet, $arraySourceFinancement[$i]['idsourcefinancement_sourcefinancement']);
            $manager->insertProjetSF($projetSF);
        }
        if (!empty($arraySourceFinancement[$i]['acronymesource'])) {
            $projetacro = new ProjetAcrosourcefinancement($newIdProjet, $arraySourceFinancement[$i]['acronymesource'], $arraySourceFinancement[$i]['idsourcefinancement_sourcefinancement']);
            $manager->updateProjetacrosourcefinancement($projetacro, $newIdProjet);
        }
    }
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              personneAcceuilcentrale
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
    $arrayPersonneaccueilcentrale = $manager->getList2("select idpersonneaccueilcentrale_personneaccueilcentrale from projetpersonneaccueilcentrale where idprojet_projet = ?", $idprojet);
    for ($i = 0; $i < count($arrayPersonneaccueilcentrale); $i++) {
        $projetpersonneaccueilcentrale = new Projetpersonneaccueilcentrale($newIdProjet, $arrayPersonneaccueilcentrale[$i]['idpersonneaccueilcentrale_personneaccueilcentrale']);
        $manager->addprojetpersonneaccueilcentrale($projetpersonneaccueilcentrale);
    }
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              projetpartenaire
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $arraypartenaire = $manager->getList2("SELECT idpartenaire_partenaireprojet FROM  projetpartenaire WHERE idprojet_projet=?", $idprojet);
    for ($i = 0; $i < count($arraypartenaire); $i++) {
        $projetPartenaire = new Projetpartenaire($arraypartenaire[$i]['idpartenaire_partenaireprojet'], $newIdProjet);
        $manager->addprojetpartenaire($projetPartenaire);
    }
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              projetautrecentrale
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $arrayautrecentrale = $manager->getList2("SELECT idcentrale FROM  projetautrecentrale WHERE idprojet=?", $idprojet);
    for ($i = 0; $i < count($arrayautrecentrale); $i++) {
        $projetAutreCentrale = new Projetautrecentrale($arrayautrecentrale[$i]['idcentrale'], $newIdProjet, FALSE);
        $manager->addprojetautrescentrale($projetAutreCentrale);
    }
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              ressourceprojet
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------     
    $arrayRessourceProjet = $manager->getList2("select idressource_ressource from ressourceprojet where idprojet_projet=?", $idprojet);
    for ($i = 0; $i < count($arrayRessourceProjet); $i++) {
        $ressourceProjet = new Ressourceprojet($newIdProjet, $arrayRessourceProjet[$i]['idressource_ressource']);
        $manager->addressourceprojet($ressourceProjet);
    }
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              Rappport
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------         
    $arrayRapport = $manager->getList2("select * from rapport where idprojet=?", $idprojet);
    if (!empty($arrayRapport)) {
        $idrapport = $manager->getSingle("select max(idrapport) from rapport") + 1;
        $rapport = new Rapport($idrapport, $arrayRapport[0]['title'], $arrayRapport[0]['author'], $arrayRapport[0]['entity'], $arrayRapport[0]['villepays'], $arrayRapport[0]['instituteinterest'], $arrayRapport[0]['fundingsource'], $arrayRapport[0]['collaborator'], $arrayRapport[0]['thematics'], $arrayRapport[0]['startingdate'], $arrayRapport[0]['objectif'], $arrayRapport[0]['results'], $arrayRapport[0]['valorization'], $arrayRapport[0]['technologicalwc'], $arrayRapport[0]['logo'], $arrayRapport[0]['logocentrale'], $arrayRapport[0]['figure'], $newIdProjet, $arrayRapport[0]['legend'], $arrayRapport[0]['datecreation'], $arrayRapport[0]['datemiseajour']);
        $manager->addrapport($rapport);
    }
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              Rappport
//  -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------         
    $projetPhase2 = new Projetphase2($arrayProjet[0]['contactscentraleaccueil'], $arrayProjet[0]['idtypeprojet_typeprojet'], $arrayProjet[0]['nbheure'], $arrayProjet[0]['datedebutprojet'], $arrayProjet[0]['dureeprojet'], 
            $arrayProjet[0]['idperiodicite_periodicite'], $arrayProjet[0]['centralepartenaireprojet'], $arrayProjet[0]['idthematique_thematique'], $arrayProjet[0]['idautrethematique_autrethematique'], 
            $arrayProjet[0]['descriptiftechnologique'], $arrayProjet[0]['attachementdesc'], $arrayProjet[0]['verrouidentifiee'], $arrayProjet[0]['nbplaque'], $arrayProjet[0]['nbrun'], $arrayProjet[0]['envoidevis'],
            $arrayProjet[0]['emailrespdevis'], $arrayProjet[0]['reussite'], $arrayProjet[0]['refinterneprojet'], $arrayProjet[0]['devtechnologique'], $arrayProjet[0]['nbeleve'], $arrayProjet[0]['nomformateur'], 
            $arrayProjet[0]['partenaire1'], $arrayProjet[0]['porteurprojet'], $arrayProjet[0]['dureeestime'], $arrayProjet[0]['periodestime'], $arrayProjet[0]['descriptionautrecentrale'], $arrayProjet[0]['etapeautrecentrale'],
            $arrayProjet[0]['centraleproximite'], $arrayProjet[0]['descriptioncentraleproximite'], $arrayProjet[0]['interneexterne'], $arrayProjet[0]['internationalnational'], $arrayProjet[0]['idtypecentralepartenaire']);   
    
   
    $manager->updateProjetphase2($projetPhase2, $newIdProjet);
}
$videCache->clear();
$numProjet = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
$idcentrale = $manager->getSingle2("select idcentrale_centrale from concerne where idprojet_projet=?", $idprojet);
$nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $idprojet);
createLogInfo(NOW, "Duplication du projet n° " . $numProjet . ' : ,nouveau n° '.$numero.'  demandeur ', $nomPrenomDemandeur[0]['nom'] .' ' . $nomPrenomDemandeur[0]['prenom'], TXT_ENATTENTEPHASE2, $manager,$idcentrale);

if ($_SESSION['idTypeUser'] == ADMINLOCAL) {
    header('Location: /' . REPERTOIRE . "/controler/controleSuiviProjetRespCentrale.php?lang=" . $lang);
} else {
    header('Location: /' . REPERTOIRE . "/controler/controleSuiviProjet.php?lang=" . $lang);
}
BD::deconnecter();
