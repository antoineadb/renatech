<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../class/Manager.php';
include_once '../decide-lang.php';
include_once '../class/Securite.php';
include '../outils/constantes.php';

if (isset($_GET['page_precedente']) && $_GET['page_precedente'] == 'gestioncompte.php') {
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    $idutilisateur = $_GET['iduser'];
    
    include_once '../html/moncompte/moncomptecommun.php';   
//-------------------------------------------------------------------------------------------------------------------------------------------
//						       ENTREPRISE LABORATOIRE
//-------------------------------------------------------------------------------------------------------------------------------------------
    $ancienEntrepriseLaboratoire = $manager->getSingle2("SELECT entrepriselaboratoire FROM utilisateur WHERE idutilisateur = ?", $idutilisateur);
    if (isset($_GET['entrepriselaboratoire']) && $_GET['entrepriselaboratoire'] != $ancienEntrepriseLaboratoire) {
        $entrepriselaboratoire = new UtilisateurNomlabo($idutilisateur, $_GET['entrepriselaboratoire']);
        $manager->updateUtilisateurNomlabo($entrepriselaboratoire, $idutilisateur);
    }        
//---------------------------------------------------------------------------------------------------------------------------------------
//                                                             QUALITE DEMANDEUR
////---------------------------------------------------------------------------------------------------------------------------------------
    $ancienIdQualite = $manager->getSingle2("select idqualitedemandeurindust_qualitedemandeurindust from utilisateur where idutilisateur = ?", $idutilisateur);
    if(!empty($_GET['idqualitedemandeurindust'])&& $_GET['idqualitedemandeurindust'] != $ancienIdQualite){
        if (strlen($_GET['idqualitedemandeurindust']) > 3) {
            $idqualitedemandeurindust = (int) substr($_GET['idqualitedemandeurindust'], 2, 2);
        } else {
            $idqualitedemandeurindust = (int) substr($_GET['idqualitedemandeurindust'], 2, 1);
        }        
        //EFFACAGE DU NOM DU RESPONSABLE ET DE SON EMAIL
        $utilisateurnomresponsable = new UtilisateurNomresponsable($idutilisateur, null);
        $manager->updateUtilisateurNomresponsable($utilisateurnomresponsable, $idutilisateur);
        
        $utilisateurmailresponsable = new UtilisateurMailresponsable($idutilisateur, null);
        $manager->updateUtilisateurMailResponsable($utilisateurmailresponsable, $idutilisateur);
        
        $qualiteDemandeurindust = new UtilisateurQualiteindust($idqualitedemandeurindust, $idutilisateur);
        $manager->updateQualiteIndustriel($qualiteDemandeurindust, $idutilisateur);
    }    
//---------------------------------------------------------------------------------------------------------------------------------------
//                                                             EMAIL RESPONSABLE
////---------------------------------------------------------------------------------------------------------------------------------------
//IL FAUT CONTROLER QUE L'EMAIL A BIEN CHANGE        
    if ($_GET['idqualitedemandeurindust'] == 'qa'.NONPERMANENTINDUST) {
        $ancienEmailResponsable = $manager->getsingle2("SELECT  mailresponsable FROM utilisateur WHERE idutilisateur =?", $idutilisateur);
        if (!empty($_GET['mailresponsable']) && $_GET['mailresponsable'] != $ancienEmailResponsable) { //NOUVEL EMAIL
            $emailResponsable = stripslashes(Securite::bdd($_GET['mailresponsable']));
            $utilisateurMailresponsable = new UtilisateurMailresponsable($idutilisateur, $emailResponsable);
            $manager->updateUtilisateurMailResponsable($utilisateurMailresponsable, $idutilisateur);
        }
//---------------------------------------------------------------------------------------------------------------------------------------
//                                                             NOM RESPONSABLE
////---------------------------------------------------------------------------------------------------------------------------------------
//IDEM POUR LES AUTRES PARAMETRES
        $ancienNomresponsable = $manager->getsingle2("SELECT  nomresponsable FROM utilisateur WHERE idutilisateur =?", $idutilisateur);
        if (!empty($_GET['nomresponsable']) && $_GET['nomresponsable'] != $ancienNomresponsable) {
            $nomresponsable = stripslashes(pg_escape_string($_GET['nomresponsable']));
            $utilisateurnomresponsable = new UtilisateurNomresponsable($idutilisateur, $nomresponsable);
            $manager->updateUtilisateurNomresponsable($utilisateurnomresponsable, $idutilisateur);
        }
    }    
    
    
//-------------------------------------------------------------------------------------------------------------------------------------------
//						       TYPE ENTREPRISE
//-------------------------------------------------------------------------------------------------------------------------------------------

    $ancienidtypeentreprise = $manager->getSingle2("SELECT idtypeentreprise FROM utilisateur,typeentreprise,appartient WHERE idtypeentreprise = idtypeentreprise_typeentreprise AND
  idutilisateur_utilisateur = idutilisateur and idutilisateur = ?", $idutilisateur);
    if (isset($_GET['idtypeentreprise']) && $_GET['idtypeentreprise'] != 'te' . $ancienidtypeentreprise && $_GET['idtypeentreprise'] != 'te0') {
        $idtypeentreprise = substr($_GET['idtypeentreprise'], 2);
        $appartient = new Appartient($idtypeentreprise, $idutilisateur);
        $manager->updateAppartient($appartient, $idutilisateur);
    }
//-------------------------------------------------------------------------------------------------------------------------------------------
//						       SECTEUR D'ACTIVITE
//-------------------------------------------------------------------------------------------------------------------------------------------
    $anciensecteuractivite = $manager->getSingle2("SELECT idsecteuractivite FROM utilisateur, intervient,secteuractivite WHERE
  intervient.idutilisateur_utilisateur = utilisateur.idutilisateur AND
  secteuractivite.idsecteuractivite = intervient.idsecteuractivite_secteuractivite AND idutilisateur =?", $idutilisateur);

    if (isset($_GET['idsecteuractivite']) && $_GET['idsecteuractivite'] != 'se' . $anciensecteuractivite && $_GET['idsecteuractivite'] != 'se0') {
        $idsecteuractivite = substr($_GET['idsecteuractivite'], 2);
        $intervient = new Intervient($idsecteuractivite, $idutilisateur);
        $manager->updateIntervient($intervient, $idutilisateur);
    }
//-------------------------------------------------------------------------------------------------------------------------------------------
//						       DROIT D'UTILISATEUR
//-------------------------------------------------------------------------------------------------------------------------------------------
    $anciendroit = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM utilisateur WHERE idutilisateur =?", $idutilisateur);
    if (!empty($_GET['role']) && $_GET['role'] != 'tu' . $anciendroit) {
        $idtypeutilisateur = substr($_GET['role'], 2);
        $typeuser = new UtilisateurType($idutilisateur, $idtypeutilisateur);
        $manager->updateUtilisateurType($typeuser, $idutilisateur);
    }
//-------------------------------------------------------------------------------------------------------------------------------------------
//						       STATUT DU COMPTE
//-------------------------------------------------------------------------------------------------------------------------------------------
    $ancienStatut = $manager->getSingle2("SELECT  actif FROM  loginpassword, utilisateur WHERE idlogin_loginpassword =  idlogin and idutilisateur=?", $idutilisateur);
    if (empty($ancienStatut)) {
        $actif = utf8_decode(TXT_NONACTIF);
    } else {
        $actif = TXT_ACTIF;
    }
    if (isset($_GET['statutcompte']) && $_GET['statutcompte'] != $actif) {
        if ($_GET['statutcompte'] == TXT_ACTIF) {
            $valactif = 'TRUE';
        } else {
            $valactif = 'FALSE';
        }
        $idlogin = $manager->getSingle2("SELECT   idlogin FROM loginpassword,utilisateur WHERE idlogin_loginpassword = idlogin and idutilisateur =?", $idutilisateur);
        $loginactif = new LoginActif($valactif, $idlogin);
        $manager->updateLoginActif($loginactif, $idlogin);
    }
    header('Location: /' . REPERTOIRE . '/updatecompteindust/' . $lang . '/' . $idutilisateur . '/1/ok');
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}