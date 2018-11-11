<?php

session_start();
include_once '../decide-lang.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include '../class/Manager.php';
include '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../class/Securite.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    $pseudo = $_SESSION['pseudo'];
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'createProjetphase2.html') {
//------------------------------------------------------------------------------------------------------------
//										INSERT PHASE 1
//------------------------------------------------------------------------------------------------------------
    if (!empty($_POST['titreProjet'])) {
        $titreProjet = stripslashes(Securite::bdd($_POST['titreProjet']));
    }
    if (!empty($_POST['contextValeur'])) {
        $contexte = clean($_POST['contextValeur']);
    }
    if (!empty($_POST['descriptifValeur'])) {
        $descriptif = clean($_POST['descriptifValeur']);
    }
        
    if (empty($_POST['confid'])) {
        $confidentiel = "FALSE";
    } else {
        $confidentiel = $_POST['confid'];
    }

    date_default_timezone_set('Europe/London');
    $dateprojet = date("m,d,Y");
//RECUPERATION DE L'IDUTILISATEUR
    if (!empty($_SESSION['idutilisateur'])) {
        $idutilisateur_utilisateur = $_SESSION['idutilisateur'];
    } else {
        $idutilisateur_utilisateur = $manager->getSingle2("SELECT idutilisateur FROM loginpassword, utilisateur WHERE
	  idlogin_loginpassword = idlogin and pseudo =?", $_SESSION['pseudo']);
    }
    $idtypeprojet_typeprojet = $manager->getSingle2("select idtypeprojet from typeprojet where libelletype =?", 'n/a'); //"n/a" par défaut
// NUMERO DE PROJET
    $numero = $manager->getSingle("select max(numero) from projet");
    if (!empty($numero)) {
        $numProjet = createNumProjet($numero);
    } else {
        $numProjet = 'P-' . date("y") . '-' . '00001'; //CAS DU 1ER PROJET
    }
    $_SESSION['numprojet'] = $numProjet; //A GARDER BESOIN SUR L'EMAIL
    
    if (!empty($_POST['acronyme'])) {
        $acronyme = stripslashes(Securite::bdd($_POST['acronyme']));
    } else {
        $acronyme=$numProjet;
    }
//INSERSION EN BASE DE DONNEE
    $idprojet = $manager->getSingle("select max(idprojet) from projet") + 1;
    if (isset($_POST['centrale']) && !empty($_POST['centrale'])) {
        $idcentrale = (int) substr($_POST['centrale'], -1);
    } else {
        $idcentrale = AUTRECENTRALE;
    }

    if (!empty($_FILES['fichierProjet']['name'])) {
        $attachement = stripslashes(Securite::bdd($_FILES['fichierProjet']['name']));
        $dossier1 = '../upload/';
        $fichierPhase1 = basename($_FILES['fichierProjet']['name']);
        $taille_maxi1 = 1048576;
        $taille1 = filesize($_FILES['fichierProjet']['tmp_name']);
        $extensions = array('.pdf', '.PDF');
        $extension1 = strrchr($_FILES['fichierProjet']['name'], '.');
        if (!in_array($extension1, $extensions)) {//VERIFICATION DU FORMAT SI IL N'EST PAS BON ON SORT                
            $erreur1 = TXT_ERREURUPLOAD;
        } elseif ($taille1 > $taille_maxi1) {//VERIFICATION DE LA TAILLE SI ELLE EST >1mo ON SORT
            $erreur12 = TXT_ERREURTAILLEFICHIER;
        } elseif (!isset($erreur1) && !isset($erreur12)) {//S'il n'y a pas d'erreur, on upload
            if (move_uploaded_file($_FILES['fichierProjet']['tmp_name'], $dossier1 . $fichierPhase1)) {
                $erreur1 = '';
                chmod($dossier1 . $fichierPhase1, 0777);
            }
        }
    } else {
        $attachement = "";
    }
    $projet = new Projetphase1($idprojet, $titreProjet, $numProjet, $confidentiel, $descriptif, $dateprojet, $contexte, $idtypeprojet_typeprojet, $attachement, $acronyme);
    $manager->addProjetphase1($projet);
//RECUPERATION DE L'ID DU PROJET CREE
    $idProjet = $manager->getSingle("SELECT max(idprojet)FROM projet;");
    $_SESSION['idprojet'] = $idProjet; //A GARDER
    //MISE A JOUR DE LA TABLE CREER
    $creer = new Creer($idutilisateur_utilisateur, $idProjet);
    $manager->addCreer($creer);
    responsablePorteur($pseudo, $idprojet);
    //MISE A JOUR DE LA TABLE CONCERNE    
    if ($_POST ['enregistre'] == 'oui') {
        $concerne = new Concerne($idcentrale, $idProjet, ENATTENTEPHASE2, '');
        $_SESSION['idstatutprojet'] = ENATTENTEPHASE2;
    } else {
        $concerne = new Concerne($idcentrale, $idProjet, ACCEPTE, '');
        $_SESSION['idstatutprojet'] = ACCEPTE;
    }
    $manager->addConcerne($concerne);
    checkConcerne($idprojet, $idcentrale, ACCEPTE);

//------------------------------------------------------------------------------------------------------------
//										INSERT PHASE 2
//------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------
//                          CHAMPS  SANS TRAITEMENT
//------------------------------------------------------------------------------------------------------------   
    
    if (!empty($_POST['contactCentralAccueil'])) {
        $contactCentralAccueil = stripslashes(Securite::bdd($_POST['contactCentralAccueil']));
    } else {
        foreach ($conmpteacceuilDefault as $key => $value) {
            if ($idcentrale == $key) {
                $contactCentralAccueil = $manager->getSingle2(" SELECT  initcap(lower(CONCAT(prenom,'  ',nom))) FROM utilisateur WHERE idutilisateur=?", $value);
            }
        }
    }

    if (!empty($_POST['typeProjet'])) {
        $idtypeprojet_typeprojet = substr($_POST['typeProjet'], 2);
        $idlibelleFormation = $manager->getSingle2("select idtypeprojet from typeprojet where libelletype=?", "Formation");
        if ($idtypeprojet_typeprojet == $idlibelleFormation) {// ON A AFFAIRE A UN TYPE DE PROJET FORMATION
            if (!empty($_POST['typeFormation'])) {
                $idtypeformation = (int) substr($_POST['typeFormation'], 2);
                //INSERTION DANS LA TABLE PROJETTYPEPROJET
                $projettypeprojet = new Projettypeprojet($idtypeformation, $idprojet);
                $manager->addprojettypeprojet($projettypeprojet, $idprojet);
                if (!empty($_POST['nbHeure'])) {
                    $nbHeure = Securite::bdd($_POST['nbHeure']);
                } else {
                    $nbHeure = 0;
                }
                if (!empty($_POST['nbeleve'])) {
                    $nbeleve = Securite::bdd($_POST['nbeleve']);
                } else {
                    $nbeleve = 0;
                }
            } else {
                $idtypeformation = 1;
            }
        } else {
            $nbeleve = 0;
            $nbHeure = 0;
            $idtypeformation = 1;
        }
    } else {
        $idtypeprojet_typeprojet = 1;
        $nbeleve = 0;
        $nbHeure = 0;
        $idtypeformation = 1;
    }
    if (!empty($_POST['nomformateur'])) {
        $nomformateur = stripslashes(Securite::bdd($_POST['nomformateur']));
    } else {
        $nomformateur = '';
    }
    if (!empty($_POST['dateDebutTravaux'])) {
        $dateDebutTravaux = pg_escape_string($_POST['dateDebutTravaux']);
    } else {
        $dateDebutTravaux = date('Y-m-d');
    }
    if (!empty($_POST['dureeprojet'])) {
        $dureeprojet = stripslashes(Securite::bdd($_POST['dureeprojet']));
    } else {
        $dureeprojet = '';
    }
    if (!empty($_POST['centralepartenaireprojet'])) {
        $centralepartenaireprojet = stripslashes(Securite::bdd($_POST['centralepartenaireprojet']));
    } else {
        $centralepartenaireprojet = '';
    }
    if (!empty($_POST['partenaire_centrale'])) {
        $partenaire_projetBDD = 'TRUE';
    } else {
        $partenaire_projetBDD = 'FALSE';
    }
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TYPEPARTENAIRE 
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    if($_POST['nombrePartenaire']!=0){
        $arrayidtypepartenaire = array();
        for ($i = 0; $i < 9; $i++) {
            $j=$i+1;
            if(!empty($_POST['tp'.$i])){
                if(strlen($_POST['tp'.$i])==3){
                    $tp = (int)substr($_POST['tp'.$i],-1);            
                }else{
                    $tp = (int)substr($_POST['tp'.$i],0);
                }
                $rang = (int)substr($_POST['rang'.$j],-1);           
                if ($tp!=0) {
                    $arrayidtypepartenaire[$i]['typepartenaire'] = $tp;
                    $arrayidtypepartenaire[$j]['rang'] = $rang;               
                }
            }
        }        
        $arrayIdTypepartenaire = (array_values($arrayidtypepartenaire));   
        if(!empty($arrayIdTypepartenaire)){
            $manager->deleteProjetTypePartenaire($idprojet);
            for ($i = 0; $i < count($arrayIdTypepartenaire); $i++) {
                $j=$i+1;
                if(!empty($arrayIdTypepartenaire[$i]['typepartenaire'])){
                    $projetTP = new ProjetTypePartenaire($arrayIdTypepartenaire[$i]['typepartenaire'], $idprojet, $arrayIdTypepartenaire[$j]['rang']);               
                $manager->insertProjetTypePartenaire($projetTP);
                }
            }
        }
    }else{
        $manager->deleteProjetTypePartenaire($idprojet);    
        $_POST['typecentralepartenaire']=null;
    }
    
    
    
    
    
    if(isset($_POST['typecentralepartenaire']) && !empty($_POST['typecentralepartenaire'])){
        $idtypecentralepartenaire= (int)substr($_POST['typecentralepartenaire'],2,2);
    }else{
        $idtypecentralepartenaire=null;
    }
    
    if (!empty(strip_tags($_POST['desTechno']))) {
        $descriptifTechnologique = clean($_POST['desTechno']);
    } else {
        $descriptifTechnologique = '_';
    }
    if (!empty(strip_tags($_POST['verrouide']))) {
        $verrouidentifie = clean($_POST['verrouide']);
    } else {
        $verrouidentifie = '_';
    }    
    if (!empty(strip_tags($_POST['reussit']))) {
        $reussite = clean($_POST['reussit']);
    } else {
        $reussite = '_';
    }
    if (!empty($_POST['nbPlaque']) || $_POST['nbPlaque'] != 0) {
        $nbPlaque = Securite::bdd($_POST['nbPlaque']);
    } else {
        $nbPlaque = 0;
    }
    if (!empty($_POST['nbRun']) || $_POST['nbRun'] != 0) {
        $nbRun = Securite::bdd($_POST['nbRun']);
    } else {
        $nbRun = 0;
    }
    if (!empty($_POST['devis'])) {
        $devis = Securite::bdd($_POST['devis']);
    } else {
        $devis = '';
    }
    if (!empty($_POST['mailresp'])) {
        $mailresp = Securite::bdd(($_POST['mailresp']));
    } else {
        $mailresp = '';
    }
    if (!empty($_POST['refinterne'])) {
        $refinterne = stripslashes(Securite::bdd(($_POST['refinterne'])));
    } else {
        $refinterne = '';
    }
    $devtechnologique = stripslashes(Securite::bdd($_POST['devTechnologique']));
    if ($devtechnologique == FALSE) {
        $verrouidentifie = '';
    }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DES SOURCES DE FINANCEMENT (CADRE INSTITUTIONNEL)
//------------------------------------------------------------------------------------------------------------

    $nbsource = $manager->getSingle("select count(idsourcefinancement) from sourcefinancement");
    $arraylibellesourcefinancement = array();
    for ($i = 0; $i < $nbsource; $i++) {
        $sf = 'sf' . ($i + 1);
        if (!empty($_POST['' . $sf . ''])) {
            if (strlen($sf) < 4) {
                array_push($arraylibellesourcefinancement, $manager->getSingle2("select libellesourcefinancement from sourcefinancement where idsourcefinancement=? ", substr($sf, -1)));
            } else {
                array_push($arraylibellesourcefinancement, $manager->getSingle2("select libellesourcefinancement from sourcefinancement where idsourcefinancement=? ", substr($sf, -2)));
            }
        }
    }
    for ($i = 0; $i < count($arraylibellesourcefinancement); $i++) {
        if (!empty($arraylibellesourcefinancement[$i])) {
            $idsourcefinancement = $manager->getSingle2("select idsourcefinancement from sourcefinancement where libellesourcefinancement=? ", $arraylibellesourcefinancement[$i]);
            $projetSF = new Projetsourcefinancement($idprojet, $idsourcefinancement);
            $manager->insertProjetSF($projetSF); //AJOUT DES SOURCE DE FINANCEMENT DANS LA TABLE PROJETSOURCEFINANCEMENT AVEC L'IDPROJET
        }
    }
    for ($i = 1; $i <= $nbsource; $i++) {
        $champpost = 'acronymesourcesf' . $i;
        if (!empty($_POST['' . $champpost . ''])) {
            $idsourcefinancementacro = substr($champpost, -1); //RECUPERATION DU DERNIER CARACTERE acronymesourcef1-9
            $projetacro = new ProjetAcrosourcefinancement($idprojet, $_POST['' . $champpost . ''], $idsourcefinancementacro);
            $manager->updateProjetacrosourcefinancement($projetacro, $idprojet);
        }
    }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DES THEMATIQUE
//------------------------------------------------------------------------------------------------------------
    if (!empty($_POST['thematique'])) {
        $idthematique = substr($_POST['thematique'], 2);
        $libellethematique = $manager->getSingle2("select libellethematique from thematique where idthematique =?", $idthematique);
        if ($libellethematique != TXT_AUTRES) {//VALEUR DIFFERENTE DE "Autres"
            $idthematique_thematique = $manager->getSingle2("select idthematique from thematique where libellethematique =?", $libellethematique);
            $idautrethematique_autrethematique = 1; //valeur n/a
        } else {
            //------------------------------------------------------------------------------------------------------------
            //                                       TRAITEMENT DES AUTRES THEMATIQUE
            //------------------------------------------------------------------------------------------------------------
            $autreThematique = stripslashes(Securite::bdd($_POST['autreThematique']));
            $idautrethematique_autrethematique = $manager->getSingle("select max(idautrethematique) from autrethematique") + 1;
            $newautrethematique = new autrethematique($idautrethematique_autrethematique, $autreThematique);
            $manager->addautrethematique($newautrethematique);
            $idthematique_thematique = $manager->getSingle("select idthematique from thematique where libellethematique='Autres'");
        }
    } else {
        $idthematique_thematique = null;
        $idautrethematique_autrethematique = null;
    }
    
    /*if (!empty($_POST['nomPartenaire01'])) {
        $partenaire1 = stripslashes(Securite::bdd(($_POST['nomPartenaire01'])));
    } else {
        $partenaire1 = '';
    }*/
    $partenaire1='';
    $porteurprojet = $_POST['porteurprojet'];    
    if (!empty($_POST['dureeestimeprojet'])) {
        $dureeestime = stripslashes(Securite::bdd($_POST['dureeestimeprojet']));
    } else {
        $dureeestime = null;
    }

    if (!empty($_POST['choix2'])) {
        $periodestime = substr($_POST['choix2'], 2);
    } else {
        $periodestime = $manager->getSingle2("select periodestime from projet where idprojet = ?", $idprojet);
    }
    /*  --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
    /*                                                                            AUTRES CENTRALE                                                                                   */
    /*  --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    if ($_POST['etapeautrecentrale'] == 'TRUE') {
        $etapeautrecentrale = TRUE;
        if (!empty($_POST['autrecentrale'])) {//CENTRALE SELECTIONNE
            for ($i = 0; $i < count($_POST['autrecentrale']); $i++) {
                $idcentrale = $manager->getSingle2("select idcentrale from centrale where replace(libellecentrale,'-','') =?", $_POST['autrecentrale'][$i]);
                $projetautrecentrale = new Projetautrecentrale($idcentrale, $idprojet, FALSE);
                $manager->addprojetautrescentrale($projetautrecentrale);
            }
        }
        if (!empty($_POST['etautrecentrale'])) {//DESCRIPTION DE L'ETAPE
            $descriptionautrecentrale = clean($_POST['etautrecentrale']);
        } else {
            $descriptionautrecentrale = '';
        }
    } else {
        $etapeautrecentrale = 'FALSE';
        $descriptionautrecentrale = '';
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              CENTRALE DE PROXIMITE
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
    $centrale_proximiteBDD = $manager->getList2("SELECT idcentraleproximite FROM centraleproximiteprojet where idprojet=?", $idprojet);
    $centraleProximiteBDD = array();
    for ($i = 0; $i < count($centrale_proximiteBDD); $i++) {
        array_push($centraleProximiteBDD, 'cp' . $centrale_proximiteBDD[$i]['idcentraleproximite']);
    }
    $centrale_proximite = stripslashes(Securite::bdd($_POST['centrale_proximite']));
    if (!empty($_POST['descriptioncentraleproximite'])) {
        $descriptioncentraleproximiteBDD = $_POST['descriptioncentraleproximite'];
    }else{
        $descriptioncentraleproximiteBDD = '';
    }
    if ($_POST['centrale_proximite'] == 'TRUE') {
        if (!empty($_POST['centrale_Proximite'])) {
            if ($centraleProximiteBDD != $_POST['centrale_Proximite']) {
                $_SESSION['centraleproximitemodif'] = $_POST['centrale_Proximite'];
            } else {
                $_SESSION['centraleproximitemodif'] = '';
            }
            $manager->deletecentraleproximiteprojet($idprojet);           
            for ($i = 0; $i < count($_POST['centrale_Proximite']); $i++) {
                $idcentrale_proximite = substr($_POST['centrale_Proximite'][$i], 2);
                $centraleproximite = new CentraleProximiteProjet($idcentrale_proximite, $idprojet);
                $manager->addCentraleProximiteProjet($centraleproximite);
                //Controle si il y un email dans la centrale de proximité
                $idresponsable = (int)$manager->getSingle2("SELECT id_responsable_centrale_proximite FROM centraleproximite WHERE idcentraleproximite=?", $idcentrale_proximite);
                $emailCP=$manager->getSingle2("SELECT mail FROM utilisateur,loginpassword  WHERE idlogin=idlogin_loginpassword AND idutilisateur=?", $idresponsable);
                if(null!=$emailCP){//AFFECTATION COMME ADMINISTRATEUR LE RESPONSABLE DE LA CENTRALE DE PROXIMITE
                    //Vérification que l'id du reponsable n'as pas déjà été sété dans la BD
                    $oldIdResponsableCP = (int)$manager->getSinglebyArray("SELECT idutilisateur FROM utilisateuradministrateur WHERE idprojet=? AND idutilisateur=?", array($idprojet,$idresponsable));
                 
                    if($oldIdResponsableCP != $idresponsable){
                        $dateaffectation = date("Y-m-d");
                        $utilisateuradminprojet = new UtilisateurAdmin($idresponsable, $idprojet, $dateaffectation);
                        $manager->addUtilisateurAdmin($utilisateuradminprojet);
                    }
                }
            }
        } else {
            $_SESSION['centraleproximitemodif'] = '';
        }
        if (!empty($_POST['centraleproximitevaleur'])) {
            $descriptioncentraleproximite = clean($_POST['centraleproximitevaleur']);
            if ($descriptioncentraleproximiteBDD != $descriptioncentraleproximite) {
                $_SESSION['descriptioncentraleproximitemodif'] = $descriptioncentraleproximite;
            } else {
                $_SESSION['descriptioncentraleproximitemodif'] = '';
                $descriptioncentraleproximite = $descriptioncentraleproximiteBDD;
            }
        } else {
            $_SESSION['descriptioncentraleproximitemodif'] = '';
            $descriptioncentraleproximite = $descriptioncentraleproximiteBDD;
        }
    }else{
        $_SESSION['descriptioncentraleproximitemodif'] = '';
        $descriptioncentraleproximite = '_';
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              FIN DES CENTRALES DE PROXIMITE
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
   
    if (!empty($_FILES['fichierphase2']['name'])) {
        $attachementdesc = stripslashes(Securite::bdd($_FILES['fichierphase2']['name']));
        $dossier = '../uploaddesc/';
        $fichierPhase = basename($_FILES['fichierphase2']['name']);
        $taille_maxi = 1048576;
        $taille = filesize($_FILES['fichierphase2']['tmp_name']);
        $extensions = array('.pdf', '.PDF');
        $extension = strrchr($_FILES['fichierphase2']['name'], '.');
        if (!in_array($extension, $extensions)) {//VERIFICATION DU FORMAT SI IL N'EST PAS BON ON SORT
            $erreur2 = TXT_ERREURUPLOAD;
        } elseif ($taille > $taille_maxi) {//VERIFICATION DE LA TAILLE SI ELLE EST >1mo ON SORT
            $erreur2 = TXT_ERREURTAILLEFICHIER;
        } elseif (!isset($erreur2)) {//S'il n'y a pas d'erreur, on upload
            if (move_uploaded_file($_FILES['fichierphase2']['tmp_name'], $dossier . $fichierPhase)) {
                $erreur2 = '';
                chmod($dossier . $fichierPhase, 0777);
            }
        }
    } else {
        $attachementdesc = $manager->getSingle2("select attachementdesc from projet where idprojet =?", $idprojet);
        if (empty($attachementdesc)) {
            $attachementdesc = '';
        }
    }

    $interneexterne = null;
    $internationalNational = null;
//------------------------------------------------------------------------------------------------------------
//              Traitement de la question des centrale partenaire du projet
//------------------------------------------------------------------------------------------------------------        
        $partenaire_projet = $_POST['question_centrale'];
        if ($partenaire_projetBDD != $partenaire_projet) {
            if ($partenaire_projet == 'TRUE') {
                $_SESSION['partenairerprojetmodif'] = TXT_OUI;
                $partenaire_projetBDD = TXT_OUI;
            } else {
                $_SESSION['partenairerprojetmodif'] = TXT_NON;
                $partenaire_projetBDD = TXT_NON;
            }
        } else {
            $_SESSION['partenairerprojetmodif'] = '';
        }
//------------------------------------------------------------------------------------------------------------
//              Traitement des centrale partenaire du projet
//------------------------------------------------------------------------------------------------------------        
        if(isset($_POST['centraleRenatech'])&& $_POST['centraleRenatech']!=-1){
            if(strlen($_POST['centraleRenatech'])==3){
                $centraleRenatech= intval(substr($_POST['centraleRenatech'],2,1));
            }else{
                $centraleRenatech= intval(substr($_POST['centraleRenatech'],2,2));
            }
        }else{
            $centraleRenatech= null;
        }

//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DU PROJETPHASE2
//------------------------------------------------------------------------------------------------------------    
    $projetphase2 = new Projetphase2($contactCentralAccueil, $idtypeprojet_typeprojet, $nbHeure, $dateDebutTravaux, $dureeprojet,  $centralepartenaireprojet, $idthematique_thematique, 
            $idautrethematique_autrethematique, $descriptifTechnologique, $attachementdesc, $verrouidentifie, $nbPlaque, $nbRun, $devis, $mailresp, $reussite, $refinterne, $devtechnologique, $nbeleve, $nomformateur, 
            $partenaire1, $porteurprojet,  $dureeestime, $descriptionautrecentrale, $etapeautrecentrale, $centrale_proximite, $descriptioncentraleproximite, $interneexterne, $internationalNational,$idtypecentralepartenaire
            , $partenaire_projet,$centraleRenatech);
    $manager->updateProjetphase2($projetphase2, $idprojet);
    $admin = $manager->getSingle2("select administrateur from utilisateur where idutilisateur=?", $idutilisateur_utilisateur);
    //TRAITEMENT DU CAS OU L'UTLISATEUR QUI CREER LE PROJET EST DEJA ADMINISTRATEUR DE PROJET
    if ($admin == 1) {
        ajouteAdministrationProjet($idutilisateur_utilisateur);
    }
    //TRAITEMENT DU CAS OU L'UTLISATEUR A UN RESPONSABLE QUI EST DANS L'APPLICATION DANS CE CAS LE RESPONSABLE EST ADMINISTRATEUR DE SES PROJETS
    $mailResponsable = $manager->getSingle2("select mailresponsable from utilisateur where idutilisateur=?", $idutilisateur_utilisateur);
    if (!empty($mailResponsable)) {
        $idresponsable = $manager->getSingle2("select idutilisateur from utilisateur,loginpassword  where idlogin=idlogin_loginpassword and mail=? ", trim($mailResponsable));
        if (!empty($idresponsable)) {
            ajouteResponsableAdministrationProjet($idutilisateur_utilisateur, $idresponsable);
        }
    }

    //MISE A JOUR DATE DEBUT DU PROJET
    $datedebutprojet = new DateDebutProjet($idprojet, date('Y-m-d'));
    $manager->updateDateDebutProjet($datedebutprojet, $idprojet);
//------------------------------------------------------------------------------
//                          PARTENAIRE PROJET
//------------------------------------------------------------------------------    
    
    if (!empty($_POST['nombrePartenaire']) && $_POST['nombrePartenaire'] != 0) {//SI LE NOMBRE DE PARTENAIRE EST >0
        $nombrePartenaire = $_POST['nombrePartenaire'];        
        for ($i = 0; $i < $nombrePartenaire - 1; $i++) {
            $nomLaboEntreprise = $_POST['nomLaboEntreprise' . $i];            
            $idtypepartenaire = substr($_POST['tp'.$i], 2,2);
            if (!empty($_POST[$_POST['nomLaboEntreprise' . $i]])) {
                $nomLaboEntreprise = stripslashes(Securite::bdd(($_POST[$_POST['nomLaboEntreprise' . $i]])));                
            }
            
            $idpartenaire = $manager->getSingle("select max (idpartenaire) from partenaireprojet") + 1;             
           
            $newpartenaireprojet = new Partenaireprojet($idpartenaire,  $nomLaboEntreprise);
            $manager->addpartenaireprojet($newpartenaireprojet);
            
            
            $newprojetpartenaire = new Projetpartenaire($idpartenaire, $idprojet, $idtypepartenaire);
            $manager->addprojetpartenaire($newprojetpartenaire);
            
        }
    } else {
        //IL N'A PAS DE PARTENAIRE SELECTIONNE IL FAUT SUPPRIMER LES PARTENAIRES PROJET DANS LES TABLE PARTENAIREPROJET ET PROJETPARTENAIRE
        //------------------------------------------------------------------------------------------------------------------------------------------------------
        $nombrePartenaire = 0;
        $manager->deleteprojetpartenaire($idprojet);
        //RECUPERATION DU PROJET DANS LA TABLE PARTENAIREPROJET QUI N'AS PAS DE REFERENCE DANS LA TABLE PROJETPARTENAIRE-->SUPPRESSION ENREGISTEMENT VIDE
        $idpartenaire = $manager->getList("SELECT idpartenaire FROM  partenaireprojet where idpartenaire not in (select idpartenaire_partenaireprojet from projetpartenaire)");
        //SUPPRESSION DES LIGNES CORRESPONDANTES
        if (count($idpartenaire) > 0) {
            for ($i = 0; $i < count($idpartenaire); $i++) {
                $manager->deletepartenaireprojet($idpartenaire[$i]['idpartenaire']);
            }
        }
        //EFFACAGE DU ER PARTENAIRE DANS LA TABLE PROJET
        $centralepartenaireprojet = $manager->getSingle2("select centralepartenaireprojet from projet where idprojet=?", $idprojet);
        $partenaire1 = $manager->getSingle2("select partenaire1 from projet where idprojet=?", $idprojet);
        $partenairefromprojet = new Partenairefromprojet($centralepartenaireprojet, $partenaire1);
        $manager->updatepartenairefromprojet($partenairefromprojet, $idprojet);
        //------------------------------------------------------------------------------------------------------------------------------------------------------
        //------------------------------------------------------------------------------------------------------------------------------------------------------					}
    }
//------------------------------------------------------------------------------------------------------------------------
//			 MISE A JOUR DE LA TABLE RESSOURCEPROJET  ON EFFACE TOUTES LES RESSOURCES SELECTIONNEES
//------------------------------------------------------------------------------------------------------------------------
    $ressources = '';
    if ($lang == 'fr') {
        $slibelleressource = 'libelleressource';
    } else {
        $slibelleressource = 'libelleressourceen';
    }
    if (!empty($_POST['ressource'])) {
        $ressource = $_POST['ressource'];
        foreach ($ressource as $chkbx) {
            if($lang=='fr'){
                $arrayressource = $manager->getListbyArray("SELECT idressource FROM ressource where libelleressource =?", array($chkbx));
            }else{
                $arrayressource = $manager->getListbyArray("SELECT idressource FROM ressource where libelleressourceen =?", array($chkbx));
            }
            $ressources .=$chkbx . ',';
            for ($i = 0; $i < count($arrayressource); $i++) {
                $idressource_ressource = $arrayressource[$i]['idressource'];
                $ressourceprojet = new Ressourceprojet($idprojet, $idressource_ressource);
                $manager->addressourceprojet($ressourceprojet);
            }
        }
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
//																																																							ACCUEIL CENTRALE
//---------------------------------------------------------------------------------------------------------------------------------------------------
//SUPPPRESSION DES PERSONNES DANS LA TABLE PROJETPERSONNEACCUEILCENTRALE
    $manager->deleteprojetpersonneaccueilcentrale($idprojet);
    //RECUPERATION DU PROJET DANS LA TABLE PERSONNEACCUEILCENTRALE QUI N'AS PAS DE REFERENCE DANS LA TABLE PROJETPERSONNEACCUEILCENTRALE
    $idpersonnecentrale = $manager->getList("SELECT idpersonneaccueilcentrale FROM  personneaccueilcentrale where idpersonneaccueilcentrale not in
	 (select idpersonneaccueilcentrale_personneaccueilcentrale from projetpersonneaccueilcentrale)");
    //SUPPRESSION DES LIGNES CORRESPONDANTES
    if (count($idpersonnecentrale) > 0) {
        for ($i = 0; $i < count($idpersonnecentrale); $i++) {
            $manager->deletepersonneaccueilcentrale($idpersonnecentrale[$i]['idpersonneaccueilcentrale']);
        }
    }
    $nombrePersonneCentrale = +($_POST['integerspinner']);
    for ($i = 0; $i < $nombrePersonneCentrale; $i++) {
        if (!empty($_POST['nomaccueilcentrale' . $i . ''])) {
            $nomAccueilcentrale = $_POST['nomaccueilcentrale' . $i . ''];
            $nomaccueilcentrale = stripslashes(Securite::bdd($nomAccueilcentrale));
        } else {
            $nomaccueilcentrale = TXT_UNDEFINENAME . $i;
        }
        if (!empty($_POST['prenomaccueilcentrale' . $i . ''])) {
            $prenomAccueilcentrale = $_POST['prenomaccueilcentrale' . $i . ''];
            $prenomaccueilcentrale = stripslashes(Securite::bdd($prenomAccueilcentrale));
        } else {
            $prenomaccueilcentrale = TXT_UNDEFINELASTNAME . $i;
        }
        if (!empty($_POST['qualiteaccueilcentrale' . $i . ''])) {
            $idqualitedemandeurAca = $_POST['qualiteaccueilcentrale' . $i . ''];
            $idqualitedemandeuraca = (int) substr($idqualitedemandeurAca, -1);
        }
        if (!empty($_POST['mailaccueilcentrale' . $i . ''])) {
            $mailaccueilcentrale = $_POST['mailaccueilcentrale' . $i . ''];
        } else {
            $mailaccueilcentrale = TXT_UNDEFINEMAIL . $i;
        }
        if (!empty($_POST['telaccueilcentrale' . $i . ''])) {
            $telaccueilcentrale = $_POST['telaccueilcentrale' . $i . ''];
        } else {
            $telaccueilcentrale = '';
        }
        if (!empty($_POST['connaissancetechnologiqueaccueil' . $i . ''])) {
            $connaissancetechnologiqueAccueil = $_POST['connaissancetechnologiqueaccueil' . $i . ''];
            $connaissancetechnologiqueaccueil = Securite::bdd($connaissancetechnologiqueAccueil);
        } else {
            $connaissancetechnologiqueaccueil = '';
        }
        //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //                          TRAITEMENT DES AUTRES QUALITE DOCTORANT,POSTDOC OU AUTRES SI AUTRES VALEUR DE AUTRES
        //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

        if ($idqualitedemandeuraca != PERMANENT) {
            if (!empty($_POST['autreQualite' . $i . ''])) {
                if ($_POST['autreQualite' . $i . ''] == 'qac' . IDAUTREQUALITE) {//cas autres
                    if (!empty($_POST['autresQualite' . $i . ''])) {
                        $libAutreQualite = clean($_POST['autresQualite' . $i . '']);
                        $idautrequalite = $manager->getSingle("select max(idautresqualite) from autresqualite") + 1;
                        $autresqualite = new Autresqualite($idautrequalite, $libAutreQualite); //CREATION DE L'ENTREE DANS LA TABLE AUTRESQUALITE
                        $manager->addAutresQualite($autresqualite);
                        $idpersonneQualite = (int) substr(trim($_POST['autreQualite' . $i . '']), -1);
                    } else {
                        $idpersonneQualite = IDNAAUTRESQUALITE;
                        $idautrequalite = IDNAAUTREQUALITE;
                    }
                } else {
                    if (!empty($_POST['autresQualite' . $i . ''])) {
                        $libAutreQualite = clean($_POST['autresQualite' . $i . '']);
                        $idautrequalite = $manager->getSingle("select max(idautresqualite) from autresqualite") + 1;
                        $autresqualite = new Autresqualite($idautrequalite, $libAutreQualite); //CREATION DE L'ENTREE DANS LA TABLE AUTRESQUALITE
                        $manager->addAutresQualite($autresqualite);
                        $idpersonneQualite = IDNAAUTRESQUALITE;
                    } else {
                        $idpersonneQualite = (int) substr(trim($_POST['autreQualite' . $i . '']), -1);
                        $idautrequalite = IDNAAUTREQUALITE;
                    }
                }
            }
        } else {
            $idautrequalite = IDNAAUTREQUALITE;
            $idpersonneQualite = IDNAAUTRESQUALITE;
        }
        //TRAITEMENT AJOUT DANS LA TABLE PERSONNEACCUEILCENTRALE
        $idpersonneaccueilcentrale = $manager->getSingle("select max(idpersonneaccueilcentrale) from Personneaccueilcentrale") + 1;
        $personne = new Personneaccueilcentrale($idpersonneaccueilcentrale, $nomaccueilcentrale, $prenomaccueilcentrale, $idqualitedemandeuraca, $mailaccueilcentrale, $telaccueilcentrale, trim($connaissancetechnologiqueAccueil), $idpersonneQualite, $idautrequalite);
        $manager->addPersonneaccueilcentrale($personne);
        //TRAITEMENT AJOUT DANS LA TABLE PROJETPERSONNEACCUEILCENTRALE
        $projetpersonneaccueilcentrale = new Projetpersonneaccueilcentrale($idprojet, $idpersonneaccueilcentrale);
        $manager->addprojetpersonneaccueilcentrale($projetpersonneaccueilcentrale);
    }
    if (empty($centralepartenaireprojet)) {
        $centralepartenaireprojet = false;
    }
    if (empty($centralepartenaireprojet)) {
        $centralepartenaireprojet = false;
    }
//------------------------------------------------------------------------------------------------------------------------
//			 MISE A JOUR DES FICHIERS UPLOADES ON VERIFIE L'ECART ENTRE LES NOMS INSCRIT DANS LA TABLE PROJET
//			 ET LES FICHIERS PRESENTS SUR LE SERVEUR, ON EFFACE CEUX QUI NE SONT PAS REFERENCES DANS LA TABLE
//			 PROJET
//------------------------------------------------------------------------------------------------------------------------
    $uploadProjet = $manager->getdataArray("select attachementdesc from projet where attachementdesc !=''");
    $listerepertoire = getDirectoryList("../uploaddesc");
//$listerepertoire = tableau contenant la liste des fichiers dans le répertoire upload
    $resultEcart = array_diff($listerepertoire, $uploadProjet);
//$resultEcart = tableau contenant l'écart entre les fichiers du répertoire distant et le noms des fichier contenu dans la table projet
    for ($i = 0; $i < count($listerepertoire); $i++) {
        if (in_array($listerepertoire[$i], $resultEcart)) { //Vérification si l'
            unlink('../uploaddesc/' . $listerepertoire[$i]); //Suppression du fichier non référencés dans la table projet
        }
    }
//---------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------    
    if (isset($erreur1)) {
        $concerne = new Concerne($idcentrale, $idProjet, ENATTENTEPHASE2, '');
        $manager->updateConcerne($concerne, $idprojet);
        header('Location: /' . REPERTOIRE . '/Upload_Errorsizephase1/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        BD::deconnecter();
        exit();
    } elseif (isset($erreur2)) {
        $concerne = new Concerne($idcentrale, $idProjet, ENATTENTEPHASE2, '');
        $manager->updateConcerne($concerne, $idprojet);
        header('Location: /' . REPERTOIRE . '/Upload_Errorsizephase2/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        BD::deconnecter();
    } else {
        include '../uploadprojetphase2.php';
        BD::deconnecter();
    }
} else {
    include_once '../decide-lang.php';
    header('Location:/' . REPERTOIRE . '/Login_Error/' . $lang);
}