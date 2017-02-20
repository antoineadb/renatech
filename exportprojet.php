<?php

session_start();
include 'decide-lang.php';
include 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
     header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (!empty($_POST['datedebut'])) {
    $datedebut = $_POST['datedebut'];
} else {
    $datedebut = $manager->getSingle("select min(dateprojet) from projet");
}
if (!empty($_POST['datefin'])) {
    $datefin = $_POST['datefin'];
} else {
    $datefin = $manager->getSingle("select max(dateprojet) from projet");
}
if (!empty($_POST['tous']) && $_POST['tous'] == 'on') {
    $tous = $_POST['tous'];
} else {
    $tous = 'off';
}
$data = utf8_decode("Id du projet;Date du projet;Titre du projet;référence interne;Acronyme;Numéro;Nom;Prénom;adresse;Localisation;Qualité demandeur académique;Discipline scientifique;Autres disciplines;Tutelle;Autres tutelle;Nom employeur;Autre nom employeur;Qualité demandeur industriel;Nom du responsable; E-mail du responsable;Centrale du demandeur;Projet affecté à (ou aux) centrale(s);Thématique RTB;Développement technologique;Durée (en mois);Type de projet;Type de formation;Nombre d'heure(s);Nombre d'élève(s);Nom du formateur;Date de début des travaux;Nombre de plaque(s);Nombre de run(s);Nombre total de personnes accueillies;Secteur d'activité;Votre labo est-il partenaire de ce projet?;Contact dans la centrale d'accueil;Ressource(s) sélectionné(s);projet confidentiel?;Objectif/Contexte;Descriptif du travail souhaité;pièce jointe;Source de financement;Acronymes des sources de financement ;Statut;Nom(s) du (ou des) personne(s) accueil centrale;Prénom(s) du (ou des) personne(s) accueil centrale;mail(s) du (ou des) personne(s) accueil centrale;Téléphone(s) du (ou des) personne(s) accueil centrale;Connaissance(s) technologique du (ou des) personne(s) accueil centrale;Nom de la personne impliqué n°1-10;Nom du Laboratoire / Entreprisen°1-10;Descriptif Technologique;pièce jointe;Verrous identifiés;email responsable devis;Réussite escompté");
$data .= "\n";
//Récupération de l'idcentrale de l'utilisateur
if (isset($_SESSION['mail'])) {
    $mail = $_SESSION['mail'];
} elseif (isset($_SESSION['email'])) {
    $mail = $_SESSION['email'];
}
if (isset($_SESSION['pseudo'])) {
    $pseudo = $_SESSION['pseudo'];
}
//RECUPERATION DE L'IDUTILISATEUR EN FONCTION DU PSEUDO
$idutilisateur = $manager->getSingle2("SELECT idutilisateur FROM loginpassword, utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $pseudo);
//RECUPERATION DU NOM ET DE L'ID DE LA CENTRALE DE L'IDUTILISATEUR EN FONCTION DE L'EMAIL
$rowcentrale = $manager->getListbyArray("select idcentrale,libellecentrale from centrale where email1=?", array($mail));
if (isset($rowcentrale[0]['idcentrale'])) {
    $idcentrale = $rowcentrale[0]['idcentrale'];
}
if (isset($rowcentrale[0]['libellecentrale'])) {
    $libellecentrale = $rowcentrale[0]['libellecentrale'];
}
//on va récupérer les info de la centrale dans la table utilisateur
if (empty($idcentrale)) {
    $idcentrale = $manager->getSingle2("select idcentrale_centrale from utilisateur where idutilisateur = ?", $idutilisateur);
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $idcentrale);
}
//récupération du type utilisateur
//VERIFICATION QU'IL Y A BIEN DES PROJETS DANS LA BASE DE DONNEES
$donnee = array($idcentrale, $datedebut, $datefin);
$sql = "SELECT distinct p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,p.partenaire1,p.attachement,p.attachementdesc,p.emailrespdevis,
            u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
            u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
            u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
            p.acronyme,p.description,p.contexte,p.reussite,p.verrouidentifiee,p.commentaire,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
            p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
            p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet, co.idstatutprojet_statutprojet,p.nbeleve,p.nomformateur
            FROM projet p,creer c,utilisateur u,concerne co
            WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur AND
            co.idcentrale_centrale =? and dateprojet between ? and ? order by p.idprojet asc";

