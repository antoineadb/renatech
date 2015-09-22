<?php

session_start();
include_once '../decide-lang.php';
include_once '../outils/constantes.php';
if (!empty($_GET['page_precedente']) && $_GET['page_precedente'] == 'gestioncompte.php') {
    include_once '../class/Manager.php';
    include_once '../class/Securite.php';
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    $_SESSION['libelleautrenomemployeur'] = $_GET['libelleautrenomemployeur'];
    $_SESSION['libelleautretutelle'] = $_GET['libelleautretutelle'];
    $_SESSION['libelleautrediscipline'] = $_GET['libelleautrediscipline'];
    $idutilisateur = $_GET['iduser'];
    include_once '../html/moncompte/moncomptecommun.php';

//---------------------------------------------------------------------------------------------------------------------------------------
//                                                             QUALITE DEMANDEUR
////---------------------------------------------------------------------------------------------------------------------------------------
    $ancienIdQualite = $manager->getSingle2("select idqualitedemandeuraca_qualitedemandeuraca from utilisateur where idutilisateur = ?", $idutilisateur);
    if (!empty($_GET['qualitedemandeuraca']) && $_GET['qualitedemandeuraca'] != $ancienIdQualite) {
        if (strlen($_GET['qualitedemandeuraca']) > 3) {
            $idqualitedemandeuraca = (int) substr($_GET['qualitedemandeuraca'], 2, 2);
        } else {
            $idqualitedemandeuraca = (int) substr($_GET['qualitedemandeuraca'], 2, 1);
        }
        //EFFACAGE DU NOM DU RESPONSABLE ET DE SON EMAIL
        $utilisateurnomresponsable = new UtilisateurNomresponsable($idutilisateur, null);
        $manager->updateUtilisateurNomresponsable($utilisateurnomresponsable, $idutilisateur);

        $utilisateurmailresponsable = new UtilisateurMailresponsable($idutilisateur, null); 
        $manager->updateUtilisateurMailResponsable($utilisateurmailresponsable, $idutilisateur);

        $qualiteDemandeuraca = new UtilisateurQualiteaca($idqualitedemandeuraca, $idutilisateur);
        $manager->updateQualiteAcademique($qualiteDemandeuraca, $idutilisateur);
    }
//---------------------------------------------------------------------------------------------------------------------------------------
//                                                             EMAIL RESPONSABLE
////---------------------------------------------------------------------------------------------------------------------------------------
//IL FAUT CONTROLER QUE L'EMAIL A BIEN CHANGE    
    if ($_GET['qualitedemandeuraca'] == 'qa' . NONPERMANENT) {
        $ancienEmailResponsable = $manager->getsingle2("SELECT  mailresponsable FROM utilisateur WHERE idutilisateur =?", $idutilisateur);
        if (!empty($_GET['mailresponsable']) && $_GET['mailresponsable'] != $ancienEmailResponsable) { //NOUVEL EMAIL
            $emailResponsable = $_GET['mailresponsable'];
            $utilisateurMailresponsable = new UtilisateurMailresponsable($idutilisateur, $emailResponsable);
            $manager->updateUtilisateurMailResponsable($utilisateurMailresponsable, $idutilisateur);
        }
//---------------------------------------------------------------------------------------------------------------------------------------
//                                                             NOM RESPONSABLE
////---------------------------------------------------------------------------------------------------------------------------------------
//IDEM POUR LES AUTRES PARAMETRES
        $ancienNomresponsable = $manager->getsingle2("SELECT  nomresponsable FROM utilisateur WHERE idutilisateur =?", $idutilisateur);
        if (!empty($_GET['nomresponsable']) && $_GET['nomresponsable'] != $ancienNomresponsable) {
            $nomresponsable = stripslashes(Securite::bdd($_GET['nomresponsable']));
            $utilisateurnomresponsable = new UtilisateurNomresponsable($idutilisateur, $nomresponsable);
            $manager->updateUtilisateurNomresponsable($utilisateurnomresponsable, $idutilisateur);
        }
    }
//-------------------------------------------------------------------------------------------------------------------------------------------
//						       DROIT D'UTILISATEUR
//-------------------------------------------------------------------------------------------------------------------------------------------
    $anciendroit = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM utilisateur WHERE idutilisateur =?", $idutilisateur);
    if (!empty($_GET['role']) && $_GET['role'] != 'tu' . $anciendroit) {// ON VERIFIE QUE L'ON A SELECTIONNE UN DROIT
        $idtypeutilisateur = substr($_GET['role'], 2);
        if ($idtypeutilisateur == UTILISATEUR || $idtypeutilisateur == ADMINNATIONNAL) {//CAS UTILISATEUR ou ADMINNATIONNAL
            $typeuser = new UtilisateurType($idutilisateur, $idtypeutilisateur);
            $manager->updateUtilisateurType($typeuser, $idutilisateur);
        } elseif ($idtypeutilisateur == ADMINLOCAL) {//ADMINISTRATEUR LOCAL
            $idcentrale = (int) substr($_GET['centrale'], 2); //ON RECUPERE LA CENTRALE
            if ($idcentrale > 0) {
                $idcentrale = (int) substr($_GET['centrale'], 2);
            } else {
                $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['centrale']);
                if (empty($idcentrale)) {//ON RECUPERE LE NOM DE LA CENTRALE D'ORIGINE
                    $idcentrale = $manager->getSingle2("select idcentrale_centrale from utilisateur where idutilisateur=?", $idutilisateur);
                }
            }
            if ($idcentrale == 0) {
                if ($idcentrale == 0) {
                    header('Location: /' . REPERTOIRE . '/gestionCompte/' . $lang . '/' . $_GET['iduser'] . '/msgerrcentrale=ok');
                    exit();
                }
            } else {
                $typeuser = new UtilisateurTypeadmin($idutilisateur, $idtypeutilisateur, $idcentrale);
                $manager->updateUtilisateurTypeAdmin($typeuser, $idutilisateur);
            }
        }
    }
