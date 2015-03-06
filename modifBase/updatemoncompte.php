<?php

session_start();

include '../decide-lang.php';
include '../class/Manager.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'moncompte.html') {
    $db = BD::connecter();
    $manager = new Manager($db);
    if (isset($_SESSION['pseudo'])) {
        $iduser = $manager->getSingle2("SELECT idutilisateur FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
        $idlogin = $manager->getSingle2("select idlogin_loginpassword from utilisateur where idutilisateur =? ", $iduser);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
//                                                                              PARTIE COMMUNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
//IL FAUT CONTROLER QUE L'EMAIL A BIEN CHANGE
    $ancienEmail = $manager->getsingle2("SELECT  mail FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and idutilisateur =? ", $iduser);
    if (isset($_POST['email']) && $_POST['email'] != $ancienEmail) { //NOUVEL EMAIL
        $mail = $_POST['email'];
        $mailUser = new MailUtilisateur($mail);
        $manager->updateMailMoncompte($mailUser, $idlogin);
        $_SESSION['email'] = $mail;
    }
//IDEM POUR LES AUTRES PARAMETRE
    $ancienNom = $manager->getSingle2("select nom from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['nom']) && $_POST['nom'] != $ancienNom) {
        $nom = stripslashes((Securite::bdd($_POST['nom'])));
        $nomUser = new NomUtilisateur($nom);
        $manager->updateNomMoncompte($nomUser, $iduser);
        $_SESSION['nom'] = $nom;
    }
    $ancienPrenom = stripslashes($manager->getSingle2("select prenom from utilisateur where idutilisateur = ?", $iduser));
    if (isset($_POST['prenom']) && $_POST['prenom'] != $ancienPrenom) {
        $prenom = stripslashes(Securite::bdd($_POST['prenom']));
        $prenomUser = new PrenomUtilisateur($prenom);
        $manager->updatePrenomMoncompte($prenomUser, $iduser);
        $_SESSION['prenom'] = $prenom;
    }
    $ancienneAdresse = stripslashes($manager->getSingle2("select adresse from utilisateur where idutilisateur = ?", $iduser));
    if (isset($_POST['adresse']) && $_POST['adresse'] != $ancienneAdresse) {
        $adresse = Securite::bdd($_POST['adresse']);
        $adresseUser = new AdresseUser($adresse);
        $manager->updateAdresseMoncompte($adresseUser, $iduser);
        $_SESSION['adresse'] = $adresse;
    }
    $ancienCp = $manager->getSingle2("select codepostal from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['cp']) && $_POST['cp'] != $ancienCp) {
        $cp = Securite::bdd($_POST['cp']);
        $cpUser = new CodepostalUser($cp);
        $manager->updateCodepostalMoncompte($cpUser, $iduser);
        $_SESSION['cp'] = $cp;
    }
    $ancienneVille = stripslashes($manager->getSingle2("select ville from utilisateur where idutilisateur = ?", $iduser));
    if (isset($_POST['ville']) && $_POST['ville'] != $ancienneVille) {
        $ville = stripslashes(Securite::bdd(($_POST['ville'])));
        $villeUser = new VilleUser($ville);
        $manager->updateVilleMoncompte($villeUser, $iduser);
        $_SESSION['ville'] = $ville;
    }
    $ancienPays = $manager->getSingle2("select idpays_pays from utilisateur where idutilisateur = ?", $iduser);
    $idpays = $manager->getSingle2("SELECT idpays FROM pays where nompays=?", $_POST['pays']);
    if (isset($_POST['pays']) && $idpays != $ancienPays) {
        $pays = $_POST['pays'];
        $idpays = $manager->getSingle2("SELECT idpays FROM pays where nompays=?", $pays);
        $paysUser = new PaysUser($idpays);
        $manager->updatePaysMoncompte($paysUser, $iduser);
        $_SESSION['pays'] = $pays;
    }
    $ancienTel = $manager->getSingle2("select telephone  from utilisateur where idutilisateur = ?", $iduser);
    if (!empty($_POST['tel']) && $_POST['tel'] != $ancienTel) {
        $telUser = new TelephoneUser($_POST['tel']);
        $manager->updatetelephoneMoncompte($telUser, $iduser);
        $_SESSION['tel'] = $_POST['tel'];
    }
    $ancienFax = $manager->getSingle2("select fax  from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['fax']) && $_POST['fax'] != $ancienFax) {
        $fax = $_POST['fax'];
        $faxUser = new FaxUser($fax);
        $manager->updateFaxMoncompte($faxUser, $iduser);
        $_SESSION['fax'] = $fax;
    } else {
        $fax = '';
    }
    $ancienPseudo = $manager->getSingle2("SELECT pseudo FROM loginpassword, utilisateur WHERE
  loginpassword.idlogin = utilisateur.idlogin_loginpassword and idutilisateur=?", $iduser);

    if (isset($_POST['pseudo']) && $_POST['pseudo'] != $ancienPseudo) {
        $pseudo = $_POST['pseudo'];
        //VERIFICATION QUE LE PSEUDO N'EXISTE PAS DEJA POUR CELA ON VERIFIE QUE LE PSEUDO AVEC TOUS LES AUTRES PARAMETRE  N'EXISTE PAS
        $idnewlogin = $manager->getSingle2("select idlogin from loginpassword where pseudo=?", $pseudo);

        if (!empty($idnewlogin)) {// LE PSEUDO EXISTE
            header('location: /' . REPERTOIRE . '/updatemoncompteErr/' . $lang . '/ok');
            exit();
        } else {// LE PSEUDO N'EXISTE PAS MISE A JOUR
            $loginuser = new LoginUtilisateur($pseudo);
            $manager->updateLoginMoncompte($loginuser, $idlogin);
        }
        $_SESSION['pseudo'] = $pseudo;
    }
    $ancienNomresponsable = $manager->getSingle2("select nomresponsable  from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['nomresponsable']) && !empty($_POST['nomresponsable'])) {
        $nomresponsable = $_POST['nomresponsable'];
    } elseif (isset($_POST['nomresponsable1']) && !empty($_POST['nomresponsable1'])) {
        $nomresponsable = $_POST['nomresponsable1'];
    } else {
        $nomresponsable = '';
    }
    if ($nomresponsable != $ancienNomresponsable) {
        $nomResponsable = new UtilisateurNomresponsable($iduser, $nomresponsable);
        $manager->updateUtilisateurNomresponsable($nomResponsable, $iduser);
        $_SESSION['nomresponsable'] = $nomresponsable;
    } else {
        $nomresponsable = '';
    }
    $ancienMailresponsable = $manager->getSingle2("select mailresponsable  from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['mailresponsable']) && !empty($_POST['mailresponsable'])) {
        $mailresponsable = $_POST['mailresponsable'];
    } elseif (isset($_POST['mailresponsable1']) && !empty($_POST['mailresponsable1'])) {
        $mailresponsable = $_POST['mailresponsable1'];
    } else {
        $mailresponsable = '';
    }
    if ($mailresponsable != $ancienMailresponsable) {
        $mailResponsable = new UtilisateurMailresponsable($iduser, $mailresponsable);
        $manager->updateUtilisateurMailResponsable($mailResponsable, $iduser);
        $_SESSION['mailresponsable'] = $mailresponsable;
    } else {
        $mailresponsable = '';
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
//                                                                              TRAITEMENT CAS INDUSTRIEL
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $ancienQualiteaindust = 'qa' . $manager->getSingle2("select idqualitedemandeurindust_qualitedemandeurindust  from utilisateur where idutilisateur = ?", $iduser);
    if(isset($_POST['qualiteDemandeurindust'])&&!$_POST['qualiteDemandeurindust']!=$ancienQualiteaindust){
        if (strlen($_POST['qualiteDemandeurindust']) > 3) {
            $idqualitedemandeurindust = (int) substr($_POST['qualiteDemandeurindust'], 2, 2);
        } else {
            $idqualitedemandeurindust = (int) substr($_POST['qualiteDemandeurindust'], 2, 1);
        }
        $libellequalitedemandeurindust = $manager->getSingle2("select libellequalitedemandeurindust from qualitedemandeurindust where idqualitedemandeurindust=?", $idqualitedemandeurindust);
        $qualite = new UtilisateurQualiteindust($idqualitedemandeurindust, $iduser);
        $manager->updateQualiteIndustriel($qualite, $iduser);
    }
    $nouveauQualiteindust = $manager->getSingle2("select idqualitedemandeurindust_qualitedemandeurindust  from utilisateur where idutilisateur = ?", $iduser);
    if ($nouveauQualiteindust == PERMANENT) {
        $manager->getRequete("update utilisateur set nomresponsable = '', mailresponsable='' where idutilisateur=?", array($iduser));
    }
    
    $anciennomentreprise = $manager->getSingle2("select nomentreprise  from utilisateur where idutilisateur = ?", $iduser);
    if(isset($_POST['nomentreprise']) && Securite::bdd($_POST['nomentreprise'])!=$anciennomentreprise){
        $libellenomentreprise = Securite::bdd($_POST['nomentreprise']);
        $nomentreprise = new UtilisateurNomEntreprise($libellenomentreprise, $iduser);
        $manager->updateUtilisateurNomEntreprise($nomentreprise, $iduser);
    }
    
    $ancientypeentreprise = $manager->getSingle2("SELECT idtypeentreprise_typeentreprise FROM utilisateur,appartient WHERE idutilisateur_utilisateur = idutilisateur and idutilisateur = ?", $iduser);
    if(isset($_POST['typeEntreprise']) && Securite::bdd($_POST['typeEntreprise'] != $ancientypeentreprise)){
        if (strlen($_POST['typeEntreprise']) > 3) {
            $idtypeentreprise = (int) substr($_POST['typeEntreprise'], 2, 2);
        } else {
            $idtypeentreprise = (int) substr($_POST['typeEntreprise'], 2, 1);
        }
        $typeentrepriseuser = new UserTypeEntreprise($idtypeentreprise, $iduser);
        $manager->updateUserTypeentreprise($typeentrepriseuser,$iduser);
    }
    
    $anciensecteuractivite=$manager->getSingle2("SELECT idsecteuractivite_secteuractivite FROM utilisateur,intervient WHERE idutilisateur_utilisateur = idutilisateur and idutilisateur =?", $iduser);
    if(isset($_POST['secteurActivite']) && Securite::bdd($_POST['secteurActivite'] != $anciensecteuractivite)){
        if (strlen($_POST['secteurActivite']) > 3) {
            $idsecteuractivite = (int) substr($_POST['secteurActivite'], 2, 2);
        } else {
            $idsecteuractivite = (int) substr($_POST['secteurActivite'], 2, 1);
        }
        $secteuractivite = new UserSecteurActivite($idsecteuractivite,$iduser);
        $manager->updateUserSecteurActivite($secteuractivite, $iduser);
    }
    
    //echo '<pre>';print_r($_POST);die;
    
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
//                                                                              TRAITEMENT CAS ACADEMIQUE
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        

    $ancienNomlabo = $manager->getSingle2("select entrepriselaboratoire  from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['entrepriselaboratoire']) && $_POST['entrepriselaboratoire'] != $ancienNomlabo) {
        $NomLabo = new UtilisateurNomlabo($iduser, $_POST['entrepriselaboratoire']);
        $manager->updateUtilisateurNomlabo($NomLabo, $iduser);
        $_SESSION['Nomlabo'] = $_POST['entrepriselaboratoire'];
    }
    $ancienQualiteaca = 'qa' . $manager->getSingle2("select idqualitedemandeuraca_qualitedemandeuraca  from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['qualiteDemandeuraca']) && $_POST['qualiteDemandeuraca'] != $ancienQualiteaca) {
        if (strlen($_POST['qualiteDemandeuraca']) > 3) {
            $idqualitedemandeuraca = (int) substr($_POST['qualiteDemandeuraca'], 2, 2);
        } else {
            $idqualitedemandeuraca = (int) substr($_POST['qualiteDemandeuraca'], 2, 1);
        }
        $libellequalitedemandeuraca = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca=?", $idqualitedemandeuraca);
        $qualite = new UtilisateurQualiteaca($idqualitedemandeuraca, $iduser);
        $manager->updateQualiteAcademique($qualite, $iduser);
    }
    $nouveauQualiteaca = $manager->getSingle2("select idqualitedemandeuraca_qualitedemandeuraca  from utilisateur where idutilisateur = ?", $iduser);

    if ($nouveauQualiteaca == PERMANENT) {
        $manager->getRequete("update utilisateur set nomresponsable = '', mailresponsable='' where idutilisateur=?", array($iduser));
    }
    //------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------   
    //                 NOM EMPLOYEUR
    //------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //CAS OU LE NOM EMPLOYEUR N'EST PAS AUTRES ET QU'ON LE CHANGE AVEC LA LISTE DEROULANTE
    $ancienNomEmployeur = 'ne' . $manager->getSingle2("SELECT idemployeur  FROM nomemployeur,utilisateur WHERE idemployeur_nomemployeur = idemployeur AND idutilisateur =  ?", $iduser);
    if (isset($_POST['nomEmployeur']) && $_POST['nomEmployeur'] != $ancienNomEmployeur) {
        if (strlen($_POST['nomEmployeur']) > 3) {
            $idemployeur_nomemployeur = (int) substr($_POST['nomEmployeur'], 2, 2);
        } else {
            $idemployeur_nomemployeur = (int) substr($_POST['nomEmployeur'], 2, 1);
        }
        if ($idemployeur_nomemployeur == IDAUTREEMPLOYEUR) {
            if (!empty($_POST['libelleautreemployeur'])) {
                $libelleautrenomemployeur = Securite::BDD($_POST['libelleautreemployeur']);
            } elseif (!empty($_POST['libelleautreemployeur1'])) {
                $libelleautrenomemployeur = Securite::BDD($_POST['libelleautreemployeur1']);
            }//ajout du nouvelle autre employeur insert dans la table autre employeur 
            $idautrenomemployeur = $manager->getSingle("select max(idautrenomemployeur) from autrenomemployeur") + 1;
            $autrenomemployeur = new Autrenomemployeur($idautrenomemployeur, $libelleautrenomemployeur);
            $manager->addAutrenomemployeur($autrenomemployeur);
            $nomEmployeur = new UtilisateurNomemployeur($iduser, IDAUTREEMPLOYEUR, $idautrenomemployeur);
            $manager->updateUtilisateurNomemployeur($nomEmployeur, $iduser);
        } else {
            $nomemployeur = new UtilisateurNomemployeur($iduser, $idemployeur_nomemployeur, NAAUTREEMPLOYEUR); //echo '<pre>';print_r($nomemployeur);die;
            $manager->updateUtilisateurNomemployeur($nomemployeur, $iduser);
        }
    }
    //CAS OU LE NOM EMPLOYEUR EST AUTRE ET QU'ON CHANGE UNIQUEMENT LE CHAMPS AUTRE
    $ancienAutreNomEmployeur = removeDoubleQuote($manager->getSingle2("SELECT libelleautrenomemployeur FROM autrenomemployeur,utilisateur WHERE idautrenomemployeur = idautrenomemployeur_autrenomemployeur and idutilisateur=?", $iduser));
    if (!empty($_POST['libelleautreemployeur'])) {
        $libelleautrenomemployeur = Securite::BDD($_POST['libelleautreemployeur']);
    } elseif (!empty($_POST['libelleautreemployeur1'])) {
        $libelleautrenomemployeur = Securite::BDD($_POST['libelleautreemployeur1']);
    }
    $idautrenomemployeur = $manager->getSingle2("select idautrenomemployeur_autrenomemployeur from utilisateur where idutilisateur=?", $iduser);
    if (isset($_POST['nomEmployeur'])) {
        $nomEmployeur = $_POST['nomEmployeur'];
        if ($idautrenomemployeur != IDAUTREEMPLOYEUR && $nomEmployeur == 'ne' . IDAUTREEMPLOYEUR) {
            if ($libelleautrenomemployeur != $ancienAutreNomEmployeur) {
                $autrenomemployeur = new Autrenomemployeur($idautrenomemployeur, $libelleautrenomemployeur);
                $manager->updateAutrenomemployeur($autrenomemployeur, $idautrenomemployeur);
            }
        }
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------   
//                 TUTELLE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------   
    $ancienTutelle = 'tu' . $manager->getSingle2("SELECT idtutelle FROM utilisateur,tutelle WHERE idtutelle = idtutelle_tutelle and idutilisateur =?", $iduser);
    if (isset($_POST['tutelle'])) {
        if (strlen($_POST['tutelle']) > 3) {
            $idtutelle = (int) substr($_POST['tutelle'], 2, 2);
        } else {
            $idtutelle = (int) substr($_POST['tutelle'], 2, 1);
        }

        if ($idtutelle != $ancienNomEmployeur) {
            $idautretutelle = NAAUTRETUTELLE;
            $tutelle = new UtilisateurTutelle($iduser, $idtutelle, $idautretutelle);
            $manager->updateUtilisateurTutelle($tutelle, $iduser);
        }

        if ($idtutelle == IDAUTRETUTELLE) {
            if (!empty($_POST['autreTutelle'])) {
                $libelleautrestutelle = Securite::BDD($_POST['autreTutelle']);
            } elseif (!empty($_POST['autreTutelle1'])) {
                $libelleautrestutelle = Securite::BDD($_POST['autreTutelle1']);
            }
            $idautrestutelle = $manager->getSingle("select max(idautrestutelle) from autrestutelle") + 1;
            $autretutelle = new Autrestutelle($idautrestutelle, $libelleautrestutelle);
            $manager->addAutrestutelle($autretutelle);
            $tutelle = new UtilisateurTutelle($iduser, IDAUTRETUTELLE, $idautrestutelle);
            $manager->updateUtilisateurTutelle($tutelle, $iduser);
        } else {
            $tutelle = new UtilisateurTutelle($iduser, $idtutelle, NAAUTRETUTELLE);
            $manager->updateUtilisateurTutelle($tutelle, $iduser);
        }
    }
    //CAS OU LA TUTELLE EST AUTRE ET QU'ON CHANGE UNIQUEMENT LE CHAMPS AUTRE
    $ancienAutreTutelle = removeDoubleQuote($manager->getSingle2("SELECT libelleautrestutelle FROM utilisateur,autrestutelle WHERE idautrestutelle = idautrestutelle_autrestutelle and idutilisateur =?", $iduser));
    if (!empty($_POST['autreTutelle'])) {
        $libelleautretutelle = Securite::BDD($_POST['autreTutelle']);
    } elseif (!empty($_POST['autreTutelle1'])) {
        $libelleautretutelle = Securite::BDD($_POST['autreTutelle1']);
    }
    $idautretutelle = $manager->getSingle2("select idautrestutelle_autrestutelle from utilisateur where idutilisateur=?", $iduser);
    if (isset($_POST['tutelle'])) {
        if ($idautretutelle != IDAUTRETUTELLE && $_POST['tutelle'] == 'tu' . IDAUTRETUTELLE) {
            if ($libelleautretutelle != $ancienAutreTutelle) {
                $autreTutelle = new Autrestutelle($idautretutelle, $libelleautretutelle);
                $manager->updateAutreTutelle($autreTutelle, $idautretutelle);
            }
        }
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------   
//                 DISCIPLINE SCIENTIFIQUE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------   
    $ancienDiscipline = 'di' . $manager->getSingle2("SELECT iddiscipline FROM utilisateur,disciplinescientifique WHERE iddiscipline = iddiscipline_disciplinescientifique and idutilisateur=?", $iduser);
    if (isset($_POST['discipline'])) {
        if (strlen($_POST['discipline']) > 3) {
            $iddiscipline = (int) substr($_POST['discipline'], 2, 2);
        } else {
            $iddiscipline = (int) substr($_POST['discipline'], 2, 1);
        }

        if ($iddiscipline != $ancienDiscipline) {
            $discipline = new UtilisateurDiscipline($iduser, $iddiscipline, NAAUTREDISCIPLINE);
            $manager->updateUtilisateurDiscipline($discipline, $iduser);
        }
        if ($iddiscipline == IDAUTREDISCIPLINE) {
            if (!empty($_POST['autrediscipline'])) {
                $autrediscipline = Securite::BDD($_POST['autrediscipline']);
            } elseif (!empty($_POST['autrediscipline1'])) {
                $autrediscipline = Securite::BDD($_POST['autrediscipline1']);
            }
            $idautreDiscipline = $manager->getSingle("select max(idautrediscipline) from autredisciplinescientifique") + 1;
            $autreDiscipline = new Autredisciplinescientifique($idautreDiscipline, $autrediscipline);
            $manager->addAutrediscipline($autreDiscipline);
            $discipline = new UtilisateurDiscipline($iduser, IDAUTREDISCIPLINE, $idautreDiscipline);
            $manager->updateUtilisateurDiscipline($discipline, $iduser);
        } else {
            $discipline = new UtilisateurDiscipline($iduser, $iddiscipline, NAAUTREDISCIPLINE);
            $manager->updateUtilisateurDiscipline($discipline, $iduser);
        }
    }
    $ancienAutreDiscipline = removeDoubleQuote($manager->getSingle2("SELECT  libelleautrediscipline FROM utilisateur,autredisciplinescientifique WHERE idautrediscipline_autredisciplinescientifique = idautrediscipline and idutilisateur =?", $iduser));
    if (!empty($_POST['autrediscipline'])) {
        $autrediscipline = Securite::BDD($_POST['autrediscipline']);
    } elseif (!empty($_POST['autrediscipline1'])) {
        $autrediscipline = Securite::BDD($_POST['autrediscipline1']);
    }
    //$idautreDiscipline = $manager->getSingle2("select idautrediscipline_autredisciplinescientifique from utilisateur where idutilisateur=?", $iduser);  //  echo '$idautreDiscipline = '.$idautreDiscipline.'<br>';
    $idautreDiscipline = $manager->getSingle("select max(idautrediscipline) from autredisciplinescientifique");
    if (isset($_POST['discipline'])) {
        if ($idautreDiscipline != IDAUTREDISCIPLINE && $_POST['discipline'] == 'di' . IDAUTREDISCIPLINE) {
            if ($autrediscipline != $ancienAutreDiscipline) { //echo '$idautreDiscipline = '.$idautreDiscipline;die;
                $autreDiscipline = new Autredisciplinescientifique($idautreDiscipline, $autrediscipline); //echo '<pre>';print_r($autreDiscipline);die;
                $manager->updateAutreDiscipline($autreDiscipline, $idautreDiscipline);
            }
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------   
//                 CODE UNITE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------      
    // echo '<pre>';print_r($_POST);die;
    $ancienCodeunite = 'cu' . $manager->getSingle2("SELECT idcentrale FROM  utilisateur,centrale WHERE idcentrale = idcentrale_centrale AND idutilisateur =?", $iduser);
    if (isset($_POST['codeunite']) && $_POST['codeunite'] != $ancienCodeunite) {
        if (strlen($_POST['codeunite']) > 3) {
            $idcodeunite = (int) substr($_POST['codeunite'], 2, 2);
        } else {
            $idcodeunite = (int) substr($_POST['codeunite'], 2, 1);
        }
        if ($_POST['codeunite'] == 'aucun') {//TRAITEMENT DU CAS AUCUN            
            $codeUnite = new UtilisateurCodeunite($iduser, null);//SUPRESSION DE LA CENTRALE IDCENTRALE = NULL
            $manager->updateUtilisateurCodeunite($codeUnite, $iduser);
            $nomlab = Securite::bdd($_POST['nomlabo']);
            $nomlabo = new UtilisateurAcronymelabo($nomlab, $iduser);
            $manager->updateAcronymelaboratoire($nomlabo, $iduser);// AFFECTATION DE L'ACRONYME
            //SUPRESSION DE L'A CENTRALE IDCENTRALE = NULL'AUTRE CODE UNITE
            $idautrecodeunite =  $manager->getSingle2("select idautrecodeunite_autrecodeunite from utilisateur where idutilisateur=?", $iduser);
            if($idautrecodeunite!=IDNACODEUNITE){
                $autrecodeunite = new UtilisateurAutreCodeunite($iduser, null);
                $manager->updateAutrecodeunite($autrecodeunite, $iduser);
            }
            
        } elseif ($_POST['codeunite'] == 'cu' . AUTRECENTRALE) {//TRAITEMENT DU CAS AUTRES
            if (!empty($_POST['autreCodeunite'])) {
                $autrecodeunite = Securite::BDD($_POST['autreCodeunite']);
            } elseif (!empty($_POST['autreCodeunite1'])) {
                $autrecodeunite = Securite::BDD($_POST['autreCodeunite1']);
            }else{
                $autrecodeunite = '';
            }
            if (!empty($_POST['nomlabo'])) {
                $acronymelaboratoire = Securite::BDD($_POST['nomlabo']);
            }
            $codeUnite = new UtilisateurCodeunite($iduser, AUTRECENTRALE);
            $manager->updateUtilisateurCodeunite($codeUnite, $iduser); //MISE A JOUR DU CODE UNITE (AUTRE ID POUR LE CODE UNITE )            
            $nomlab = Securite::bdd($_POST['nomlabo']);
            $nomlabo = new UtilisateurAcronymelabo($nomlab, $iduser);
            $manager->updateAcronymelaboratoire($nomlabo, $iduser); //MISE A JOUR DE L'ACRONYME DU LABORATOIRE

            $idautrecodeunite = $manager->getSingle("select max(idautrecodeunite) from autrecodeunite") + 1;
            $autreCodeunite = new Autrecodeunite($idautrecodeunite, $autrecodeunite);
            $manager->addAutrecodeunite($autreCodeunite); //CREATION DU L'AUTRE CODE UNITE
            $idautreCodeunite = $manager->getSingle("select max(idautrecodeunite) from autrecodeunite");
            $autreCodeuniteUtilisateur = new UtilisateurAutreCodeunite($iduser, $idautreCodeunite);
            $manager->updateAutrecodeunite($autreCodeuniteUtilisateur, $iduser);
        } else {
            $codeUnite = new UtilisateurCodeunite($iduser, $idcodeunite);
            $manager->updateUtilisateurCodeunite($codeUnite, $iduser);
            $nomlabo = new UtilisateurAcronymelabo('', $iduser);
            $manager->updateAcronymelaboratoire($nomlabo, $iduser);
            $idautrecodeunite = $manager->getSingle2("select idautrecodeunite_autrecodeunite from utilisateur where idutilisateur=?", $iduser);
            $userautrecodeunite = new UtilisateurAutreCodeunite($iduser, IDNACODEUNITE);
            $manager->updateAutrecodeunite($userautrecodeunite, $iduser);
            // EFFACAGE DE L'ACRONYME DE L'ACRONYMELABORATOIRE ET DE L'AUTRE CODEUNITE      --> A FAIRE            
        }
    }
    //CAS OU ON MODIFIE L'ACRONYME ET L'AUTRE CODEUNITE SANS UTILISER LA LISTE DEROULANTE CODEUNITE
    $libelleancienautrecodeunite = $manager->getSingle2("SELECT libelleautrecodeunite FROM autrecodeunite,utilisateur WHERE  idautrecodeunite = idautrecodeunite_autrecodeunite AND idutilisateur=?", $iduser);
    if (!empty($_POST['autreCodeunite']) && Securite::bdd($_POST['autreCodeunite'])!=$libelleancienautrecodeunite) {
        $autrecodeunite = Securite::BDD($_POST['autreCodeunite']);
    } elseif (!empty($_POST['autreCodeunite1'])&& Securite::bdd($_POST['autreCodeunite1'])!=$libelleancienautrecodeunite) {
        $autrecodeunite = Securite::BDD($_POST['autreCodeunite1']);
    } elseif (!empty($_POST['autreCodeunite2'])&& Securite::bdd($_POST['autreCodeunite2'])!=$libelleancienautrecodeunite) {
        $autrecodeunite = Securite::BDD($_POST['autreCodeunite2']);
    } else {
        $autrecodeunite = '';
    }
    if (!empty($_POST['nomlabo'])) {
        $acronymelaboratoire = Securite::BDD($_POST['nomlabo']);
    } else {
        $acronymelaboratoire = '';
    }
    if ($autrecodeunite != '') {
        $ancienautrecodeunite = $manager->getSingle2("SELECT libelleautrecodeunite FROM utilisateur,autrecodeunite WHERE idautrecodeunite = idautrecodeunite_autrecodeunite and idutilisateur =?", $iduser);
        if ($ancienautrecodeunite != $autrecodeunite) {
            $idautrecodeunite = $manager->getSingle2("select idautrecodeunite_autrecodeunite from utilisateur where idutilisateur=?", $iduser);
            if($idautrecodeunite!=IDNACODEUNITE){
                $autreCodeunite = new AutreCodeunite($idautrecodeunite, $autrecodeunite);
                $manager->updateAutreCU($autreCodeunite, $idautrecodeunite);
            }
        }
    }
    $ancienacronymelaboratoire = $manager->getSingle2("select acronymelaboratoire from utilisateur where idutilisateur=?", $iduser);
    //echo '$ancienacronymelaboratoire '.$ancienacronymelaboratoire.'<br>'.'$acronymelaboratoire = '.$acronymelaboratoire;
    if ($acronymelaboratoire != '' && $ancienacronymelaboratoire != $acronymelaboratoire) {        
            $acronymelabo = new UtilisateurAcronymelabo($acronymelaboratoire, $iduser);
            $manager->updateAcronymelaboratoire($acronymelabo, $iduser);
    }

    header('location:/' . REPERTOIRE . '/updateMonCompte/' . $lang . '/ok');
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}
BD::deconnecter();
