<?php
session_start();
include_once '../decide-lang.php';
include '../class/Manager.php';
include '../class/Securite.php';
include_once '../outils/toolBox.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_GET['statut'])) {
    $idstatutprojet = $_GET['statut'];
}elseif (isset($_POST['idstatut'])) {
    $idstatutprojet = $_POST['idstatut'];
}
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DES DONNEES NON AFFECTEES DANS LA TABLE AUTRESQUALITE
//----------------------------------------------------------------------------------------------------------------------------------------------------------

$arrayAutresQualite = $manager->getList("select idautresqualite,libelleautresqualite from autresqualite where idautresqualite not in(select idautresqualite from personneaccueilcentrale)");
for ($i = 0; $i < count($arrayAutresQualite); $i++) {
    $autresQualite = new Autresqualite($arrayAutresQualite[$i]['idautresqualite'],$arrayAutresQualite[$i]['libelleautresqualite']);    
    $manager->deleteAutresQualite($autresQualite);
}
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//                          CHAMPS  SANS TRAITEMENT
//----------------------------------------------------------------------------------------------------------------------------------------------------------
if (isset($_POST['page_precedente'])) {
    if ($_POST['page_precedente'] == 'phase2.html' || $_POST['page_precedente'] == 'createProjetphase2.html') {
        if (!empty($_GET['idprojet'])) {
            $idprojet = $_GET['idprojet'];
        }
//----------------------------------------------------------------------------------------------------------------------------------------------------------        
//                            AFFECTATION D'UNE VARIABLE $cas POUR DETERMINER LE TAVAIL A EFFECTUER
//----------------------------------------------------------------------------------------------------------------------------------------------------------        
        if ($_POST['save'] == 'oui' && $_POST['maj'] == 'non') {//ENREGISTREMENT AVEC OU SANS AUTRE CENTRALE
            $cas = 'enregistrement';
        } elseif ($_POST['save'] == 'oui' && isset($_POST['chgstatut']) && $_POST['chgstatut'] == 'oui' || $_POST['save'] == 'oui' && isset($_POST['chgstatut']) && $_POST['chgstatut'] == 'non') {//ENREGISTREMENT cas ou on change de statut et on clique sur save on ne prend pas en compte le changement de statut
            $cas = 'enregistrement';
        } elseif ($_POST['maj'] == 'oui' && isset($_POST['chgstatut']) && $_POST['chgstatut'] == 'oui' || $_POST['maj'] == 'oui' && isset($_POST['chgstatut']) && $_POST['chgstatut'] == 'non') {//MISE A JOUR cas ou on a chnagé de statut et on a cliqué sur maj on ne pend pas en compte le changement de statut
            if ($_POST['etapeautrecentrale'] == 'TRUE') {//MISE A JOUR AVEC UNE ETAPE AUTRE CENTRALE
                $cas = 'mise a jourEmailAutreEmail';
            } else {//MISE A JOUR SANS UNE ETAPE AUTRE CENTRALE
                $cas = 'mise a jourEmail';
            }
        } elseif ($_POST['save'] == 'non' && isset($_POST['chgstatut']) && $_POST['chgstatut'] == 'oui') {//CHANGEMENT DE STATUT
            $cas = 'changement de statut';
        } elseif ($_POST['maj'] == 'non' && isset($_POST['chgstatut']) && $_POST['chgstatut'] == 'oui') {//CHANGEMENT DE STATUT
            $cas = 'changement de statut';
        } elseif ($_POST['save'] == 'non' && $_POST['maj'] == 'oui') {//MISE A JOUR
            if ($_POST['etapeautrecentrale'] == 'TRUE') {//MISE A JOUR AVEC UNE ETAPE AUTRE CENTRALE                
                $cas = 'mise a jourEmailAutreEmail';
            } elseif ($_POST['etapeautrecentrale'] == 'FALSE') {//MISE A JOUR SANS UNE ETAPE AUTRE CENTRALE
                $cas = 'mise a jourEmail';
            }
        } elseif ($_POST['save'] == 'non' && $_POST['maj'] == 'non') {//VALIDATION APRES UNE SAUVEGARDE
            if ($_POST['etapeautrecentrale'] == 'TRUE') {//VALIDATION AVEC UNE ETAPE AUTRE CENTRALE
                $cas = 'creationprojetphase2etape';
            } elseif ($_POST['etapeautrecentrale'] == 'FALSE') {//VALIDATION SANS UNE ETAPE AUTRE CENTRALE
                $cas = 'creerprojetphase2';
            }
        }
//----------------------------------------------------------------------------------------------------------------------------------------------------------
//                            FIN AFFECTATION D'UNE VARIABLE $cas
//----------------------------------------------------------------------------------------------------------------------------------------------------------    
        $IDCENTRALEUSER = $manager->getSingle2("select idcentrale_centrale from utilisateur, loginpassword where idlogin_loginpassword=idlogin and pseudo=? ", $_SESSION['pseudo']);
        if (!empty($_POST['datemodifstatut'])) {
            $datemodifstatut = $_POST['datemodifstatut'];
        } else {
            $datemodifstatut = date('Y-m-d');
        }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------
//                       AFFECTATION DE LA  DATE DE MISE A JOUR DU PROJET DANS TOUS LES CAS
//-----------------------------------------------------------------------------------------------------------------------------------------------------------
        $datemajprojet = date('Y-m-d');
        $datemaj = new DateMajProjet($idprojet, $datemajprojet);
        $manager->updateDatemajProjet($datemaj, $idprojet);
//------------------------------------------------------------------------------------------------------------------------------------------------------------
//                      TRAITEMENT DE LA PARTIE 1 DANS LE CAS D'UNE SAUVEGARDE D'UNE VALIDATION OU D'UNE MISE A JOUR
//------------------------------------------------------------------------------------------------------------------------------------------------------------        
        include_once '../modifBase/updatePhase2succincte.php';
//------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                             TRAITEMENT DE LA PARTIE PHASE 2
//------------------------------------------------------------------------------------------------------------------------------------------------------------   
        $rowprojet = $manager->getList2("select contactscentraleaccueil,idtypeprojet_typeprojet,typeFormation,emailrespdevis,idthematique_thematique,attachementdesc,idautrethematique_autrethematique,nbheure,porteurprojet,envoidevis,nbeleve,datedebuttravaux,dureeprojet,descriptiftechnologique,idperiodicite_periodicite,centralepartenaireprojet,nbplaque,nbrun,nomformateur,refinterneprojet,partenaire1,dureeestime,periodestime,devtechnologique,verrouidentifiee,descriptionautrecentrale,descriptioncentraleproximite,reussite from projet where idprojet=?", $idprojet);
        $contactscentraleaccueilBDD = $rowprojet[0]['contactscentraleaccueil'];
        $typeprojetBDD = (int) $rowprojet[0]['idtypeprojet_typeprojet'];
        if (!empty($rowprojet[0]['typeFormation'])) {
            $typeformationBDD = $rowprojet[0]['typeFormation'];
        } else {
            $typeformationBDD = '';
        }
        if (!empty($rowprojet[0]['nbheure'])) {
            $nbHeureBDD = (int) $rowprojet[0]['nbheure'];
        } else {
            $nbHeureBDD = '';
        }
        if (!empty($rowprojet[0]['nbeleve'])) {
            $nbeleveBDD = (int) $rowprojet[0]['nbeleve'];
        } else {
            $nbeleveBDD = '';
        }
        if (!empty($rowprojet[0]['nomformateur'])) {
            $nomformateurBDD = $rowprojet[0]['nomformateur'];
        } else {
            $nomformateurBDD = '';
        }
        if (!empty($rowprojet[0]['dureeprojet'])) {
            $dureeProjetBDD = (int) $rowprojet[0]['dureeprojet'];
        } else {
            $dureeProjetBDD = '';
        }
        if (!empty($rowprojet[0]['idperiodicite_periodicite'])) {
            $idperiodBDD = (int) $rowprojet[0]['idperiodicite_periodicite'];
        } else {
            $idperiodBDD = null;
        }
        if ($rowprojet[0]['porteurprojet']) {
            $porteurprojetBDD = 'TRUE';
        } else {
            $porteurprojetBDD = 'FALSE';
        }
        if (!empty($rowprojet[0]['centralepartenaireprojet'])) {
            $centralepartenaireprojetBDD = $rowprojet[0]['centralepartenaireprojet'];
        } else {
            $centralepartenaireprojetBDD = '';
        }
        if (!empty($rowprojet[0]['partenaire1'])) {
            $partenaire1BDD = $rowprojet[0]['partenaire1'];
        } else {
            $partenaire1BDD = '';
        }
        //RECUPERATION DU NOMBRE DE PARTENAIRE A PARTIR DE LA TABLE PROJETPARTENAIRE        
        $arraypartenaireBDD = $manager->getList2("SELECT nomlaboentreprise,nompartenaire FROM  projetpartenaire,partenaireprojet WHERE idpartenaire_partenaireprojet = idpartenaire and  idprojet_projet=?", $idprojet);
        $nombrePartenaireBDD = count($arraypartenaireBDD);
        $partenairesBDD = '';
        if (!empty($nombrePartenaireBDD)) {
            for ($i = 0; $i < count($arraypartenaireBDD); $i++) {
                $partenairesBDD .=$arraypartenaireBDD[$i]['nompartenaire'] . ' - ' . $arraypartenaireBDD[$i]['nomlaboentreprise'] . ' - ';
            }
            $sPartenairesBDD = substr(trim($partenairesBDD), 0, -1);
        } else {
            $sPartenairesBDD = '';
        }
        if (!empty($rowprojet[0]['idthematique_thematique'])) {
            $idthematiqueBDD = $rowprojet[0]['idthematique_thematique'];
        } else {
            $idthematiqueBDD = '';
        }
        if (!empty($rowprojet[0]['idautrethematique_autrethematique']) && $rowprojet[0]['idautrethematique_autrethematique'] != NAAUTRETHEMATIQUE) {
            $libelleautrethematiqueBDD = $manager->getSingle2("select libelleautrethematique from autrethematique where idautrethematique=?", $rowprojet[0]['idautrethematique_autrethematique']);
        } else {
            $libelleautrethematiqueBDD = '';
        }
        if (!empty($rowprojet[0]['datedebuttravaux'])) {
            $dateDebutTravauxBDD = $rowprojet[0]['datedebuttravaux'];
        } else {
            $dateDebutTravauxBDD = '';
        }
        if (!empty($rowprojet[0]['dureeestime'])) {
            $dureeestimeBDD = $rowprojet[0]['dureeestime'];
        } else {
            $dureeestimeBDD = '';
        }
        if (!empty($rowprojet[0]['periodestime'])) {
            $idperiodestimeBDD = $rowprojet[0]['periodestime'];
        } else {
            $idperiodestimeBDD = null;
        }

        //RECUPERATION DU NOMBRE DE "Personne susceptible de travailler en salle blanche sur ce projet"
        $arraypersonnecentraleBDD = 
        $manager->getList2(""
                . "SELECT "
                . "nomaccueilcentrale,"
                . "prenomaccueilcentrale,"
                . "mailaccueilcentrale,"
                . "telaccueilcentrale,"
                . "connaissancetechnologiqueaccueil,"
                . "libellequalitedemandeuraca,"
                . "libellequalitedemandeuracaen "
                . "FROM projetpersonneaccueilcentrale,personneaccueilcentrale,qualitedemandeuraca "
                . "WHERE idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale "
                . "AND idqualitedemandeuraca_qualitedemandeuraca = idqualitedemandeuraca "
                . "AND idprojet_projet =?", $idprojet);
        $nombrePersonnecentraleBDD = count($arraypersonnecentraleBDD);
        $personnecentraleBDD = '';
        if (!empty($nombrePersonnecentraleBDD)) {
            for ($i = 0; $i < $nombrePersonnecentraleBDD; $i++) {
                if ($lang == 'fr') {
                    $personnecentraleBDD .=$arraypersonnecentraleBDD[$i]['nomaccueilcentrale'] . ' - ' . $arraypersonnecentraleBDD[$i]['prenomaccueilcentrale'] . ' - ' . $arraypersonnecentraleBDD[$i]['mailaccueilcentrale'] .
                            ' - ' . $arraypersonnecentraleBDD[$i]['telaccueilcentrale'] . ' - ' . $arraypersonnecentraleBDD[$i]['libellequalitedemandeuraca'] . '-';
                } else {
                    $personnecentraleBDD .=$arraypersonnecentraleBDD[$i]['nomaccueilcentrale'] . ' - ' . $arraypersonnecentraleBDD[$i]['prenomaccueilcentrale'] . ' - ' . $arraypersonnecentraleBDD[$i]['mailaccueilcentrale'] .
                            ' - ' . $arraypersonnecentraleBDD[$i]['telaccueilcentrale'] . ' - ' . $arraypersonnecentraleBDD[$i]['libellequalitedemandeuracaen'] . '-';
                }
            }
            $personneCentraleBDD = TXT_PERSONNEACCUEILCENTRALE . ': ' . substr(trim($personnecentraleBDD), 0, -1);
        } else {
            $personnecentraleBDD = '';
        }
//------------------------------------------------------------------------------------------------------------
//                          TRAITEMENT DES RESSOURCES        
//------------------------------------------------------------------------------------------------------------
        if ($lang == 'fr') {
            $slibelleressource = 'libelleressource';
        } else {
            $slibelleressource = 'libelleressourceen';
        }
        $arrayRESSOURCEBDD = $manager->getList2("SELECT $slibelleressource FROM ressource,ressourceprojet WHERE idressource_ressource = idressource AND idprojet_projet=? order by idressource_ressource asc", $idprojet);
        $arrayRessourceBDD = array();
        for ($i = 0; $i < count($arrayRESSOURCEBDD); $i++) {
            array_push($arrayRessourceBDD, $arrayRESSOURCEBDD[$i]['' . $slibelleressource . '']);
        }
//------------------------------------------------------------------------------------------------------------
//                          TRAITEMENT DU DESCRIPTIF TECHNOLOGIQUE        
//------------------------------------------------------------------------------------------------------------
        if (!empty($rowprojet[0]['descriptiftechnologique'])) {
            $descriptiftechnologiqueBDD = $rowprojet[0]['descriptiftechnologique'];
        } else {
            $descriptiftechnologiqueBDD = null;
        }

        if (!empty($rowprojet[0]['attachementdesc'])) {
            $attachementdescBDD = $rowprojet[0]['attachementdesc'];
        } else {
            $attachementdescBDD = '';
        }

//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DU CONTACT CENTRALE D'ACCUEIL
//------------------------------------------------------------------------------------------------------------                
        if (!empty($_POST['contactCentralAccueil'])) {
            $contactCentralAccueil = stripslashes(Securite::bdd($_POST['contactCentralAccueil']));
            if ($contactscentraleaccueilBDD != $contactCentralAccueil) {
                $_SESSION['contactcentralaccueilmodif'] = $contactCentralAccueil;
            } else {
                $_SESSION['contactcentralaccueilmodif'] = '';
            }
        } elseif (!empty($rowprojet[0]['contactscentraleaccueil'])) {
            $contactCentralAccueil = $rowprojet[0]['contactscentraleaccueil'];
            $_SESSION['contactcentralaccueilmodif'] = '';
        } else {
            $contactCentralAccueil = '';
            $_SESSION['contactcentralaccueilmodif'] = '';
        }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DES TYPES DE PROJET
//------------------------------------------------------------------------------------------------------------
        if (!empty($_POST['typeProjet'])) {
            $idtypeprojet_typeprojet = (int) substr($_POST['typeProjet'], 2);
            if ($typeprojetBDD != $idtypeprojet_typeprojet) {
                $_SESSION['idtypeprojetmodif'] = $idtypeprojet_typeprojet;
            } else {
                $_SESSION['idtypeprojetmodif'] = '';
            }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DES FORMATIONS
//------------------------------------------------------------------------------------------------------------
            if ($idtypeprojet_typeprojet == FORMATION) {
                $idtypeformation = (int) substr($_POST['typeFormation'], 2);
                if ($typeformationBDD != $idtypeformation) {
                    $_SESSION['typeFormationmodif'] = $idtypeformation;
                } else {
                    $_SESSION['typeFormationmodif'] = '';
                }
                if (empty($idtypeformation)) {
                    $idtypeformation = $manager->getSingle2("select idtypeformation from projettypeprojet where idprojet=?", $idprojet);
                    $_SESSION['typeFormationmodif'] = '';
                }
                //ON EFFACE LE REFERENE AU PROJETDANS LA TABLE PROJETRTYPEPROJET
                $manager->deleteprojettypeprojet($idprojet);
                //INSERTION DANS LA TABLE PROJETTYPEPROJET
                $projettypeprojet = new Projettypeprojet($idtypeformation, $idprojet);
                $manager->addprojettypeprojet($projettypeprojet, $idprojet);
                if (!empty($_POST['nbHeure'])) {
                    $nbHeure = Securite::bdd($_POST['nbHeure']);
                    if ($nbHeureBDD != $nbHeure) {
                        $_SESSION['nbheuremodif'] = $nbHeure;
                    } else {
                        $_SESSION['nbheuremodif'] = '';
                    }
                } else {
                    $nbHeure = 0;
                    $_SESSION['nbheuremodif'] = '';
                }
                if (!empty($_POST['nbeleve'])) {
                    $nbeleve = Securite::bdd($_POST['nbeleve']);
                    if ($nbeleveBDD != $nbeleve) {
                        $_SESSION['nbelevemodif'] = $nbeleve;
                    } else {
                        $_SESSION['nbelevemodif'] = '';
                    }
                } else {
                    $nbeleve = 0;
                    $_SESSION['nbelevemodif'] = '';
                }
                if (!empty($_POST['nomformateur'])) {
                    $nomformateur = Securite::bdd($_POST['nomformateur']);
                    if ($nomformateurBDD != $nomformateur) {
                        $_SESSION['nomformateurmodif'] = $nomformateur;
                    } else {
                        $_SESSION['nomformateurmodif'] = '';
                    }
                } else {
                    $nomformateur = '';
                    $_SESSION['nomformateurmodif'] = '';
                }
            } else {
                $nbHeure = 0;
                $_SESSION['nbheuremodif'] = '';
                $nbeleve = 0;
                $_SESSION['nbelevemodif'] = '';
                $nomformateur = '';
                $_SESSION['nomformateurmodif'] = '';
                //ON EFFACE LE REFERENE AU PROJETDANS LA TABLE PROJETRTYPEPROJET
                $manager->deleteprojettypeprojet($idprojet);
                $typeFormation = 1;
                $_SESSION['typeFormationmodif'] = '';
            }
        } else {
            $idtypeprojet_typeprojet = 1;
            $_SESSION['idtypeprojetmodif'] = '';
            $typeFormation = 1;
            $_SESSION['typeFormationmodif'] = '';
            $nbHeure = 0;
            $_SESSION['nbheuremodif'] = '';
            $nbeleve = 0;
            $_SESSION['nbelevemodif'] = '';
            $nomformateur = '';
            $_SESSION['nomformateurmodif'] = '';
        }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DES DUREES DE PROJET
//------------------------------------------------------------------------------------------------------------        
        if (!empty($_POST['dureeprojet'])) {
            $dureeprojet = stripslashes(Securite::bdd($_POST['dureeprojet']));
            if ($dureeProjetBDD != $dureeprojet) {
                $_SESSION['dureeprojetmodif'] = $dureeprojet;
            } else {
                $_SESSION['dureeprojetmodif'] = '';
            }
        } elseif (!empty($rowprojet[0]['dureeprojet']) || $rowprojet[0]['dureeprojet'] != 0) {
            $dureeprojet = $rowprojet[0]['dureeprojet'];
            $_SESSION['dureeprojetmodif'] = '';
        } else {
            $dureeprojet = '';
            $_SESSION['dureeprojetmodif'] = '';
        }
        if (!empty($_POST['choix'])) {
            $idperiodicite_periodicite = (int) substr($_POST['choix'], 2);
            if ($idperiodBDD != $idperiodicite_periodicite) {
                $_SESSION['idperiodmodif'] = $idperiodicite_periodicite;
            } else {
                $_SESSION['idperiodmodif'] = '';
            }
        } else {
            $idperiodicite_periodicite = $manager->getSingle2("select idperiodicite_periodicite from projet where idprojet = ?", $idprojet);
            $_SESSION['idperiodmodif'] = '';
            $_SESSION['dureeprojetmodif'] = '';
        }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DES SOURCES DE FINANCEMENT (CADRE INSTITUTIONNEL)
//------------------------------------------------------------------------------------------------------------
        if ($lang == 'fr') {
            $arraySFBDD = $manager->getList2("SELECT libellesourcefinancement FROM sourcefinancement,projetsourcefinancement WHERE idsourcefinancement_sourcefinancement = idsourcefinancement and idprojet_projet=?", $idprojet);
            $arraysfBDD = array();
            for ($i = 0; $i < count($arraySFBDD); $i++) {
                array_push($arraysfBDD, $arraySFBDD[$i]['libellesourcefinancement']);
            }
        } else {
            $arraySFBDD = $manager->getList2("SELECT libellesourcefinancementen FROM sourcefinancement,projetsourcefinancement WHERE idsourcefinancement_sourcefinancement = idsourcefinancement and idprojet_projet=?", $idprojet);
            $arraysfBDD = array();
            for ($i = 0; $i < count($arraySFBDD); $i++) {
                array_push($arraysfBDD, $arraySFBDD[$i]['libellesourcefinancementen']);
            }
        }
        $arrayAcroSFBDD = $manager->getList2("SELECT acronymesource,idsourcefinancement_sourcefinancement FROM projetsourcefinancement WHERE idprojet_projet=?", $idprojet);
        if (!empty($arrayAcroSFBDD)) {
            foreach ($arrayAcroSFBDD as $row) {
                $arrayAcrosfBDD[$row['idsourcefinancement_sourcefinancement']] = $row['acronymesource'];
            }
        } else {
            $arrayAcrosfBDD = array();
        }

        $nbsource = $manager->getList("select idsourcefinancement from sourcefinancement");
        if ($nbsource > 0) {
            $arraylibellesourcefinancement = array();
            $manager->deletesourcefinancementprojet($idprojet); //EFFACEMENT AU PREALABLE DES REFERENCES SOURCE FINANCEMENT AUX PROJETS
            $nombresource = count($nbsource);
            for ($i = 0; $i < $nombresource; $i++) {
                $sf = 'sf' . ($i + 1);
                if (!empty($_POST['' . $sf . ''])) {
                    if (strlen($sf) < 4) {
                        array_push($arraylibellesourcefinancement, $manager->getSingle2("select libellesourcefinancement from sourcefinancement where idsourcefinancement=? ", substr($sf, -1)));
                    } else {//si on a plus de 9 sources de financement sf10
                        array_push($arraylibellesourcefinancement, $manager->getSingle2("select libellesourcefinancement from sourcefinancement where idsourcefinancement=? ", substr($sf, -2)));
                    }
                } else {
                    $_SESSION['arraysfmodif'] = '';
                }
            }
            $diff = array_slice(array_diff($arraylibellesourcefinancement, $arraysfBDD), 0);
            if (!empty($diff)) {
                $_SESSION['arraysfmodif'] = $diff;
            } else {
                $_SESSION['arraysfmodif'] = '';
            }
            $nbarraylibellesourcefinancement = count($arraylibellesourcefinancement);
            for ($i = 0; $i < $nbarraylibellesourcefinancement; $i++) {
                if (!empty($arraylibellesourcefinancement[$i])) {
                    $idsourcefinancement = $manager->getSingle2("select idsourcefinancement from sourcefinancement where libellesourcefinancement=? ", $arraylibellesourcefinancement[$i]);
                    $projetSF = new Projetsourcefinancement($idprojet, $idsourcefinancement);
                    $manager->insertProjetSF($projetSF); //AJOUT DES SOURCE DE FINANCEMENT DANS LA TABLE PROJETSOURCEFINANCEMENT AVEC L'IDPROJET
                }
            }

            for ($i = 1; $i < 7; $i++) {
                $champpost = 'acronymesourcesf' . $i;
                if (!empty($_POST['' . $champpost . ''])) {
                    $idsourcefinancementacro = substr($champpost, -1); //RECUPERATION DU DERNIER CARACTERE acronymesourcef1-9                    
                    $projetacro = new ProjetAcrosourcefinancement($idprojet, $_POST['' . $champpost . ''], $idsourcefinancementacro);
                    $manager->updateProjetacrosourcefinancement($projetacro, $idprojet);
                }
            }
        }
        $arrayAccroSF = $manager->getList2("SELECT acronymesource,idsourcefinancement_sourcefinancement FROM projetsourcefinancement WHERE idprojet_projet=?", $idprojet);
        if (!empty($arrayAccroSF)) {
            foreach ($arrayAccroSF as $row) {
                $arrayAccrosf[$row['idsourcefinancement_sourcefinancement']] = $row['acronymesource'];
            }
            $arrayacromodif = array_diff($arrayAccrosf, $arrayAcrosfBDD);
            if (!empty($arrayacromodif)) {
                $_SESSION['arrayacromodif'] = $arrayacromodif;
            } else {
                $_SESSION['arrayacromodif'] = '';
            }
        } else {
            $arrayAccroSF = array();
        }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DE LA QUESTION ETES VOUS LE COORDINATEUR DU PROJET? 
//------------------------------------------------------------------------------------------------------------  
        $porteurprojet = $_POST['porteurprojet'];
        if ($porteurprojetBDD != $porteurprojet) {
            if ($porteurprojet == 'TRUE') {
                $_SESSION['porteurprojetmodif'] = TXT_OUI;
                $porteurprojetBDD =TXT_OUI;
            } else {
                $_SESSION['porteurprojetmodif'] = TXT_NON;
                $porteurprojetBDD =TXT_NON;
            }
        } else {
            $_SESSION['porteurprojetmodif'] = '';
        }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DES PARTENAIRES DU PROJET
//------------------------------------------------------------------------------------------------------------
     if (!empty($_POST['nombrePartenaire']) && $_POST['nombrePartenaire'] != 0) {   
        if (!empty($_POST['centralepartenaireprojet'])) {
            $centralepartenaireprojet = stripslashes(Securite::bdd($_POST['centralepartenaireprojet']));
            if ($centralepartenaireprojetBDD != $centralepartenaireprojet) {
                $_SESSION['centralepartenaireprojetmodif'] = $centralepartenaireprojet;
            } else {
                $_SESSION['centralepartenaireprojetmodif'] = '';
            }
        } else {
            $centralepartenaireprojet = null;
            $_SESSION['centralepartenaireprojetmodif'] = '';
        }
//------------------------------------------------------------------------------------------------------------
//                                       TRAITEMENT DES NOMS PARTENAIRE
//------------------------------------------------------------------------------------------------------------
        if (!empty($_POST['nomPartenaire01'])) {
            $partenaire1 = stripslashes(Securite::bdd(($_POST['nomPartenaire01'])));
            if ($partenaire1BDD != $partenaire1) {
                $_SESSION['partenaire1modif'] = $partenaire1;
            } else {
                $_SESSION['partenaire1modif'] = '';
            }
        } else {
            $partenaire1 = null;
            $_SESSION['partenaire1modif'] = '';
        }
     }else{
        $nombrePartenaire = 0; 
        $partenaire1 = null;
        $centralepartenaireprojet=null;
        $partenairefromprojet = new Partenairefromprojet($centralepartenaireprojet, $partenaire1);
        $manager->updatepartenairefromprojet($partenairefromprojet, $idprojet);
     }

//------------------------------------------------------------------------------
//                          PARTENAIRE PROJET
//------------------------------------------------------------------------------
        if (!empty($_POST['nombrePartenaire']) && $_POST['nombrePartenaire'] != 0) {//SI LE NOMBRE DE PARTENAIRE EST >0
            //SUPPPRESSION DES PARTENAIRES DANS LA TABLE PROJETPARTENAIRE
            $nombrePartenaire = $_POST['nombrePartenaire'];
            $manager->deleteprojetpartenaire($idprojet);
            //RECUPERATION DU PROJET DANS LA TABLE PARTENAIREPROJET QUI N'AS PAS DE REFERENCE DANS LA TABLE PROJETPARTENAIRE-->SUPPRESSION ENREGISTEMENT VIDE
            $idpartenaire = $manager->getList("SELECT idpartenaire FROM  partenaireprojet where idpartenaire not in (select idpartenaire_partenaireprojet from projetpartenaire)");
            //SUPPRESSION DES LIGNES CORRESPONDANTES
            $nbidpartenaire = count($idpartenaire);
            if ($nbidpartenaire > 0) {
                for ($i = 0; $i < $nbidpartenaire; $i++) {
                    $manager->deletepartenaireprojet($idpartenaire[$i]['idpartenaire']);
                }
            }
            $partenaires = '';
        } else {//IL N'A PAS DE PARTENAIRE SELECTIONNE IL FAUT SUPPRIMER LES PARTENAIRES PROJET DANS LES TABLE PARTENAIREPROJET ET PROJETPARTENAIRE            
            $nombrePartenaire = 0;
            $manager->deleteprojetpartenaire($idprojet);
            //RECUPERATION DU PROJET DANS LA TABLE PARTENAIREPROJET QUI N'AS PAS DE REFERENCE DANS LA TABLE PROJETPARTENAIRE-->SUPPRESSION ENREGISTEMENT VIDE
            $idpartenaire = $manager->getList("SELECT idpartenaire FROM  partenaireprojet where idpartenaire not in (select idpartenaire_partenaireprojet from projetpartenaire)");
            //SUPPRESSION DES LIGNES CORRESPONDANTES
            $nbidpartenaire = count($idpartenaire);
            if ($nbidpartenaire > 0) {
                for ($i = 0; $i < $nbidpartenaire; $i++) {
                    $manager->deletepartenaireprojet($idpartenaire[$i]['idpartenaire']);
                }
            } //EFFACAGE DU ER PARTENAIRE DANS LA TABLE PROJET           
            $centralepartenaireprojet = $manager->getSingle2("select centralepartenaireprojet from projet where idprojet=?", $idprojet);
            //$autrenomcentrale = $manager->getSingle2("select autrenomcentrale from projet where idprojet=?", $idprojet);
            $partenaire1 = $manager->getSingle2("select partenaire1 from projet where idprojet=?", $idprojet);
            $partenairefromprojet = new Partenairefromprojet($centralepartenaireprojet, $partenaire1);
            $manager->updatepartenairefromprojet($partenairefromprojet, $idprojet);
        }
        if ($_POST['nombrePartenaire'] > 1) {
            for ($i = 0; $i < $_POST['nombrePartenaire'] - 1; $i++) {
                if (!empty($_POST['' . 'nomPartenaire' . $i . ''])) {
                    $nomPartenaire = stripslashes(Securite::bdd(($_POST['' . 'nomPartenaire' . $i . ''])));
                } else {
                    $nomPartenaire = '';
                }
                if (!empty($_POST['' . 'nomLaboEntreprise' . $i . ''])) {
                    $nomLaboEntreprise = stripslashes(Securite::bdd(($_POST['' . 'nomLaboEntreprise' . $i . ''])));
                } else {
                    $nomLaboEntreprise = '';
                }//TRAITEMENT AJOUT DANS LA TABLE PARTENAIREPROJET
                $idpartenaire = $manager->getSingle("select max (idpartenaire) from partenaireprojet") + 1;
                $newpartenaireprojet = new Partenaireprojet($idpartenaire, $nomPartenaire, $nomLaboEntreprise);
                $manager->addpartenaireprojet($newpartenaireprojet);
                $partenaires .=$nomPartenaire . ' - ' . $nomLaboEntreprise . ' - ';
                //TRAITEMENT AJOUT DANS LA TABLE PROJETPARTENAIRE
                $newprojetpartenaire = new Projetpartenaire($idpartenaire, $idprojet);
                $manager->addprojetpartenaire($newprojetpartenaire);
            }
            $Spartenaires = substr(trim($partenaires), 0, -1);
            if ($Spartenaires != $sPartenairesBDD) {
                $_SESSION['partenairesmodif'] = TXT_AUTRESPARTENAIRE . '  ' . $Spartenaires;
            } else {
                $_SESSION['partenairesmodif'] = '';
            }
        }

//------------------------------------------------------------------------------------------------------------
        //                              TRAITEMENT DES THEMATIQUE
        //------------------------------------------------------------------------------------------------------------
        if (!empty($_POST['thematique'])) {
            $idthematique = substr($_POST['thematique'], 2);
            $libellethematique = $manager->getSingle2("select libellethematique from thematique where idthematique =?", $idthematique);
            if ($libellethematique != TXT_AUTRES) {//VALEUR DIFFERENTE DE "Autres"
                $_SESSION['libelleautrethematiquemodif'] = '';
                if ($idthematiqueBDD != $idthematique) {
                    $_SESSION['libellethematiquemodif'] = $libellethematique;
                } else {
                    $_SESSION['libellethematiquemodif'] = '';
                }
                $idthematique_thematique = $manager->getSingle2("select idthematique from thematique where libellethematique =?", $libellethematique);
                $idautrethematique_autrethematique = NAAUTRETHEMATIQUE; //valeur n/a
            } else {
                $_SESSION['libellethematiquemodif'] = '';
//------------------------------------------------------------------------------------------------------------
//                                       TRAITEMENT DES AUTRES THEMATIQUE
//------------------------------------------------------------------------------------------------------------
                if (!empty($_POST['autreThematique']) || $_POST['autreThematique'] != null) {
                    $autreThematique = Securite::BDD($_POST['autreThematique']);
                    $idautrethematique_autrethematique = $manager->getSingle("select max(idautrethematique) from autrethematique") + 1;
                    if ($libelleautrethematiqueBDD != $autreThematique) {
                        $_SESSION['libelleautrethematiquemodif'] = $autreThematique;
                        $_SESSION['libellethematiquemodif'] = '';
                    } else {
                        $_SESSION['libelleautrethematiquemodif'] = '';
                    }
                    $newautrethematique = new autrethematique($idautrethematique_autrethematique, $autreThematique);
                    $manager->addautrethematique($newautrethematique);
                    $idthematique_thematique = $manager->getSingle("select idthematique from thematique where libellethematique='Autres'");
                } else {
                    $idthematique_thematique = $manager->getSingle("select idthematique from thematique where libellethematique='Autres'");
                    $idautrethematique_autrethematique = 1;
                    $_SESSION['libelleautrethematiquemodif'] = '';
                }
            }
        } else {
            $_SESSION['libellethematiquemodif'] = '';
            $_SESSION['libelleautrethematiquemodif'] = '';
            $idthematique_thematique = $manager->getSingle2("select idthematique_thematique from projet where idprojet=?", $idprojet);
            if (empty($idthematique_thematique)) {
                $idthematique_thematique = null;
                $_SESSION['libelleautrethematiquemodif'] = '';
            }
            $idautrethematique_autrethematique = $manager->getSingle2("select idautrethematique_autrethematique from projet where idprojet=?", $idprojet);
            if (empty($idautrethematique_autrethematique)) {
                $idautrethematique_autrethematique = 1;
                $_SESSION['libelleautrethematiquemodif'] = '';
            }
        }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DES DATE DE DEBUT DES TRAVAUX
//------------------------------------------------------------------------------------------------------------        
        if (!empty($_POST['dateDebutTravaux'])) {
            $dateDebutTravaux = $_POST['dateDebutTravaux'];
            if ($dateDebutTravauxBDD != $dateDebutTravaux) {
                $_SESSION['dateDebutTravauxmodif'] = $dateDebutTravaux;
            } else {
                $_SESSION['dateDebutTravauxmodif'] = '';
            }
        } elseif (!empty($rowprojet[0]['dateDebutTravaux'])) {
            $dateDebutTravaux = $rowprojet[0]['dateDebutTravaux'];
            $_SESSION['dateDebutTravauxmodif'] = '';
        } else {
            $_SESSION['dateDebutTravauxmodif'] = '';
        }
//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT 
//------------------------------------------------------------------------------------------------------------ 
        if (!empty($_POST['dureeestimeprojet'])) {
            $dureeestime = stripslashes(Securite::bdd($_POST['dureeestimeprojet']));
            if ($dureeestimeBDD != $dureeestime) {
                $_SESSION['dureeestimemodif'] = $dureeestime;
            } else {
                $_SESSION['dureeestimemodif'] = '';
            }
        } elseif (!empty($rowprojet[0]['dureeestime']) || $rowprojet[0]['dureeestime'] != 0) {
            $dureeestime = $rowprojet[0]['dureeestime'];
            $_SESSION['dureeestimemodif'] = '';
        } else {
            $dureeestime = '';
            $_SESSION['dureeestimemodif'] = '';
        }
        if (!empty($_POST['choix2'])) {
            $periodestime = (int) substr($_POST['choix2'], 2);
            if ($idperiodestimeBDD != $periodestime) {
                $_SESSION['periodestimemodif'] = $periodestime;
            } else {
                $_SESSION['periodestimemodif'] = '';
            }
        } else {
            $periodestime = $manager->getSingle2("select periodestime from projet where idprojet = ?", $idprojet);
            $_SESSION['periodestimemodif'] = '';
        }
//---------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PERSONNE CENTRALE
//---------------------------------------------------------------------------------------------------------------------------------------------------
//SUPPPRESSION DES PERSONNES DANS LA TABLE PROJETPERSONNEACCUEILCENTRALE
        if (!empty($_POST['nombrePersonneCentrale']) || $_POST['nombrePersonneCentrale'] == 0) {
            $nombrePersonneCentrale = $_POST['nombrePersonneCentrale'];
            $manager->deleteprojetpersonneaccueilcentrale($idprojet);
        } else {
            $nombrePersonneCentrale = 0;
        }
        //RECUPERATION DU PROJET DANS LA TABLE PERSONNEACCUEILCENTRALE QUI N'AS PAS DE REFERENCE DANS LA TABLE PROJETPERSONNEACCUEILCENTRALE
        $idpersonnecentrale = $manager->getList("SELECT idpersonneaccueilcentrale FROM  personneaccueilcentrale where idpersonneaccueilcentrale not in
	 (select idpersonneaccueilcentrale_personneaccueilcentrale from projetpersonneaccueilcentrale)");
        //SUPPRESSION DES LIGNES CORRESPONDANTES
        $nbidpersonnecentrale = count($idpersonnecentrale);
        if ($nbidpersonnecentrale > 0) {
            for ($i = 0; $i < $nbidpersonnecentrale; $i++) {
                $manager->deletepersonneaccueilcentrale($idpersonnecentrale[$i]['idpersonneaccueilcentrale']);
            }
        }
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
                $idqualitedemandeurAca = trim($_POST['qualiteaccueilcentrale' . $i . '']);
                $idqualitedemandeuraca = substr($idqualitedemandeurAca, -1);
                if (empty($idqualitedemandeuraca)) {
                    $idqualitedemandeuraca = 1;
                }               
                if ($lang == 'fr') {
                    $libellequalite = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca =?", $idqualitedemandeuraca);
                } elseif ($lang == 'en') {
                    $libellequalite = $manager->getSingle2("select libellequalitedemandeuracaen from qualitedemandeuraca where idqualitedemandeuraca =?", $idqualitedemandeuraca);
                }
            }
            //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            //                          TRAITEMENT DES AUTRES QUALITE DOCTORANT,POSTDOC OU AUTRES SI AUTRES VALEUR DE AUTRES
            //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            
            if($idqualitedemandeuraca != PERMANENT ){
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
                        }else{
                            $idpersonneQualite = (int) substr(trim($_POST['autreQualite' . $i . '']), -1);
                            $idautrequalite = IDNAAUTREQUALITE;                            
                        }
                    }
                }
            }else{
                $idautrequalite = IDNAAUTREQUALITE;
                $idpersonneQualite = IDNAAUTRESQUALITE;
            } 
                 
            //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            //
            //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
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
                $connaissancetechnologiqueaccueil = $connaissancetechnologiqueAccueil;
            } else {
                $connaissancetechnologiqueaccueil = '';
            }
                       
            //TRAITEMENT AJOUT DANS LA TABLE PERSONNEACCUEILCENTRALE            
            $idpersonneaccueilcentrale = $manager->getSingle("select max(idpersonneaccueilcentrale) from Personneaccueilcentrale") + 1;            
            $personne = new Personneaccueilcentrale($idpersonneaccueilcentrale, $nomaccueilcentrale, $prenomaccueilcentrale, $idqualitedemandeuraca, $mailaccueilcentrale, $telaccueilcentrale, trim($connaissancetechnologiqueAccueil),$idpersonneQualite,$idautrequalite);
            
            $manager->addPersonneaccueilcentrale($personne);
            //TRAITEMENT AJOUT DANS LA TABLE PROJETPERSONNEACCUEILCENTRALE
            $projetpersonneaccueilcentrale = new Projetpersonneaccueilcentrale($idprojet, $idpersonneaccueilcentrale);
            $manager->addprojetpersonneaccueilcentrale($projetpersonneaccueilcentrale);
            //TRAITEMENT DES AUTRES QUALITE
            
            
        }
    }
    $personnecentrale = '';
    if (!empty($nombrePersonneCentrale)) {
        for ($i = 0; $i < $nombrePersonneCentrale; $i++) {
            $idqualitedemandeurAca = trim($_POST['qualiteaccueilcentrale' . $i . '']);
            $idqualitedemandeuraca = substr($idqualitedemandeurAca, -1);
            if ($lang == 'fr') {
                $libellequalite = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca =?", $idqualitedemandeuraca);
            } elseif ($lang == 'en') {
                $libellequalite = $manager->getSingle2("select libellequalitedemandeuracaen from qualitedemandeuraca where idqualitedemandeuraca =?", $idqualitedemandeuraca);
            }
            $personnecentrale .= Securite::BDD($_POST['nomaccueilcentrale' . $i . '']) . ' - ' . Securite::BDD($_POST['prenomaccueilcentrale' . $i . '']) . ' - ' . $_POST['mailaccueilcentrale' . $i . ''] .
                    ' - ' . Securite::BDD($_POST['telaccueilcentrale' . $i . '']) . ' - ' . Securite::BDD($libellequalite) . '-';
        }
        $personneCentrale = TXT_PERSONNEACCUEILCENTRALE . ': ' . substr(trim($personnecentrale), 0, -1);
    }
    if (htmlspecialchars($personnecentraleBDD) != htmlspecialchars($personnecentrale)) {
        $_SESSION['personnecentralemodif'] = $personnecentrale;
    } else {
        $_SESSION['personnecentralemodif'] = '';
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              AUTRE CENTRALE                
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $arrayautrecentraleBDD = $manager->getList2("SELECT c.libellecentrale FROM  projetautrecentrale p,centrale c WHERE  p.idcentrale = c.idcentrale and p.idprojet=?", $idprojet);
    $arrayAutreCentraleBDD = array();
    for ($i = 0; $i < count($arrayautrecentraleBDD); $i++) {
        array_push($arrayAutreCentraleBDD, $arrayautrecentraleBDD[$i]['libellecentrale']);
    }
    if (!empty($rowprojet[0]['descriptionautrecentrale'])) {
        $descriptionautrecentraleBDD = $rowprojet[0]['descriptionautrecentrale'];
    } else {
        $descriptionautrecentraleBDD = "";
    }
    if ($_POST['etapeautrecentrale'] == 'TRUE') {//-->ON  SELECTIONNE AUTRE CENTRALE
        $etapeautrecentrale = 'TRUE';
        if (!empty($_POST['autrecentrale'])) {
            $manager->deleteprojetautrecentrale($idprojet);
            for ($i = 0; $i < count($_POST['autrecentrale']); $i++) {
                $idautrecentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_POST['autrecentrale'][$i]);
                $projetautrecentrale = new Projetautrecentrale($idautrecentrale, $idprojet);
                $manager->addprojetautrescentrale($projetautrecentrale);
            }
        }
        if (!empty($_POST['etautrecentrale'])) {
            $descriptionautrecentrale = clean($_POST['etautrecentrale']);
            if ($descriptionautrecentraleBDD != $descriptionautrecentrale) {
                $_SESSION['descriptionautrecentralemodif'] = $descriptionautrecentrale;
            } else {
                $_SESSION['descriptionautrecentralemodif'] = '';
            }
        } else {
            $_SESSION['descriptionautrecentralemodif'] = '';
            $descriptionautrecentrale = clean($manager->getSingle2("select descriptionautrecentrale from projet where idprojet = ?", $idprojet));
            if (empty($descriptionautrecentrale)) {
                $descriptionautrecentrale = '';
                $_SESSION['descriptionautrecentralemodif'] = '';
            }
        }
    } else {//-->ON A PAS SELECTIONNE AUTRE CENTRALE
        $etapeautrecentrale = 'FALSE';
        $manager->deleteprojetautrecentrale($idprojet);
        $descriptionautrecentrale = '';
    }
    $sautrecentrale = '';
    if (!empty($_POST['autrecentrale'])) {
        if ($arrayAutreCentraleBDD != $_POST['autrecentrale']) {
            for ($i = 0; $i < count($_POST['autrecentrale']); $i++) {
                $sautrecentrale.=$_POST['autrecentrale'][$i] . ' - ';
            }
            $_SESSION['autrecentralemodif'] = substr($sautrecentrale, 0, -2);
        } else {
            $_SESSION['autrecentralemodif'] = '';
        }
    } else {
        $sautrecentrale = '';
    }

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              CENTRALE DE PROXIMITE
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
    $centrale_proximiteBDD = $manager->getList2("SELECT idcentrale_proximite FROM projet_centraleproximite where idprojet=?", $idprojet);
    $centraleProximiteBDD = array();
    for ($i = 0; $i < count($centrale_proximiteBDD); $i++) {
        array_push($centraleProximiteBDD, 'cp' . $centrale_proximiteBDD[$i]['idcentrale_proximite']);
    }
    $centrale_proximite = stripslashes(Securite::bdd($_POST['centrale_proximite']));

    $descriptioncentraleproximiteBDD = $rowprojet[0]['descriptioncentraleproximite'];

    if ($_POST['centrale_proximite'] == TRUE) {
        if (!empty($_POST['centrale_Proximite'])) {
            if ($centraleProximiteBDD != $_POST['centrale_Proximite']) {
                $_SESSION['centraleproximitemodif'] = $_POST['centrale_Proximite'];
            } else {
                $_SESSION['centraleproximitemodif'] = '';
            }
            $manager->deleteprojetcentraleproximite($idprojet);
            for ($i = 0; $i < count($_POST['centrale_Proximite']); $i++) {
                $idcentrale_proximite = substr($_POST['centrale_Proximite'][$i], 2);
                $centraleproximite = new Projet_centraleproximite($idprojet, $idcentrale_proximite);
                $manager->addprojetcentraleproximite($centraleproximite);
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
    }
    if (!empty($_POST['desTechno'])) {
        $descriptifTechnologique = clean($_POST['desTechno']);
        if ($descriptiftechnologiqueBDD != $descriptifTechnologique) {
            $_SESSION['descriptifTechnologiquemodif'] = $descriptifTechnologique;
        } else {
            $_SESSION['descriptifTechnologiquemodif'] = '';
        }
    } else {
        $descriptifTechnologique = clean($manager->getSingle2("select descriptiftechnologique from projet where idprojet = ?", $idprojet));
        $_SESSION['descriptifTechnologiquemodif'] = '';
        if (empty($descriptifTechnologique)) {
            $descriptifTechnologique = '';
            $_SESSION['descriptifTechnologiquemodif'] = '';
        }
    }
    if (!empty($rowprojet[0]['verrouidentifiee'])) {
        $verrouidentifieBDD = $rowprojet[0]['verrouidentifiee'];
    } else {
        $verrouidentifieBDD = '';
    }
    if (!empty($_POST['verrouide'])) {
        $verrouidentifie = clean(trim($_POST['verrouide']));
        if ($verrouidentifieBDD != $verrouidentifie) {
            $_SESSION['verrouidentifiemodif'] = $verrouidentifie;
        } else {
            $_SESSION['verrouidentifiemodif'] = '';
        }
    } else {
        $_SESSION['verrouidentifiemodif'] = '';
        $verrouidentifie = clean($manager->getSingle2("select verrouidentifiee from projet where idprojet = ?", $idprojet));
        if (empty($verrouidentifie)) {
            $verrouidentifie = '';
            $_SESSION['verrouidentifiemodif'] = '';
        }
    }
    $reussiteBDD = $rowprojet[0]['reussite'];
    if (!empty($_POST['reussit'])) {
        $reussite = clean($_POST['reussit']);
        if ($reussiteBDD != $reussite) {
            $_SESSION['reussitemodif'] = $reussite;
        } else {
            $_SESSION['reussitemodif'] = '';
        }
    } else {
        $reussite = clean($manager->getSingle2("select reussite from projet where idprojet =?", $idprojet));
        $_SESSION['reussitemodif'] = '';
        if (empty($reussite)) {
            $reussite = '';
            $_SESSION['reussitemodif'] = '';
        }
    }
    $nbPlaqueBDD = $rowprojet[0]['nbplaque'];

    if (!empty($_POST['nbPlaque']) || $_POST['nbPlaque'] != 0) {
        $nbPlaque = Securite::bdd($_POST['nbPlaque']);
        if ($nbPlaqueBDD != $nbPlaque) {
            $_SESSION['nbplaquemodif'] = $nbPlaque;
        } else {
            $_SESSION['nbplaquemodif'] = '';
        }
    } elseif (!empty($rowprojet[0]['nbplaque']) || $rowprojet[0]['nbplaque'] != 0) {
        $nbPlaque = $rowprojet[0]['nbplaque'];
        $_SESSION['nbplaquemodif'] = '';
    } else {
        $nbPlaque = 0;
        $_SESSION['nbplaquemodif'] = '';
    }
    $nbrunBDD = $rowprojet[0]['nbrun'];
    if (!empty($_POST['nbRun']) || $_POST['nbRun'] != 0) {
        $nbRun = Securite::bdd($_POST['nbRun']);
        if ($nbrunBDD != $nbRun) {
            $_SESSION['nbRunmodif'] = $nbRun;
        } else {
            $_SESSION['nbRunmodif'] = '';
        }
    } elseif (!empty($rowprojet[0]['nbrun'])) {
        $nbRun = $rowprojet[0]['nbrun'];
        $_SESSION['nbRunmodif'] = '';
    } else {
        $nbRun = 0;
        $_SESSION['nbRunmodif'] = '';
    }

    if (!empty($_POST['devis'])) {
        $devis = Securite::bdd($_POST['devis']);
    } elseif (!empty($rowprojet[0]['envoidevis'])) {
        $devis = $rowprojet[0]['envoidevis'];
    } else {
        $devis = '';
    }
    $emailrespdevisBDD = $rowprojet[0]['emailrespdevis'];

    if (!empty($_POST['mailresp'])) {
        $mailresp = Securite::bdd(($_POST['mailresp']));
        if ($emailrespdevisBDD != $mailresp) {
            $_SESSION['emailrespdevismodif)'] = $mailresp;
        } else {
            $_SESSION['emailrespdevismodif)'] = '';
        }
    } elseif (!empty($rowprojet[0]['emailrespdevis'])) {
        $mailresp = $rowprojet[0]['emailrespdevis'];
        $_SESSION['emailrespdevismodif)'] = '';
    } else {
        $mailresp = '';
        $_SESSION['emailrespdevismodif)'] = '';
    }

    $refinterneBDD = $rowprojet[0]['refinterneprojet'];
    if (!empty($_POST['refinterne'])) {
        $refinterne = stripslashes(Securite::bdd(($_POST['refinterne'])));
        if ($refinterneBDD != $refinterne) {
            $_SESSION['refinternemodif'] = $refinterne;
        } else {
            $_SESSION['refinternemodif'] = '';
        }
    } elseif (!empty($rowprojet[0]['refinterneprojet'])) {
        $refinterne = $rowprojet[0]['refinterneprojet'];
        $_SESSION['refinternemodif'] = '';
    } else {
        $refinterne = '';
        $_SESSION['refinternemodif'] = '';
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
    $nblisterepertoire = count($listerepertoire);
    for ($i = 0; $i < $nblisterepertoire; $i++) {
        if (in_array($listerepertoire[$i], $resultEcart)) { //Vérification si l'
            unlink('../uploaddesc/' . $listerepertoire[$i]); //Suppression du fichier non référencés dans la table projet
        }
    }
    if (!empty($_FILES['fichierphase2']['name'])) {
        $attachementdesc = stripslashes(Securite::bdd($_FILES['fichierphase2']['name']));
        if ($attachementdescBDD != $attachementdesc) {
            $_SESSION['attachementdescmodif'] = $attachementdesc;
        } else {
            $_SESSION['attachementdescmodif'] = '';
        }
        $dossier = '../uploaddesc/';
        $fichierPhase = basename($_FILES['fichierphase2']['name']);
        $taille_maxi = 1048576;
        $taille = filesize($_FILES['fichierphase2']['tmp_name']);
        $extensions = array('.pdf', '.PDF');
        $extension = strrchr($_FILES['fichierphase2']['name'], '.');
        if (!in_array($extension, $extensions)) {//VERIFICATION DU FORMAT SI IL N'EST PAS BON ON SORT
            $erreur1 = TXT_ERREURUPLOAD;
            header('Location: /' . REPERTOIRE . '/Upload_Errorphase2/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet.'/'.$idstatutprojet);
            exit();
        } elseif ($taille > $taille_maxi) {//VERIFICATION DE LA TAILLE SI ELLE EST >1mo ON SORT
            $erreur1 = TXT_ERREURTAILLEFICHIER;
            header('Location: /' . REPERTOIRE . '/Upload_Errorsizephase2/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet.'/'.$idstatutprojet);
            exit();
        } elseif (!isset($erreur1)) {//S'il n'y a pas d'erreur, on upload
            if (move_uploaded_file($_FILES['fichierphase2']['tmp_name'], $dossier . $fichierPhase)) {
                chmod($dossier . $fichierPhase, 0777);
            }
        }
    } else {
        $attachementdesc = $manager->getSingle2("select attachementdesc from projet where idprojet =?", $idprojet);
        $_SESSION['attachementdescmodif'] = '';
        if (empty($attachementdesc)) {
            $attachementdesc = '';
            $_SESSION['attachementdescmodif'] = '';
        }
    }
    $devtechnologique = $_POST['devTechnologique'];
    if ($devtechnologique == 'FALSE') {
        $verrouidentifie = '';
    }


    //------------------------------------------------------------------------------------------------------------
    //                              TRAITEMENT DU PROJETPHASE2
    //------------------------------------------------------------------------------------------------------------        
    if($_SESSION['idTypeUser']==ADMINLOCAL){
        if($_POST['interneExterne']=='ie0'){
            $interneexterne = null;
        }elseif($_POST['interneExterne']=='ie1'){
            $interneexterne = 'I';
        }else{
            $interneexterne = 'E';
        }
        if($_POST['internationnalNationnal']=='in0'){
            $internationalNational = null;
        }elseif($_POST['internationnalNationnal']=='in1'){
            $internationalNational = 'N';
        }else{
            $internationalNational = 'I';
        }
        
    }else{
        $interneexterne = $manager->getSingle2("select interneexterne from projet where idprojet=?", $idprojet);
        $internationalNational = $manager->getSingle2("select internationalnational from projet where idprojet=?", $idprojet);
        if(empty($interneexterne)){
            $interneexterne = null;
        }
        if(empty($internationalNational)){
            $internationalNational = null;
        }
    }
        
    $projetphase2 = new Projetphase2($contactCentralAccueil, $idtypeprojet_typeprojet, $nbHeure, $dateDebutTravaux, $dureeprojet, $idperiodicite_periodicite, $centralepartenaireprojet, $idthematique_thematique, $idautrethematique_autrethematique, $descriptifTechnologique, $attachementdesc, $verrouidentifie, $nbPlaque, $nbRun, $devis, $mailresp, $reussite, $refinterne, $devtechnologique, $nbeleve, $nomformateur, $partenaire1, $porteurprojet, $dureeestime, $periodestime, $descriptionautrecentrale, $etapeautrecentrale, $centrale_proximite, $descriptioncentraleproximite,$interneexterne, $internationalNational);    
    $manager->updateProjetphase2($projetphase2, $idprojet);
    //------------------------------------------------------------------------------------------------------------------------
    //                  GESTION DES CAS OU LE DEMANDEUR EST ADMINISTRATEUR DE PROJET
    //------------------------------------------------------------------------------------------------------------------------
    if(isset($_SESSION['idutilisateur'])&&!empty($_SESSION['idutilisateur'])){
        $idutilisateur = $_SESSION['idutilisateur'];
    }elseif (isset($_GET['idprojet'])&&!empty($_GET['idprojet'])) {
        $idprojet = $_GET['idprojet'];
        $idutilisateur= $manager->getSingle2("select idutilisateur_utilisateur from creer where idprojet_projet=?", $idprojet);
    }
    
    $admin = $manager->getSingle2("select administrateur from utilisateur where idutilisateur=?", $idutilisateur);    
    if($admin==1){
        ajouteAdministrationProjet($idutilisateur);
    }
    //------------------------------------------------------------------------------------------------------------------------
    //                  GESTION DES CAS OU LE DEMANDEUR A UN RESPONSABLE DANS L'APPLICATION QUI EST ADMINISTRATEUR DE PROJET
    //------------------------------------------------------------------------------------------------------------------------
    $mailResponsable = $manager->getSingle2("select mailresponsable from utilisateur where idutilisateur=?", $idutilisateur);
    if(!empty($mailResponsable)){
        $idresponsable = $manager->getSingle2("select idutilisateur from utilisateur,loginpassword  where idlogin=idlogin_loginpassword and lower(mail) like lower(?) ", trim($mailResponsable));
        if(!empty($idresponsable)){
            ajouteResponsableAdministrationProjet($idutilisateur,$idresponsable);
        }
    }
    
    //------------------------------------------------------------------------------------------------------------------------
    //			 MISE A JOUR DE LA TABLE RESSOURCEPROJET  ON EFFACE TOUTES LES RESSOURCES SELECTIONNEES
    //------------------------------------------------------------------------------------------------------------------------
    $ressources = '';
    if (!empty($_POST['ressource'])) {
        $manager->deleteressourceprojet($idprojet);
        $ressource = $_POST['ressource'];
        foreach ($ressource as $chkbx) {
            $arrayressource = $manager->getListbyArray("SELECT idressource FROM ressource where libelleressource =?", array($chkbx));
            $ressources .=$chkbx . ',';
            $nbarrayressource = count($arrayressource);
            for ($i = 0; $i < $nbarrayressource; $i++) {
                $idressource_ressource = $arrayressource[$i]['idressource'];
                //TRAITEMENT AJOUT DANS LA TABLE RESSOURCE PROJET
                $ressourceprojet = new Ressourceprojet($idprojet, $idressource_ressource);
                $manager->addressourceprojet($ressourceprojet);
            }
        }
    } else {
        $manager->deleteressourceprojet($idprojet);
    }
    if (!empty($_POST['ressource'])) {
        if ($arrayRessourceBDD != $_POST['ressource']) {
            $sressources = '';
            for ($i = 0; $i < count($_POST['ressource']); $i++) {
                $sressources.=$_POST['ressource'][$i] . ' - ';
            }
            $_SESSION['ressourcesmodif'] = substr($sressources, 0, -2);
        } else {
            $_SESSION['ressourcesmodif'] = '';
        }
    } else {
        $sressources = '';
    }
//------------------------------------------------------------------------------------------------------------------------
//			 FIN
//------------------------------------------------------------------------------------------------------------------------   
    if (isset($_POST['valider'])) {//CAS OU ON VALIDE UN PROJET
        $idstatutprojet = ACCEPTE;
        if (isset($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {
            $cas = 'creationprojetphase2etape';
        } else {
            $cas = 'creerprojetphase2';
        }
    }
    if ($cas == 'creerprojetphase2' || $cas == 'creationprojetphase2etape') {//CAS OU ON VALIDE UN PROJET EN ATTENTE PHASE2 idstatut = 9
        $idcentrale = $manager->getSingle2('select idcentrale_centrale from concerne where idprojet_projet=?', $idprojet);
        $manager->deleteConcerne($idprojet);
        $idstatutprojet = ACCEPTE;
        $concerne = new Concerne($idcentrale, $idprojet, $idstatutprojet, "");
        $manager->addConcerne($concerne);
        $_SESSION['idstatutprojet'] = $idstatutprojet;
        include '../uploadprojetphase2.php';
    } elseif ($cas == 'enregistrement') {//CAS OU ON SAUVEGARDE UN PROJET                
        $idstatutprojet = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
        $_SESSION['idstatutprojet'] = $idstatutprojet;
    }
    if (!empty($_POST['statutProjet']) && $_POST['save'] == 'non' && $_POST['maj'] == 'non') {
        $idstatutprojet = (int) substr($_POST['statutProjet'], 2, 1);
    } elseif (!empty($_POST['statutProjet']) && $_POST['save'] == 'oui') {
        $idstatutprojet = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet); //on récupère la valeur dans la BDD
        $_SESSION['idstatutprojet'] = $idstatutprojet;
        if (empty($idstatutprojet)) {
            $idstatutprojet = ENATTENTEPHASE2;
            $_SESSION['idstatutprojet'] = $idstatutprojet;
        }
    }
    if ($cas == 'changement de statut') {
        if ($idstatutprojet == ACCEPTE) {//PROJET EN COURS D'EXPERTISE            
            $idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword, utilisateur  WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
            $concerne = new Concerne($idcentrale, $idprojet, ACCEPTE, "");
            $manager->updateConcerne($concerne, $idprojet);
            include '../uploadphase2.php';
        } elseif ($idstatutprojet == ENCOURSREALISATION) {
            //PROJET EN COURS DE REALISATION             
            $datedebutprojet = $datemodifstatut;
            $idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword, utilisateur  WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
            $concerne = new Concerne($idcentrale, $idprojet, ENCOURSREALISATION, "");
            $manager->updateConcerne($concerne, $idprojet);
            //MISE A JOUR DE LA TABLE PROJET --> DATEDEBUT DU PROJET = DATE DU JOUR DE CHANGEMENT DE STATUT
            $datedebut = new DateDebutProjet($idprojet, $datedebutprojet);
            $manager->updateDateDebutProjet($datedebut, $idprojet);
            //CONTROLE QUE LE PROJET N'EST PAS PLUS DE 1 FOIS DANS LA MEME CENTRALE ET SUPPERSION DU DOUBLON
            $nbcentrale = $manager->getSingle2("select count(idcentrale_centrale) from concerne where idprojet_projet=? ", $idprojet);
            if ($nbcentrale > 1) {
                //MISE A JOUR DU STATUT DU PROJET DANS LES AUTRES CENTRALES
                $arraycentrale = $manager->getListbyArray("select idcentrale_centrale from concerne where idcentrale_centrale!=? and idprojet_projet=?", array($idcentrale, $idprojet));
                $nbarraycentrale = count($arraycentrale);
                $datejour = date('Y-m-d');
                $commentaireProjet = affiche('TXT_PROJETREALISATIONCENTRALE');
                $libellecentrale = $manager->getSinglebyArray("SELECT libellecentrale FROM  concerne, centrale WHERE  idcentrale_centrale = idcentrale AND idstatutprojet_statutprojet =? and idprojet_projet=?", array(ENCOURSREALISATION, $idprojet));
                for ($i = 0; $i < $nbarraycentrale; $i++) {
                    $concerne = new Concerne($arraycentrale[$i]['idcentrale_centrale'], $idprojet, REFUSE, $commentaireProjet . ' ' . $libellecentrale);
                    $manager->updateConcerne($concerne, $idprojet);
                    $daterefus = new DateStatutRefusProjet($idprojet, $datejour);
                    $manager->updateDateStatutRefuser($daterefus, $idprojet, $arraycentrale[$i]['idcentrale_centrale']);
                }
                include_once '../EmailProjetRealiInfoautrecentrale.php'; //ENVOIE D'UN EMAIL DANS LES AUTRES CENTRALES
            } else {
                $idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword, utilisateur  WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
                $concerne = new Concerne($idcentrale, $idprojet, ENCOURSREALISATION, "");
                $manager->updateConcerne($concerne, $idprojet);
                include '../EmailProjetEncoursrealisation.php';
                header('Location: /' . REPERTOIRE . '/myproject/' . $lang . '/' . $idprojet);
            }
        } elseif ($idstatutprojet == FINI) {//PROJET FINI
            //VERIFICATION QUE LE PROJET A BIEN UNE DATE DE DEBUT DE PROJET,AFFECTATION DE LA DATE STAUTFINI DANS LE CAS CONTRAIRE
            $datedebutduprojet = $manager->getSingle2("select datedebutprojet from projet where idprojet=?", $idprojet);
            if (empty($datedebutduprojet)) {
                $datedebutduprojet = $datemodifstatut;
                $dateprojet = new DateDebutProjet($idprojet, $datedebutduprojet);
                $manager->updateDateDebutProjet($dateprojet, $idprojet);
            }
            $datestatutFini = $datemodifstatut;
            $datefini = new DateStatutFiniProjet($idprojet, $datestatutFini);
            $manager->updateDateStatutFini($datefini, $idprojet);
            $idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword, utilisateur  WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
            $concerne = new Concerne($idcentrale, $idprojet, FINI, "");
            $manager->updateConcerne($concerne, $idprojet);
            // ENVOI D'UN EMAIL
            include '../EmailProjetfini.php';
            include '../uploadphase2.php';
        } elseif ($idstatutprojet == CLOTURE) {//PROJET CLOTURER
            $datestatutCloturer = $datemodifstatut;
            //VERIFICATION QUE LE PROJET A BIEN UNE DATE DE DEBUT DE PROJET,AFFECTATION DE LA DATE STATUTCLOTURER DANS LE CAS CONTRAIRE
            $dates = $manager->getList2("select datedebutprojet,datestatutfini from projet where idprojet=?", $idprojet);
            if (empty($dates[0]['datedebutprojet'])) {
                $datedebutduprojet = $datemodifstatut;
                $dateprojet = new DateDebutProjet($idprojet, $datedebutduprojet);
                $manager->updateDateDebutProjet($dateprojet, $idprojet);
            }
            if (empty($dates[0]['datestatutfini'])) {
                $datestatutfini = $datemodifstatut;
                $dateprojet = new DateStatutFiniProjet($idprojet, $datestatutfini);
                $manager->updateDateStatutFini($dateprojet, $idprojet);
            }
            $datecloturer = new DateStatutCloturerProjet($idprojet, $datestatutCloturer);
            $manager->updateDateStatutCloturer($datecloturer, $idprojet);
            $idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword, utilisateur  WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
            $concerne = new Concerne($idcentrale, $idprojet, CLOTURE, "");
            $manager->updateConcerne($concerne, $idprojet);
            // ENVOI D'UN EMAIL            
            include '../EmailProjetcloture.php';
            include '../uploadphase2.php';
        } elseif ($idstatutprojet == REFUSE) {//PROJET REFUSER
            $idcentrale = $manager->getSingle2('select idcentrale from centrale,loginpassword,utilisateur where idlogin=idlogin_loginpassword and idcentrale=idcentrale_centrale and pseudo=?', $_SESSION['pseudo']);
            $datejour = date('Y-m-d');
            //RECUPERATION DE L'EV.$idcentrale;ENTUEL PREMIER COMMENTAIRE
            if (!empty($_POST['commentairephase2Valeur'])) {
                $commentaireProjet = $_POST['commentairephase2Valeur'];
            } else {
                $commentaireProjet = '';
            }
            $concerne = new Concerne($idcentrale, $idprojet, REFUSE, $commentaireProjet);
            $manager->updateConcerne($concerne, $idprojet);
            $daterefus = new DateStatutRefusProjet($idprojet, $datejour);
            $manager->updateDateStatutRefuser($daterefus, $idprojet, $idcentrale);
            include '../EmailProjetphase2.php'; //ENVOIE D'UN EMAIL AU DEMANDEUR AVEC COPIE DU CHAMP COMMENTAIRE
            $_SESSION['idstatutprojet'] = $idstatutprojet; //NE PAS EFFACER ON A BESOIN DANS UPLOADPHASE2
        } elseif ($idstatutprojet == TRANSFERERCENTRALE) {
            $ancienStatut = $manager->getSinglebyArray("select idstatutprojet_statutprojet from concerne where idprojet_projet=? and idcentrale_centrale=?", array($idprojet, $IDCENTRALEUSER));
            $datejour = date('Y-m-d');
            $idnouvellecentrale = $_POST['centraletrs'];
            $rowidcentrale = $manager->getListbyArray("select idcentrale_centrale from concerne where idprojet_projet =? and idcentrale_centrale <>?  ", array($idprojet, $idnouvellecentrale));
            $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $idnouvellecentrale); //NOM DE LA CENTRALE
            foreach ($rowidcentrale as $key => $value) {
                $concerne = new Concerne($value[0], $idprojet, REFUSE, TXT_COMMENTTRS . ' ' . $libellecentrale);
                $manager->updateConcerne($concerne, $idprojet);
                $daterefus = new DateStatutRefusProjet($idprojet, $datejour);
                $manager->updateDateStatutRefuser($daterefus, $idprojet, $value[0]);
            }
            //MISE A JOUR DE LA CENTRALE AFFECTE
            if ($ancienStatut == ACCEPTE) {
                $centraleaffecte = new Concerne($idnouvellecentrale, $idprojet, ACCEPTE, TXT_PROJETTRANSFERT);
            } elseif ($ancienStatut == ENCOURSANALYSE) {
                $centraleaffecte = new Concerne($idnouvellecentrale, $idprojet, ENCOURSANALYSE, TXT_PROJETTRANSFERT);
            } else {
                $centraleaffecte = new Concerne($idnouvellecentrale, $idprojet, ACCEPTE, TXT_PROJETTRANSFERT);
            }
            $manager->addConcerne($centraleaffecte);
            include '../EmailProjetphase2.php'; //ENVOIE D'UN EMAIL AU DEMANDEUR AVEC COPIE DU CHAMP COMMENTAIRE
            $_SESSION['idstatutprojet'] = $idstatutprojet;
        }
    } else {
        $_SESSION['idstatutprojet'] = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
        include '../uploadphase2.php';
    }

    BD::deconnecter(); //DECONNEXION A LA BASE DE DONNEE
} else {
    include_once '../decide-lang.php';
    header('Location:/' . REPERTOIRE . '/Login_Error/' . $lang);
}