$row = $manager->getListbyArray($sql, $donnee);
if (count($row) != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < count($row); $i++) {
        $idprojet = $row[$i]['idprojet'];
        $nomPays = $manager->getSingle2("select nompays from pays where idpays=?", $row[$i]['idpays_pays']);
        if ($nomPays == 'France') {
            $sit = 'Nationnal';
        } else {
            $sit = $manager->getSingle2("SELECT  libellesituationgeo FROM pays,situationgeographique WHERE idsituation = idsituation_situationgeographique AND
            idpays =?", $row[$i]['idpays_pays']);
        }
        if ($row[$i]['idqualitedemandeuraca_qualitedemandeuraca'] > 0) {
            $libqualiteaca = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca=?", $row[$i]['idqualitedemandeuraca_qualitedemandeuraca']);
        } else {
            $libqualiteaca = '';
        }
        if (!empty($row[$i]['idqualitedemandeurindust_qualitedemandeurindust'])) {
            $libqualiteindust = $manager->getSingle2("select libellequalitedemandeurindust from qualitedemandeurindust where idqualitedemandeurindust =?", $row[$i]['idqualitedemandeurindust_qualitedemandeurindust']);
        } else {
            $libqualiteindust = '';
        }
        if (!empty($row[$i]['iddiscipline_disciplinescientifique'])) {
            $libdiscipline = $manager->getSingle2("select libellediscipline from disciplinescientifique where iddiscipline =?", $row[$i]['iddiscipline_disciplinescientifique']);
        } else {
            $libdiscipline = '';
        }
        if (!empty($row[$i]['idautrediscipline_autredisciplinescientifique'])) {
            $libautrediscipline = $manager->getSingle2("select libelleautrediscipline from autredisciplinescientifique where idautrediscipline =?", $row[$i]['idautrediscipline_autredisciplinescientifique']);
            if ($libautrediscipline == 'n/a') {
                $libautrediscipline = '';
            }
        } else {
            $libautrediscipline = "";
        }
        if (!empty($row[$i]['idtutelle_tutelle'])) {
            $libtutelle = $manager->getSingle2("select libelletutelle from tutelle where idtutelle =?", $row[$i]['idtutelle_tutelle']);
        } else {
            $libtutelle = "";
        }
        if (!empty($row[$i]['idautrestutelle_autrestutelle'])) {
            $libautrestutelle = $manager->getSingle2("select libelleautrestutelle from autrestutelle where idautrestutelle =?", $row[$i]['idautrestutelle_autrestutelle']);
            if ($libautrestutelle == 'n/a') {
                $libautrestutelle = "";
            }
        } else {
            $libautrestutelle = "";
        }
        if (!empty($row[$i]['idemployeur_nomemployeur'])) {
            $libnomemployeur = $manager->getSingle2("select libelleemployeur from nomemployeur where idemployeur =?", $row[$i]['idemployeur_nomemployeur']);
        } else {
            $libnomemployeur = "";
        }
        if (!empty($row[$i]['idautrenomemployeur_autrenomemployeur'])) {
            $libautrenomemployeur = $manager->getSingle2("select libelleautrenomemployeur from autrenomemployeur where idautrenomemployeur =?", $row[$i]['idautrenomemployeur_autrenomemployeur']);
            if ($libautrenomemployeur == 'n/a') {
                $libautrenomemployeur = '';
            }
        } else {
            $libautrenomemployeur = "";
        }
        if (!empty($row[$i]['nomresponsable'])) {
            $nomresponsable = $row[$i]['nomresponsable'];
        } else {
            $nomresponsable = "";
        }
        $mailresponsable = "";
        if (!empty($row[$i]['mailresponsable'])) {
            $mailresponsable = $row[$i]['mailresponsable'];
        } else {
            $mailresponsable = "";
        }
        $libcentrale = '';
        if (!empty($row[$i]['idcentrale_centrale'])) {
            $libcentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale =?", $row[$i]['idcentrale_centrale']);
        } else {
            $libcentrale = '';
        }
        if (!empty($row[$i]['refinterneprojet'])) {
            $refinterne = $row[$i]['refinterneprojet'];
        } else {
            $refinterne = "";
        }
        if(!empty($row[$i]['acronyme'])){
            $acronyme=$row[$i]['acronyme'];
        }else{
            $acronyme='';
        }
//ADRESSE
        $Adresse = utf8_decode(filtredonnee($row[$i]['adresse']));
        $adresse = str_replace(";", ",", $Adresse);
        //RECUPERATION DES CENTRALES OU LE PROJET EST AFFECTE
        $rowCentrale = $manager->getListbyArray("SELECT  libellecentrale FROM  concerne, centrale,projet WHERE idprojet_projet = idprojet AND idcentrale_centrale = idcentrale and idprojet_projet =?", array($idprojet));
        $libellecentrale = '';
        for ($k = 0; $k < count($rowCentrale); $k++) {
            $libellecentrale .= $rowCentrale[$k]['libellecentrale'] . " - "; //ENLEVE LE DERNIER -
        }
        $libelleCentrale = substr($libellecentrale, 0, -2);
        $idstatutprojet = $row[$i]['idstatutprojet_statutprojet'];
        $libellestatutprojet = $manager->getSingle2("select libellestatutprojet from statutprojet where idstatutprojet=?", $idstatutprojet);
        //THEMATIQUE
        $libthematique = '';
        $idthematiqueAutres = $manager->getSingle2("select idthematique from thematique where libellethematique=?", "Autres");
        if (isset($row[$i]['idthematique_thematique'])) {
            if ($row[$i]['idthematique_thematique'] != $idthematiqueAutres) {///AUTRES
                $libthematique = $manager->getSingle2("select libellethematique from thematique where  idthematique = ?", $row[$i]['idthematique_thematique']);
            } else {
                $libthematique = $manager->getSingle2("select libelleautrethematique from autrethematique where idautrethematique = ?", $row[$i]['idautrethematique_autrethematique']);
            }
        } else {
            $libthematique = "";
        }
        $devtechno = $row[$i]['devtechnologique']; //TYPE BOOLEAN DONC OUI OU NON
        if ($devtechno == 1) {
            $devtechno = TXT_AVECDEV;
        } else {
            $devtechno = TXT_SANSDEV;
        }
        $dureeProjet = '';
        if (!empty($row[$i]['dureeprojet'])) {
            $duree = $row[$i]['dureeprojet'];
            $idperiodicite = $row[$i]['idperiodicite_periodicite'];
            switch ($idperiodicite) {
                case 1://jour
                    $dureeProjet = ceil($duree / 30);
                    break;
                case 2://Mois
                    $dureeProjet = ceil($duree);
                    break;
                case 3://Année
                    $dureeProjet = ceil($duree * 12);
                    break;
                default:
                    break;
            }
        }
        if (!empty($row[$i]['datedebuttravaux'])) {
            $date = date_create($row[$i]['datedebuttravaux']);
            $datedebuttravaux = date_format($date, 'd-m-Y');
            ;
        }else{
            $datedebuttravaux = '';
        }
//TYPE DE PROJET
        if (!empty($row[$i]['idtypeprojet_typeprojet'])) {
            $libelletypeprojet = $manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $row[$i]['idtypeprojet_typeprojet']);
            //TRAITEMENT DES TYPE DE FORMATIONS
            if (!empty($row[$i]['typeformation'])) {
                $typeformation = removeDoubleQuote( html_entity_decode(stripslashes($row[$i]['typeformation'])));
            } else {
                $typeformation = '';
            }
//TRAITEMENT DU NOMBRE D'HEURE DE FORMATION
            if (!empty($row[$i]['nbheure'])) {
                $nbheure = $row[$i]['nbheure'];
            } else {
                $nbheure = '';
            }
//TRAITEMENT DU NOMBRE D'ELEVE
            if ($row[$i]['nbeleve'] > 0) {
                $nbeleve = $row[$i]['nbeleve'];
            } else {
                $nbeleve = null;
            }
//TRAITEMENT DU NOM DUU FORMATEUR
            if (!empty($row[$i]['nomformateur'])) {
                $nomformateur = removeDoubleQuote( utf8_decode(stripslashes($row[$i]['nomformateur'])));
            } else {
                $nomformateur = '';
            }
        }
//CONTEXTE
        if (isset($row[$i]['contexte'])) {
            $context = preg_replace("/(\r\n|\n|\r)/", " ", $row[$i]['contexte']);
            $Contexte = str_replace("’", "'", $context);
            $Context = removeDoubleQuote( $Contexte);
            $contexte = stripslashes(trim(utf8_decode(strip_tags(str_replace(";", "", $Context)))));
        } else {
            $contexte = '';
        }
//CONTEXTE
        if (isset($row[$i]['description'])) {
            $descript = preg_replace("/(\r\n|\n|\r)/", " ", $row[$i]['description']);
            $Descriptif = str_replace("’", "'", $descript);
            $Descript = removeDoubleQuote( $Descriptif);
            $descriptif = stripslashes(trim(utf8_decode(strip_tags(str_replace(";", "", $Descript)))));
        } else {
            $descriptif = '';
        }
//DESCRIPTIF TECHNOLOGIQUE
        if (isset($row[$i]['descriptiftechnologique'])) {
            $descriptTechno = preg_replace("/(\r\n|\n|\r)/", " ", $row[$i]['descriptiftechnologique']);
            $DescriptifTechno = str_replace("’", "'", $descriptTechno);
            $DescriptTechno = removeDoubleQuote( $DescriptifTechno);
            $descriptifTechno = stripslashes(trim(html_entity_decode(strip_tags(str_replace(";", "", $DescriptTechno)))));
            $descriptifTechno = str_replace("-","_", $descriptifTechno);
            $descriptifTechno = str_replace("+","_", $descriptifTechno);
        } else {
            $descriptifTechno = '';
        }
//PIECE POINTE   
        if (isset($row[$i]['attachement'])) {
            $attachement = $row[$i]['attachement'];
        } else {
            $attachement = '';
        }
        if (isset($row[$i]['attachementdesc'])) {
            $attachementdesc = $row[$i]['attachementdesc'];
        } else {
            $attachementdesc = '';
        }
//VERROUS IDENTIFIES
        if (isset($row[$i]['verrouidentifiee'])) {
            $verrouIdentifiee = preg_replace("/(\r\n|\n|\r)/", " ", $row[$i]['verrouidentifiee']);
            $VerrouIdentifiee = str_replace("’", "'", $verrouIdentifiee);
            $Verrouidentifiee = removeDoubleQuote( $VerrouIdentifiee);
            $verrouidentifiee = stripslashes(trim(html_entity_decode(strip_tags(str_replace(";", "", $Verrouidentifiee)))));
        } else {
            $verrouidentifiee = '';
        }
//REUSSIE ESCOMPTE
        if (isset($row[$i]['reussite'])) {
            $reussite0 = preg_replace("/(\r\n|\n|\r)/", " ", $row[$i]['reussite']);
            $reussite1 = str_replace("’", "'", $reussite0);
            $reussite2 = removeDoubleQuote( $reussite1);
            $reussite = stripslashes(trim(html_entity_decode(strip_tags(str_replace(";", "", $reussite2)))));
        } else {
            $reussite = '';
        }        
//EMAIL RESPONSABLE DEVIS        
        if (isset($row[$i]['emailrespdevis'])) {
            $emailrespdevis = $row[$i]['emailrespdevis'];
        }else{
            $emailrespdevis='';
        }
        //TRAITEMENT DU NOMBRE DE PLAQUES ET DE RUN
        $nbplaque = '';
        if (isset($row[$i]['nbplaque'])) {
            $nbplaque = $row[$i]['nbplaque'];
            if ($nbplaque == 0) {
                $nbplaque = '';
            }
        }
        $nbrun = '';
        if (isset($row[$i]['nbrun'])) {
            $nbrun = $row[$i]['nbrun'];
            if ($nbrun == 0) {
                $nbrun = '';
            }
        }
        //TRAITEMENT DU NOMBRE DE PERSONNE ACCUEIL CENTRALE
        $nbpersonneaccueil = $manager->getSingle2("select count(idprojet_projet) from projetpersonneaccueilcentrale where idprojet_projet=?", $idprojet);
        if ($nbpersonneaccueil == 0) {
            $nbpersonneaccueil = '';
        }
//DOMAINE APPLICATION POUR LES INDUSTRIELS
        $secteuractivite = "";
        if ($row[$i]['idqualitedemandeurindust_qualitedemandeurindust'] > 0) {
            //ON EST UTILISATEUR INDUSTRIEL
            $secteuractivite = $manager->getSingle2("SELECT s.libellesecteuractivite FROM intervient i,utilisateur u,creer c, secteuractivite s,projet p
        WHERE   i.idutilisateur_utilisateur = u.idutilisateur AND c.idutilisateur_utilisateur = u.idutilisateur AND c.idprojet_projet = p.idprojet 
        AND s.idsecteuractivite = i.idsecteuractivite_secteuractivite and idprojet=?", $idprojet);
        }
        $centralepartenaire = $row[$i]['centralepartenaire'];
        if ($centralepartenaire == 't') {
            $centralepartenaire = TXT_OUI;
        } else {
            $centralepartenaire = TXT_NON;
        }
        if (!empty($row[$i]['contactscentraleaccueil'])) {
            $contactscentraleaccueil = $row[$i]['contactscentraleaccueil'];
        } else {
            $contactscentraleaccueil = "";
        }
        //RECUPERATION DES RESSOURCES
        $libelleressource = '';
        $rowRessource = $manager->getListbyArray("SELECT libelleressource FROM ressourceprojet,ressource,projet WHERE idprojet_projet = idprojet AND idressource_ressource = idressource
and idprojet =?", array($idprojet));
        for ($k = 0; $k < count($rowRessource) - 1; $k++) {
            $libelleressource .= $rowRessource[$k]['libelleressource'] . " - ";
        }
        $libelleRessource = substr($libelleressource, 0, -2);
        //CONFIDENTIEL
        $confidentiel = $row[$i]['confidentiel'];
        if ($confidentiel == 't') {
            $confidentiel = TXT_OUI;
        } else {
            $confidentiel = TXT_NON;
        }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              SOURCE DE FINANCEMENT
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$s_Sourcefinancement = '';
        $arraysourcefinancement = $manager->getList2("SELECT libellesourcefinancement FROM sourcefinancement,projetsourcefinancement WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet_projet =?", $idprojet);
        for ($k = 0; $k < count($arraysourcefinancement); $k++) {
            $s_Sourcefinancement .= removeDoubleQuote( $arraysourcefinancement[$k]['libellesourcefinancement']) . ' - ';
        }
        if (count($arraysourcefinancement) > 0) {
            $s_Sourcefinancement = substr(trim($s_Sourcefinancement), 0, -1);
        } else {
            $s_Sourcefinancement = '';
        }        
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                      ACRONYME  SOURCE DE FINANCEMENT
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $s_acronymesf = '';
        $arrayacrosourcefinancement = $manager->getList2("SELECT acronymesource FROM projetsourcefinancement WHERE idprojet_projet =?", $idprojet);        
        for ($k = 0; $k < count($arrayacrosourcefinancement); $k++) {
            if(!empty($arrayacrosourcefinancement[$k]['acronymesource'])){
                $s_acronymesf .= removeDoubleQuote( $arrayacrosourcefinancement[$k]['acronymesource']). ' - ';
            }
        }
        if (count($arrayacrosourcefinancement) > 0 ) {
            if(!empty($s_acronymesf)){
                $s_acronymesf =  substr(trim($s_acronymesf), 0, -1);
            }
        } else {
            $s_acronymesf = '';
        }        
        //RECUPERATION DES INFOS DES PERSONNES ACCUEILS CENTRALES
        $rowpersonne = $manager->getListByArray("SELECT nomaccueilcentrale,prenomaccueilcentrale,mailaccueilcentrale,telaccueilcentrale,connaissancetechnologiqueaccueil,
        idqualitedemandeuraca_qualitedemandeuraca,idprojet
        FROM personneaccueilcentrale,projetpersonneaccueilcentrale,projet
        WHERE idprojet_projet = idprojet AND idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale AND idprojet =?", array($idprojet));
        $nomPersonne = '';
        $prenomPersonne = '';
        $mailPersonne = '';
        $telPersonne = '';
        $connaissancePersonne = '';
        for ($j = 0; $j < count($rowpersonne); $j++) {
            $nomPersonne .= $rowpersonne[$j]['nomaccueilcentrale'] . " - ";
            $prenomPersonne .= $rowpersonne[$j]['prenomaccueilcentrale'] . " - ";
            $mailPersonne .= $rowpersonne[$j]['mailaccueilcentrale'] . " - ";
            $telPersonne .= $rowpersonne[$j]['telaccueilcentrale'] . " - ";
            $connaissancePersonne .= noreturn($rowpersonne[$j]['connaissancetechnologiqueaccueil'] ). " - ";
        }
        $nompersonne = substr($nomPersonne, 0, -2);
        $prenompersonne = substr($prenomPersonne, 0, -2);
        $mailpersonne = substr($mailPersonne, 0, -2);
        $telpersonne = substr($telPersonne, 0, -2);
        $connaissancepersonne = substr($connaissancePersonne, 0, -2);
        $connaissancepersonne = str_replace("-",",", $connaissancepersonne);
        // PARTENAIRE  
        //Nom de la personne impliqué n°1
        if (!empty($row[$i]['partenaire1'])) {
            $partenaire1 = (filtredonnee($row[$i]['partenaire1']));
        } else {
            $partenaire1 = '';
        }
        //Nom du Laboratoire / Entreprisen°1
        if (!empty($row[$i]['centralepartenaireprojet']) && $row[$i]['centralepartenaireprojet'] != 'Autres') {
            $centralepartenaireprojet = (filtredonnee($row[$i]['centralepartenaireprojet']));
        } elseif ($row[$i]['centralepartenaireprojet'] == 'Autres') {
            if (!empty($row[$i]['autrenomcentrale'])) {
                $centralepartenaireprojet = (filtredonnee($row[$i]['autrenomcentrale']));
            } else {
                $centralepartenaireprojet = '';
            }
        } else {
            $centralepartenaireprojet = '';
        }
        //RECUPERATION DES INFOS DES PARTENAIRES DU PROJET
        $rowpartenaireprojet = $manager->getList2("SELECT nompartenaire,nomlaboentreprise FROM partenaireprojet,projetpartenaire WHERE idpartenaire_partenaireprojet = idpartenaire AND idprojet_projet=?", $idprojet);
        $nomPartenaire = '';
        $nomLaboentreprise = '';
        for ($j = 0; $j < count($rowpartenaireprojet) - 1; $j++) {
            $nomPartenaire .= $rowpartenaireprojet[$j]['nompartenaire'] . " - ";
        }
        for ($j = 0; $j < count($rowpartenaireprojet) - 1; $j++) {
            if (!empty($rowpartenaireprojet[$j]['nomlaboentreprise'])) {
                $nomLaboentreprise .= $rowpartenaireprojet[$j]['nomlaboentreprise'] . " - ";
            }
        }
        if (empty($nomPartenaire)) {
            $nompartenaire = $partenaire1;
        } else {
            $nompartenaire = $partenaire1 . ' - ' . substr($nomPartenaire, 0, -2);
        }
        if (empty($nomLaboentreprise)) {
            $nomlaboentreprise = $centralepartenaireprojet;
        } else {
            $nomlaboentreprise = $centralepartenaireprojet . ' - ' . substr($nomLaboentreprise, 0, -2);
        }
        //date du jour au bon format
        $originalDate = date('d-m-Y');
        $data .= "" .
                $row[$i]['idprojet'] . ";" .
                $row[$i]['dateprojet'] . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($row[$i]['titre'])))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($acronyme)))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($refinterne)))) . ";" .
                utf8_decode($row[$i]['numero']) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($row[$i]['nom'])))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($row[$i]['prenom'])))) . ";" .
                $adresse . ";" .
                $sit . ";" .
                utf8_decode($libqualiteaca) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($libdiscipline) . ";" .
                                utf8_decode($libautrediscipline))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($libtutelle))) . ";" .
                removeDoubleQuote(stripslashes(trim(utf8_decode($libautrestutelle)))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($libnomemployeur))) . ";" .
                utf8_decode(trim($libautrenomemployeur)) . ";" .
                utf8_decode($libqualiteindust) . ";" .
                utf8_decode(trim($nomresponsable)) . ";" .
                utf8_decode(trim($mailresponsable)) . ";" .
                $libcentrale . ";" .
                $libelleCentrale . ";" .
                utf8_decode($libthematique) . ";" .
                utf8_decode($devtechno) . ";" .
                $dureeProjet . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($libelletypeprojet))) . ";" .
                removeDoubleQuote(stripslashes(trim(utf8_decode($typeformation)))) . ";" .
                $nbheure . ";" .
                $nbeleve . ";" .
                $nomformateur . ";" .
                $datedebuttravaux . ";" .
                $nbplaque . ";" .
                $nbrun . ";" .
                $nbpersonneaccueil . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($secteuractivite))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($centralepartenaire))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($contactscentraleaccueil)))) . ";" .
                utf8_decode($libelleRessource) . ";" .
                $confidentiel . ";" .
                $contexte . ";" .
                $descriptif . ";" .
                $attachement . ";" .
                utf8_decode($s_Sourcefinancement).";".
                utf8_decode($s_acronymesf)." / ".$s_acronymesf.";".
                removeDoubleQuote(stripslashes(utf8_decode($libellestatutprojet))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($nompersonne))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($prenompersonne))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($mailpersonne))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($telpersonne))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($connaissancepersonne)))) . ";" .
                stripslashes(utf8_decode($nompartenaire)) . ";" .
                stripslashes(utf8_decode($nomlaboentreprise)) . ";" .
                stripslashes(utf8_decode($descriptifTechno)) . ";".
                $attachementdesc . ";".
                stripslashes(utf8_decode($verrouidentifiee)) . ";".
                $emailrespdevis . ";".
                stripslashes(utf8_decode($reussite)) . "\n";
    }
    $libcentrale= $manager->getSingle2("SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale AND pseudo=?", $_SESSION['pseudo']);
// Déclaration du type de contenu
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=export_donnee_Brute_centrale_" . $libcentrale . '_' . $originalDate . ".csv");
    print $data;
    exit;
} else {
    echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/'.REPERTOIRE.'/exportdesProjets.php?lang=' . $lang . '")</script>';
    exit();
}
BD::deconnecter();