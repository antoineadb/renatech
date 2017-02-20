<?php

session_start();

include_once '../outils/constantes.php';
include_once '../decide-lang.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
include '../class/Securite.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
//------------------------------------------------------------------------------------------------------------
//                                       RECUPERATION DES PARAMETRES
//------------------------------------------------------------------------------------------------------------
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'createContact.html') {
    date_default_timezone_set('Europe/London');
    $date = date("Y-m-d");
    if (!empty($_POST['nom'])) {
        $nom = stripslashes((Securite::bdd($_POST['nom'])));
        $_SESSION['nom'] = $nom;
    }
    if (!empty($_POST['prenom'])) {
        $prenom = stripslashes(Securite::bdd($_POST['prenom']));
        $_SESSION['prenom'] = $prenom;
    }
    if (!empty($_POST['entrepriselaboratoire'])) {
        $entrepriselaboratoire = stripslashes(Securite::bdd($_POST['entrepriselaboratoire']));
        $_SESSION['entrepriselaboratoire'] = $entrepriselaboratoire;
    }
    if (!empty($_POST['adresse'])) {
        $adresse = stripslashes(Securite::bdd($_POST['adresse']));
        $_SESSION['adresse'] = $adresse;
    }
    if (!empty($_POST['cp'])) {
        $cp = stripslashes(Securite::bdd($_POST['cp']));
        $_SESSION['cp'] = $cp;
    }
    if (!empty($_POST['ville'])) {
        $ville = stripslashes(Securite::bdd($_POST['ville']));
        $_SESSION['ville'] = $ville;
    }
    if (!empty($_POST['tel'])) {
        $tel = pg_escape_string($_POST['tel']);
        $_SESSION['tel'] = $tel;
    }
    if (!empty($_POST['fax'])) {
        $fax = stripslashes(Securite::bdd($_POST['fax']));
        $_SESSION['fax'] = $fax;
    } else {
        $fax = '';
    }
    if (!empty($_POST['nomequipe'])) {
        $nomEquipe = stripslashes(Securite::bdd($_POST['nomequipe']));
        $_SESSION['nomequipe'] = $nomEquipe;
    } else {
        $nomEquipe = '';
    }
    $idPays = (int) substr($_POST['pays'], 2);
    if ($lang == 'fr') {
        $pays = $manager->getSingle2("select nompays from pays where idpays=?", $idPays);
    } elseif ($lang == 'en') {
        $pays = $manager->getSingle2("select nompaysen from pays where idpays=?", $idPays);
    }

    $_SESSION['pays'] = $pays;
    if (!empty($_POST['typeuser'])) {
        $typeuser = $_POST['typeuser'];
    }
    if (!empty($_SESSION['mail'])) {
        $email = $_SESSION['mail'];
    } else {
        $email = $_SESSION['email'];
    }
    if (!empty($_SESSION['pseudo'])) {
        $pseudo = $_SESSION['pseudo'];
    }
    //EFFACE LES MOTS DE PASSES CREER EN VARIABLE DE SESSION LORS DE LA CREATION DU LOGIN
    if (!empty($_SESSION['mot_de_passe_1'])) {
        unset($_SESSION['mot_de_passe_1']);
    } else {
        unset($_SESSION['passe']);
    }
    //VERIFIFACTION QUE SI ON EST RESPONSABLE CENTRALE (EMAIL SAISIE = EMAIL1 TABLE CENTRALE) ON NE PEUT PAS S'INSCRIRE COMME INDUSTRIEL
    for ($i = 1; $i < 6; $i++) {
        $idcentrale = $manager->getSingle2("select idcentrale from centrale where email" . $i . "=?", $email);
        if (!empty($idcentrale)) {
            if ($typeuser == TXT_INDUSTRIELCONTACT) {
                header('location: /' . REPERTOIRE . '/contactadmnErr/' . $lang . '/ok');
                exit();
            }
        }
    }
    //RECUPERATION DE L'idlogin_loginpassword
    $param = array($email, $pseudo);
    $rowidlogin_loginpassword = $manager->getListbyArray("SELECT l.idlogin FROM loginpassword l   WHERE l.mail = ? and l.pseudo =?", $param);
    $idlogin_loginpassword = $rowidlogin_loginpassword[0]['idlogin'];
    //VERIFICATION DU TYPE UTILISATEUR
    $idadminnational = $manager->getSingle2("select idadminnational from adminnational where emailadminnational=?", $email);
    $idadminlocal = $manager->getSingle2("select idcentrale from centrale where email1=?", $email);
    if (!empty($idadminnational)) {
        $idtypeutilisateur_typeutilisateur = ADMINNATIONNAL; //L'UTILISATEUR EST UN ADMINISTRATEUR NATIONNAL
    } elseif (!empty($idadminlocal)) {
        $idtypeutilisateur_typeutilisateur = ADMINLOCAL; //L'UTILISATEUR EST UN ADMINISTRATEUR LOCAL
    } else {
        $idtypeutilisateur_typeutilisateur = UTILISATEUR; //L'UTILISATEUR EST UN UTILISATEUR
    }
    //------------------------------------------------------------------------------------------------------------
    //                                       TRAITEMENT TYPE ACADEMIQUE
    //------------------------------------------------------------------------------------------------------------
    if (!empty($_POST['qualiteDemandeuraca'])) {
        $idqualitedemandeuraca_qualitedemandeuraca = substr($_POST['qualiteDemandeuraca'], 2);
    }
    //------------------------------------------------------------------------------------------------------------
    //                                       TRAITEMENT DE LA TUTELLE
    //------------------------------------------------------------------------------------------------------------
    if (!empty($_POST['tutelle'])) {
        $idnomemployeur = 'tu' . $manager->getSingle("select idtutelle from tutelle where libelletutelle ='Autres'");
        if ($_POST['tutelle'] != $idnomemployeur) {//VALEUR DIFFERENTE DE "Autres"
            //RECUPERATION DE L'ID DE LA TUTELLE ($idtutelle_tutelle)
            $idtutelle_tutelle = substr($_POST['tutelle'], 2);
            $idautrestutelle_autrestutelle = 1; //valeur n/a
        } else {
            //------------------------------------------------------------------------------------------------------------
            //                                       TRAITEMENT AUTRETUTELLE
            //------------------------------------------------------------------------------------------------------------
            $autretutelle = Securite::bdd($_POST['autreTutelle']);
            $idautrestutelle = $manager->getSingle("select max(idautrestutelle) from autrestutelle") + 1;
            $newautrestutelle = new Autrestutelle($idautrestutelle, $autretutelle);
            $manager->addAutrestutelle($newautrestutelle); //INSERTION DANS DE L'AUTRESTUTELLE DANS LA TABLE AUTRETUTELLE
            // RECUPERATION DE L'$idautrestutelle_autrestutelle
            $idautrestutelle_autrestutelle = $manager->getSingle("SELECT max(idautrestutelle) FROM autrestutelle");
            // RECUPERATION DE l'ID CORRESPONDANT AU LIBELLE 'Autres' DE LA TABLE TUTELLE
            $idtutelle_tutelle = $manager->getSingle2("select idtutelle from tutelle where libelletutelle=?", 'Autres');
        }
    }
    //-------------------------------------------------------------------------------------------------------------
    //                                       TRAITEMENT NOMEMPLOYEUR
    //------------------------------------------------------------  -----------------------------------------------
    if (!empty($_POST['nomEmployeur'])) {
        $idnomemployeur = 'ne' . $manager->getSingle("select idemployeur from nomemployeur where libelleemployeur ='Autres'");
        if ($_POST['nomEmployeur'] != $idnomemployeur) {//VALEUR DIFFERENTE DE "Autres"
            $nomEmployeur = $_POST['nomEmployeur'];
            $_SESSION['nomEmployeur'] = $nomEmployeur;
            //RECUPERATION DE L'ID EMPLOYEUR ($idemployeur_nomemployeur)
            $idEmployeur = substr($_POST['nomEmployeur'], 2);
            $idemployeur_nomemployeur = $manager->getSingle2("SELECT idemployeur FROM nomemployeur where idemployeur =?", $idEmployeur);
            $idautrenomemployeur_autrenomemployeur = 1; //DANS CE CAS L'ID DE L'AUTRENOM EMPLOYEUR VAUT 1
        } else {
            //------------------------------------------------------------------------------------------------------------
            //                                       TRAITEMENT AUTRENOMEMPLOYEUR
            //------------------------------------------------------------------------------------------------------------
            $autreemployeur = Securite::bdd($_POST['autreEmployeur']);
            $idautrenomemployeur = $manager->getSingle("select max(idautrenomemployeur)from autrenomemployeur ") + 1; //RECUPERATION DE L'idautrenomemployeur
            //INSERTION DE L'AUTRE EMPLOYEUR DANS LA TABLE AUTREEMPLOYEUR
            $autreenomemployeur = new Autrenomemployeur($idautrenomemployeur, $autreemployeur);
            $manager->addAutrenomemployeur($autreenomemployeur); //INSERTION DANS DE L'AUTRESTUTELLE DANS LA TABLE AUTRETUTELLE
            //RECUPERATION DE L'ID EMPLOYEUR ($idautrenomemployeur_autrenomemployeur)
            $idautrenomemployeur_autrenomemployeur = $manager->getSingle("select max(idautrenomemployeur) from autrenomemployeur ");
            // RECUPERATION DE l'ID CORRESPONDANT AU LIBELLE 'Autres' DE LA TABLE EMPLOYEUR
            $idemployeur_nomemployeur = $manager->getSingle("select idemployeur from nomemployeur where libelleemployeur='Autres';");
        }
    }
    //------------------------------------------------------------------------------------------------------------
    //                                       TRAITEMENT CODEUNITE
    //------------------------------------------------------------------------------------------------------------
    if (!empty($_POST['nomLabo'])) {
        $acronymelaboratoire = Securite::bdd($_POST['nomLabo']);
        //RECUPERATION DU CODE UNITE PAR LE NOM DU LABO, VERIFICATION QUE LE NOM DU LABO <> LIBELLECENTRALE+'(+VILLECENTRALE+')
        $libelcentrale = strstr($acronymelaboratoire, '(', TRUE); //PARTIE GAUCHE DE "libellecentrale(villecentrale)"
        $Vilcentrale = strstr($acronymelaboratoire, '(', FALSE); // PARTIE DROITE
        $param = array(trim($libelcentrale), trim($Vilcentrale));
        $rescodeunite = $manager->getListbyArray("SELECT idcentrale,codeunite FROM centrale where libellecentrale=? or villecentrale=?", $param);
        if (!empty($rescodeunite[0][0])) {//SI IL EXISTE ALORS ON AFFECTE L'IDAUTRECODE UNITE A 1
            $idcentrale_centrale = $rescodeunite[0][0];
            $codeunite = $rescodeunite[0][1];
            $idautrecodeunite_autrecodeunite = 1;
        } elseif (empty($idcentrale_centrale) && !!empty($_POST['autres'])) {//CAS OU IL N'EXISTE PAS ET ON A PAS SELECTIONNE "Autres" -->CAS "Aucun"
            $idautrecodeunite_autrecodeunite = 1;
        } else {//AUTRES CAS --> IL N'EXISTE PAS ET DONC ON NE PEUT AVOIR QUE SELECTIONNE "Autres"
            $autrecodeunite = Securite::bdd($_POST['autres']);
            //RECUPERATION DE L'idautrecodeunite
            $idautrecodeunite = $manager->getSingle("select max(idautrecodeunite) from autrecodeunite") + 1;
            $autreCodeunite = new Autrecodeunite($idautrecodeunite, $autrecodeunite);
            $manager->addAutrecodeunite($autreCodeunite); //INSERTION UN AUTRE CODE UNITE DANS LA TABLE AUTRECODEUNITE
            // RECUPERATION DE L'$idautrecodeunite_autrecodeunite
            $idautrecodeunite_autrecodeunite = $manager->getSingle("select max(idautrecodeunite) from autrecodeunite ");
            // RECUPERATION DE l'ID CORRESPONDANT AU LIBELLE 'n/a' DE LA TABLE AUTRECODEUNITE
            $codeunite = $manager->getSingle2("select idautrecodeunite from autrecodeunite where libelleautrecodeunite=?", 'n/a');
        }
    } else {// CAS OU ON EST ADMINISTRATEUR LOCAL --> RESPONSABLE DE CENTRALE
        $idcentrale_centrale = $manager->getSingle2("select idcentrale from centrale where lower(email1)=?", strtolower($email));
        if (!empty($idcentrale_centrale)) {
            $idautrecodeunite_autrecodeunite = 1; //  n/a
        }
    }
    //------------------------------------------------------------------------------------------------------------
    //                                       TRAITEMENT DISCIPLINE SCIENTIFIQUE
    //------------------------------------------------------------------------------------------------------------
    if (!empty($_POST['disciplineScientifique'])) {
        $idautrediscipline = 'di' . $manager->getSingle("select iddiscipline from disciplinescientifique where libellediscipline ='Autres'");
        if ($_POST['disciplineScientifique'] != $idautrediscipline) {
            //RECUPERATION DE L'$iddisciplineScientifique
            $iddisciplineScientifique = substr($_POST['disciplineScientifique'], 2);
            $idautrediscipline_autredisciplinescientifique = 1;
        } else {
            //$autrediscipline	=	stripslashes(pg_escape_string($_POST['autreDiscipline']));
            $autrediscipline = Securite::bdd($_POST['autreDiscipline']);
            //RECUPERATION DE L'idautrediscipline
            $idautrediscipline = $manager->getSingle("select max(idautrediscipline) from autredisciplinescientifique") + 1;
            //INSERTION UNE AUTRE DISCIPLINE SCIENTIFIQUE DANS LA TABLE AUTREDISCIPLINESCIENTIFIQUE
            $autreDiscipline = new Autredisciplinescientifique($idautrediscipline, $autrediscipline);
            $manager->addAutrediscipline($autreDiscipline);
            //RECUPERATION DE L'$idautrediscipline_autredisciplinescientifique
            $idautrediscipline_autredisciplinescientifique = $manager->getSingle("select max(idautrediscipline) from autredisciplinescientifique ");
            // RECUPERATION DE l'ID CORRESPONDANT AU LIBELLE 'Autres' DE LA TABLE DISCIPLINESCIENTIFIQUE
            $iddisciplineScientifique = $manager->getSingle2("select iddiscipline from disciplinescientifique where libellediscipline=?", 'Autres');
        }
    }
    //------------------------------------------------------------------------------------------------------------
    //                                       TRAITEMENT TYPE INDUSTRIEL
    //------------------------------------------------------------------------------------------------------------

    if (!empty($_POST['qualiteDemandeurindust'])) {
        //RECUPERATION DE L'$idqualitedemandeurindust_qualitedemandeurindust DE LA QUALITE DEMANDEUR
        $idqualitedemandeurindust_qualitedemandeurindust = substr($_POST['qualiteDemandeurindust'], 2);
    } else {
        $idqualitedemandeurindust_qualitedemandeurindust = 1; //C'EST UNE LISTE DEROULANTE OBLIGATOIRE IL DOIT Y AVOIR UNE VALEUR DANS LE CAS OU ON EST ACADEMIQUE
    }
    if (!empty($_POST['nomEntreprise'])) {
        $nomEntreprise = stripslashes(Securite::bdd($_POST['nomEntreprise']));
    }
    if (!empty($_POST['typeEntreprise'])) {
        $typeEntreprise = $_POST['typeEntreprise'];
        $idtypeentreprise_typeentreprise = substr($typeEntreprise, 2);
    }
    if (!empty($_POST['secteurActivite'])) {
        $secteuractivite = $_POST['secteurActivite'];
        //RECUPERATION DE L'$idsecteuractivite_secteuractivite CORRESPONDANT A LA VALEUR SELECTIONNE SUR LA LISTE $secteurActivite ;
        $idsecteuractivite_secteuractivite = substr($secteuractivite, 2);
    }
    if (!empty($_POST['valeurNomResponsable1']) && !empty($_POST['valeurNomResponsable1'])) {//CAS INDUSTRIEL
        $nomresponsable = stripslashes(Securite::bdd($_POST['valeurNomResponsable1']));
    } elseif (!empty($_POST['nomresponsable']) && !empty($_POST['nomresponsable'])) {//CAS ACADEMIQUE
        $nomresponsable = stripslashes(Securite::bdd($_POST['nomresponsable']));
    } else {
        $nomresponsable = '';
    }
    //------------------------------------------------------------------------------------------------------------
    //                                       FIN TYPE INDUSTRIEL
    //------------------------------------------------------------------------------------------------------------
    if (!empty($_POST['valeurEmailResponsable1']) && !empty($_POST['valeurEmailResponsable1'])) {//CAS INDUSTRIEL
        $mailresponsable = securite::bdd($_POST['valeurEmailResponsable1']);
        $_SESSION['emailResponsable'] = $mailresponsable;
    } elseif (!empty($_POST['emailResponsable'])) {//CAS ACADEMIQUE
        $mailresponsable = Securite::bdd($_POST['emailResponsable']);
        $_SESSION['emailResponsable'] = $mailresponsable;
    } else {
        $mailresponsable = '';
    }
    //RECUPERATION DE l'idutilisateurtype_utilisateurtype (TYPE UTILISATEUR)
    if ($typeuser == TXT_ACADEMIQUECONTACT && !empty($idcentrale_centrale)) {//CAS OU JE SUIS ACADEMIQUE ET DANS UNE CENTRALE
//------------------------------------------------------------------------------------------------------------
//                                        INSERT  CAS ACADEMIQUE DEJA DANS UNE CENTRALE
//------------------------------------------------------------------------------------------------------------
        $utilisateur = new Utilisateuracademique($nom, $prenom, $entrepriselaboratoire, $adresse, $cp, $ville, $date, $tel, $fax, $nomresponsable, $mailresponsable, $idtypeutilisateur_typeutilisateur, $idPays, $idlogin_loginpassword, $iddisciplineScientifique, $idcentrale_centrale, $idqualitedemandeuraca_qualitedemandeuraca, $idtutelle_tutelle, $idemployeur_nomemployeur, $idautrestutelle_autrestutelle, $idautrediscipline_autredisciplinescientifique, $idautrenomemployeur_autrenomemployeur, $idautrecodeunite_autrecodeunite, $acronymelaboratoire);
        //INSERTION DE L'UTILISATEUR
        $manager->addUtilisateuracademique($utilisateur);
        $idlogin = $manager->getSingle("select max (idlogin) from loginpassword");
        $nomequipe = new UtilisateurNomEquipe($nomEquipe, $idlogin);
        $manager->updateLoginNomEquipe($nomequipe, $idlogin);
        nomEntete($email, $pseudo);
        $_SESSION['pseudo'] = $pseudo;
        include '../EmailContact.php';
        //FERMETURE DE LA CONNEXION
        BD::deconnecter();
        header('location: /' . REPERTOIRE . '/compte/' . $lang);
    } elseif ($typeuser == TXT_ACADEMIQUECONTACT && empty($idcentrale_centrale)) {
        //------------------------------------------------------------------------------------------------------------
        //                                        INSERT  CAS ACADEMIQUE MAIS PAS DANS UNE CENTRALE
        //------------------------------------------------------------------------------------------------------------
        $utilisateur = new Utilisateuracadext($nom, $prenom, $entrepriselaboratoire, $adresse, $cp, $ville, $date, $tel, $fax, $nomresponsable, $mailresponsable, $idtypeutilisateur_typeutilisateur, $idPays, $idlogin_loginpassword, $iddisciplineScientifique, $idqualitedemandeuraca_qualitedemandeuraca, $idtutelle_tutelle, $idemployeur_nomemployeur, $idautrestutelle_autrestutelle, $idautrediscipline_autredisciplinescientifique, $idautrenomemployeur_autrenomemployeur, $idautrecodeunite_autrecodeunite, $acronymelaboratoire);
        //INSERTION DE L'UTILISATEUR        
        $manager->addUtilisateuracademiqueext($utilisateur);
        $idlogin = $manager->getSingle("select max (idlogin) from loginpassword");
        $nomequipe = new UtilisateurNomEquipe($nomEquipe, $idlogin);
        $manager->updateLoginNomEquipe($nomequipe, $idlogin);
        nomEntete($email, $pseudo);
        $_SESSION['pseudo'] = $pseudo;
        include '../EmailContact.php';
        //FERMETURE DE LA CONNEXION
        BD::deconnecter();
        header('location: /' . REPERTOIRE . '/compte/' . $lang);
    } elseif ($typeuser == TXT_INDUSTRIELCONTACT) {//TYPE INDUSTRIEL
        //------------------------------------------------------------------------------------------------------------
        //                                        INSERT  CAS INDUSTRIEL
        //------------------------------------------------------------------------------------------------------------
        $utilisateur = new Utilisateurindustriel($nom, $prenom, $entrepriselaboratoire, $adresse, $cp, $ville, $date, $tel, $fax, $nomresponsable, $mailresponsable, $nomEntreprise, $idtypeutilisateur_typeutilisateur, $idPays, $idlogin_loginpassword, $idqualitedemandeurindust_qualitedemandeurindust);
        //INSERTION DE L'UTILISATEUR
        $manager->addUtilisateurindustriel($utilisateur);
        $idlogin = $manager->getSingle("select max (idlogin) from loginpassword");
        $nomequipe = new UtilisateurNomEquipe($nomEquipe, $idlogin);
        $manager->updateLoginNomEquipe($nomequipe, $idlogin);
        BD::deconnecter();
        //MISE A JOUR DU NOM ET PRENOM DANS L'ENTETE DE LA PAGE
        nomEntete($email, $pseudo);
        $_SESSION['pseudo'] = $pseudo;
        $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
        $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
        // RECUPERATION DE L'idutilisateur
        $idUtilisateur = $manager->getSingle2("SELECT idutilisateur FROM utilisateur where idlogin_loginpassword=?", $idlogin_loginpassword);
        //INSERTION DANS LA TABLE APPARTIENT POUR LE TYPEENTREPRISE
        $appartient = new Appartient($idtypeentreprise_typeentreprise, $idUtilisateur);
        $manager->addAppartient($appartient);
        //INSERTION DANS LA TABLE INTERVIENT POUR LE SECTEUR D'ACTIVITE
        $intervient = new Intervient($idsecteuractivite_secteuractivite, $idUtilisateur);
        $manager->addIntervient($intervient);                
         //ENVOI DE L'EMAIL CONTACT
        include '../EmailContact.php';
        //REDIRECTION VERS LA PAGE D'ACCUEIL DU CONTACT FRAICHEMENT CREE
        header('location: /' . REPERTOIRE . '/compte/' . $lang);
        //FERMETURE DE LA CONNEXION
        BD::deconnecter();
    }
} else {
    header('location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