//---------------------------------------------------------------------------------------------------------------------------------------
//                                                             LIBELLE EMPLOYEUR
//---------------------------------------------------------------------------------------------------------------------------------------
    $ancienIdemployeur = $manager->getsingle2("SELECT idemployeur FROM  utilisateur,nomemployeur WHERE idemployeur = idemployeur_nomemployeur and idutilisateur =?", $idutilisateur);
    if (!empty($_GET['idemployeur']) && $_GET['idemployeur'] != 'ne' . $ancienIdemployeur) {// UN LIBELLE EMPLOYEUR A ETE SELECTIONNE
        $idemployeur_nomemployeur = substr($_GET['idemployeur'], 2);
        $utilisateurnomemployeur = new UtilisateurNomemployeur($idutilisateur, $idemployeur_nomemployeur, 1);
        $manager->updateUtilisateurNomemployeur($utilisateurnomemployeur, $idutilisateur);
    }
//---------------------------------------------------------------------------------------------------------------------------------------
//                                                             LIBELLE AUTRE EMPLOYEUR
//---------------------------------------------------------------------------------------------------------------------------------------
    $ancienautreLibelleemployeur = Securite::bdd($manager->getsingle2("SELECT libelleautrenomemployeur FROM utilisateur,autrenomemployeur WHERE  idautrenomemployeur =idautrenomemployeur_autrenomemployeur
  and idutilisateur =?", $idutilisateur)); //LIBELLE DANS LA BASE DE DONNEE
    $nouveauautreLibelleemployeur = stripslashes(Securite::bdd($_GET['libelleautrenomemployeur']));
    if (!empty($nouveauautreLibelleemployeur) && $ancienautreLibelleemployeur != $nouveauautreLibelleemployeur) { //SI ON A AJOUTER UN NOUVEAU NOM EMPLOYEUR
        // RECUPERATION DU NOUVEL ID/*
        $idautrenomemployeur = $manager->getSingle("select max(idautrenomemployeur)from autrenomemployeur") + 1;
        // RECUPERATION DE L'ID DE "autres"
        $idautre = $manager->getSingle("select idemployeur from nomemployeur where libelleemployeur= 'Autres'");
        $autrenomemployeur = new Autrenomemployeur($idautrenomemployeur, $nouveauautreLibelleemployeur);
        $manager->addAutrenomemployeur($autrenomemployeur);
        $idautrenomemployeur_autrenomemployeur = $manager->getSingle("select max(idautrenomemployeur) from autrenomemployeur");
        $utilisateurnomemployeur = new UtilisateurNomemployeur($idutilisateur, $idautre, $idautrenomemployeur_autrenomemployeur);
        $manager->updateUtilisateurNomemployeur($utilisateurnomemployeur, $idutilisateur);
    }
//--------------------------------------------------------------------------------------------------------------------------------------------
//							    TUTELLE
//--------------------------------------------------------------------------------------------------------------------------------------------
    $ancienIdtutelle = Securite::bdd($manager->getsingle2("SELECT idtutelle FROM  utilisateur,tutelle WHERE idtutelle = idtutelle_tutelle and idutilisateur =?", $idutilisateur));
    if (!empty($_GET['idtutelle']) && $_GET['idtutelle'] != 'tu' . $ancienIdtutelle) {// UN LIBELLE TUTELLE A ETE SELECTIONNE
        $idtutelle_tutelle = substr($_GET['idtutelle'], 2);
        $utilisateurTutelle = new UtilisateurTutelle($idutilisateur, $idtutelle_tutelle, 1);
        $manager->updateUtilisateurTutelle($utilisateurTutelle, $idutilisateur);
    }
//---------------------------------------------------------------------------------------------------------------------------------------
//                                                           LIBELLE AUTRE TUTELLE
//---------------------------------------------------------------------------------------------------------------------------------------
    $ancienLibelleautretutelle = Securite::bdd($manager->getsingle2("SELECT libelleautrestutelle FROM utilisateur,autrestutelle WHERE  idautrestutelle =idautrestutelle_autrestutelle
  and idutilisateur =?", $idutilisateur)); //LIBELLE DANS LA BASE DE DONNEE
    $nouveauautreLibelletutelle = trim(stripslashes(Securite::bdd($_GET['libelleautretutelle'])));
    if (!empty($nouveauautreLibelletutelle) && $ancienLibelleautretutelle != $nouveauautreLibelletutelle) {
        $idautretutelle = $manager->getSingle("select max(idautrestutelle)from autrestutelle") + 1;
        $idautre = $manager->getSingle("select idtutelle from tutelle where libelletutelle= 'Autres'");
        $autretutelle = new Autrestutelle($idautretutelle, $nouveauautreLibelletutelle);
        $manager->addAutrestutelle($autretutelle);
        $idautrestutelle_autrestutelle = $manager->getSingle("select max(idautrestutelle)from autrestutelle");
        $utilisateurautreTutelle = new UtilisateurTutelle($idutilisateur, $idautre, $idautrestutelle_autrestutelle);
        $manager->updateUtilisateurTutelle($utilisateurautreTutelle, $idutilisateur);
    }
//--------------------------------------------------------------------------------------------------------------------------------------------
//					DISCIPLINE
//--------------------------------------------------------------------------------------------------------------------------------------------
    $ancienIddiscipline = Securite::bdd($manager->getsingle2("SELECT iddiscipline FROM  utilisateur,disciplinescientifique WHERE iddiscipline = iddiscipline_disciplinescientifique and idutilisateur =? ", $idutilisateur));
    if (!empty($_GET['iddiscipline']) && $_GET['iddiscipline'] != 'di' . $ancienIddiscipline) {// UN LIBELLE A ETE SELECTIONNE
        $iddiscipline_disciplinescientifique = substr($_GET['iddiscipline'], 2);
        $utilisateurDiscipline = new UtilisateurDiscipline($idutilisateur, $iddiscipline_disciplinescientifique, 1);
        $manager->updateUtilisateurDiscipline($utilisateurDiscipline, $idutilisateur);
    }
//---------------------------------------------------------------------------------------------------------------------------------------
//                                                             LIBELLE AUTRE DISCIPLINE
//---------------------------------------------------------------------------------------------------------------------------------------
    $ancienLibelleautrediscipline = Securite::bdd($manager->getsingle2("SELECT libelleautrediscipline FROM utilisateur,autredisciplinescientifique WHERE  idautrediscipline =idautrediscipline_autredisciplinescientifique
  and idutilisateur =?", $idutilisateur)); //LIBELLE DANS LA BASE DE DONNEE
    $nouveauLibelleautrediscipline = stripslashes(Securite::bdd($_GET['libelleautrediscipline']));
    if (!empty($nouveauLibelleautrediscipline) && $ancienLibelleautrediscipline != $nouveauLibelleautrediscipline) {
        $idautrediscipline = $manager->getSingle("select max(idautrediscipline)from autredisciplinescientifique") + 1;
        $idautre = $manager->getSingle("select iddiscipline from disciplinescientifique where libellediscipline= 'Autres'");
        $autrediscipline = new Autredisciplinescientifique($idautrediscipline, $nouveauLibelleautrediscipline);
        $manager->addAutrediscipline($autrediscipline);
        $idautrediscipline_autredisciplinescientifique = $manager->getSingle("select max(idautrediscipline)from autredisciplinescientifique");
        $utilisateurautreDiscipline = new UtilisateurDiscipline($idutilisateur, $idautre, $idautrediscipline_autredisciplinescientifique);
        $manager->updateUtilisateurDiscipline($utilisateurautreDiscipline, $idutilisateur);
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
    if (!empty($_GET['statutcompte']) && $_GET['statutcompte'] != $actif) {
        if ($_GET['statutcompte'] == TXT_ACTIF) {
            $valactif = 'TRUE';
        } else {
            $valactif = 'FALSE';
        }
        $idlogin = $manager->getSingle2("SELECT   idlogin FROM loginpassword,utilisateur WHERE idlogin_loginpassword = idlogin and idutilisateur =?", $idutilisateur);
        $loginActif = new LoginActif($valactif, $idlogin);
        $manager->updateLoginActif($loginActif, $idlogin);
    }
//-------------------------------------------------------------------------------------------------------------------------------------------
//						       ACRONYME DU LABORATOIRE
//-------------------------------------------------------------------------------------------------------------------------------------------
    $ancienacronymelaboratoire = $manager->getSingle2("SELECT acronymelaboratoire FROM utilisateur WHERE idutilisateur = ?", $idutilisateur);
    if (isset($_GET['acronymelaboratoire']) && $_GET['acronymelaboratoire'] != $ancienacronymelaboratoire) {
        $acronymelaboratoire = new UtilisateurAcronymelabo(stripslashes(Securite::bdd($_GET['acronymelaboratoire'])), $idutilisateur);
        $manager->updateAcronymelaboratoire($acronymelaboratoire, $idutilisateur);
    }
//-------------------------------------------------------------------------------------------------------------------------------------------
//						       ENTREPRISE LABORATOIRE
//-------------------------------------------------------------------------------------------------------------------------------------------
    $ancienentreoriselaboratoire = $manager->getSingle2("SELECT entrepriselaboratoire FROM utilisateur WHERE idutilisateur = ?", $idutilisateur);
    if (isset($_GET['entrepriselaboratoire']) && $_GET['entrepriselaboratoire'] != $ancienentreoriselaboratoire) {
        $entrepriselaboratoire = new UtilisateurNomlabo($idutilisateur, stripslashes(Securite::bdd($_GET['entrepriselaboratoire'])));
        $manager->updateUtilisateurNomlabo($entrepriselaboratoire, $idutilisateur);
    }    
//-------------------------------------------------------------------------------------------------------------------------------------------
//						       SESSION
//-------------------------------------------------------------------------------------------------------------------------------------------        
    BD::deconnecter();
    $_SESSION['libelleautrenomemployeur'] = '';
    $_SESSION['libelleautretutelle'] = '';
    $_SESSION['libelleautrediscipline'] = '';

//-------------------------------------------------------------------------------------------------------------------------------------------
//						       ADMINISTRATEUR DE PROJET
//-------------------------------------------------------------------------------------------------------------------------------------------    
    if (isset($_GET['admin']) && $_GET['admin'] == 1) {
        $administrateur = $_GET['admin'];
        $useradminprojet = new UtilisateurAdministrateur($idutilisateur, $administrateur);
        $manager->updateUtilisateurAdministrateur($useradminprojet, $idutilisateur);        
        ajouteAdministrationProjet($idutilisateur);
    } elseif (isset($_GET['admin']) && $_GET['admin'] == 0) {
        $administrateur = $_GET['admin'];
        $useradminprojet = new UtilisateurAdministrateur($idutilisateur, $administrateur);
        $manager->updateUtilisateurAdministrateur($useradminprojet, $idutilisateur);
        //AJOUT DE LA FONCTION ADMINISTRATEUR DE PROJET
        retireAdministrationProjet($idutilisateur);      
    }

//-------------------------------------------------------------------------------------------------------------------------------------------
//						       FIN
//-------------------------------------------------------------------------------------------------------------------------------------------    


    header('Location: /' . REPERTOIRE . '/updatecompteaca/' . $lang . '/' . $idutilisateur . '/1/ok');
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}