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
$s_accueilcentrale='';
for ($a = 1; $a <= 21; $a++) {
    $s_accueilcentrale.= "Nom de la personne accueil".$a.";Prénom de la personne accueil".$a.";mail de la personne accueil".$a.";Téléphone de la personne accueil".$a.";Connaissance technologique de la personne accueil".$a.";Qualité de la personne accueil".$a.";";
}
$s_sourcefinancement ='';
for ($b = 1; $b <= 5; $b++) {
    $s_sourcefinancement.="Source de financement ".$b.";acronyme ".$b.";";
}

$s_partenaire="";
for ($c = 2; $c <= 10; $c++) {
    $s_partenaire .= "Nom du Laboratoire&Entreprise".$c.";".utf8_decode("type d'entreprise").$c.";";
}
$s_personnesalleblanche = "";
for ($d = 1; $d <= 21; $d++) {
$s_personnesalleblanche .= "Nom de la personne accueil".$d.";Prénom de la personne accueil".$d.";mail de la personne accueil".$d.";Téléphone de la personne accueil".$d.";Connaissance technologique de la personne accueil".$d.
        ";Qualité de la personne accueil".$d.";"."Qualité non permanent".$d.";"."Autre Qualité".$d.";";    
}
$s_ressource='';
for ($q = 1; $q <=NBRESSOURCE; $q++) {
    $s_ressource.="Ressource n°".$q.";";
}

$data = utf8_decode("Id du projet;Date du projet;Titre du projet;référence interne;Commentaire du projet;Nom du porteur;prénom du porteur;Date d'affectation;Nom de l'administrateur;prénom de l'administrateur;"
        . "Date d'affectation;Acronyme;Numéro;Nom;Prénom;adresse;Localisation;Qualité demandeur académique;Discipline scientifique;Autres disciplines;Tutelle;Autres tutelle;Nom employeur;Autre nom employeur;"
        . "Qualité demandeur industriel;Nom du responsable; E-mail du responsable;Centrale du demandeur;Projet affecté à (ou aux) centrale(s);Thématique RTB;Développement technologique;Durée (en mois);"
        . "Type de projet;Type de formation;Nombre d'heure(s);Nombre d'élève(s);Nom du formateur;Date de début des travaux;Durée estimée des travaux technologiques(durant la durée du projet)(en mois);Nombre de plaque(s);Nombre de run(s);Nombre total de personnes accueillies;"
        . "Secteur d'activité;Votre labo est-il partenaire de ce projet?;Contact dans la centrale d'accueil;"
        . "".$s_ressource.""
        . "projet confidentiel?;Objectif/Contexte;Descriptif du travail souhaité;pièce jointe;"
        ."".$s_sourcefinancement.""        
        . "Source de financement 6;"
        . "Etes vous le coordinateur du projet?;Statut;"            
        . "Nom du Laboratoire&Entreprise1;"
        . utf8_decode("Type d'entreprise1;")
        ."".$s_partenaire.""
        . "Descriptif Technologique;pièce jointe;Le projet nécessite-t'il un développement technologique pour certaines étapes?;Verrous identifiés;Etape(s)réalisée(s)dans une autre centrale;Autre(s) centrale(s);"
        . "Description de l'(ou des) étape(s):;Utilisez-vous dans votre projet une centrale de proximité? ;Centrales de proximité;Descriptions de la demande;"
        . "email responsable devis;Réussite escompté;"
        ."".$s_personnesalleblanche.""     
        ."Date statut fini;"
        ."Date début de projet"
        );
$data .= "\n";
if (isset($_SESSION['pseudo'])) {
    $pseudo = $_SESSION['pseudo'];    
}

if(IDTYPEUSER==ADMINLOCAL){
    //RECUPERATION DE L'IDUTILISATEUR EN FONCTION DU PSEUDO
    $idutilisateur = $manager->getSingle2("SELECT idutilisateur FROM loginpassword, utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $pseudo);
    //RECUPERATION DU NOM ET DE L'ID DE LA CENTRALE DE L'IDUTILISATEUR EN FONCTION DE L'EMAIL
    $rowcentrale = $manager->getList2("select idcentrale,libellecentrale from centrale where idcentrale=?", IDCENTRALEUSER);
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
}elseif(IDTYPEUSER==ADMINNATIONNAL){
    $idcentrale = $_POST['centrale'];
    $datedebut = $_POST['datedebut'];
    $datefin = $_POST['datefin'];
    
}
//récupération du type utilisateur
//VERIFICATION QU'IL Y A BIEN DES PROJETS DANS LA BASE DE DONNEES
$donnee = array($idcentrale, $datedebut, $datefin,REFUSE);
$sql = "SELECT 
        distinct p.idprojet,p.descriptionautrecentrale,p.descriptioncentraleproximite,p.centraleproximite,p.devtechnologique,p.etapeautrecentrale,p.porteurprojet,
        u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,p.attachement,p.attachementdesc,p.emailrespdevis,
        u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
        u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,
        u.idautrediscipline_autredisciplinescientifique,u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,
        u.entrepriselaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,p.acronyme,p.description,p.contexte,p.reussite,p.verrouidentifiee,p.commentaire,p.confidentiel,
        p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,p.refinterneprojet,
        p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,p.dureeestime,p.periodestime,p.nbheure,
        p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet, co.idstatutprojet_statutprojet,p.nbeleve,p.nomformateur,
        p.idtypecentralepartenaire,p.datestatutfini, p.datedebutprojet
        FROM projet p
        LEFT JOIN creer c ON c.idprojet_projet = p.idprojet
        LEFT JOIN concerne co ON p.idprojet = co.idprojet_projet
        LEFT JOIN utilisateur u ON u.idutilisateur = c.idutilisateur_utilisateur
        WHERE co.idcentrale_centrale =? 
        AND dateprojet BETWEEN ? AND ?
        AND co.idstatutprojet_statutprojet !=?
        AND trashed != TRUE
        ORDER BY p.idprojet ASC";
$row = $manager->getListbyArray($sql, $donnee);

$nbrow = count($row);
if($nbrow>300){
    echo ' <script>alert("' . TXT_INTERVALEDATE . '");window.location.replace("/' . REPERTOIRE . '/exportdesProjetsBrute.php?lang=' . $lang . '")</script>';
    exit();
}
if ($nbrow != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < $nbrow; $i++) {
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
        if (!empty($row[$i]['acronyme'])) {
            $acronyme = $row[$i]['acronyme'];
        } else {
            $acronyme = '';
        }
        $titre = cleanForExportOther($row[$i]['titre']);
        $dateprojet = $row[$i]['dateprojet'];
        $datedebutprojet = $row[$i]['datedebutprojet'];
        if($row[$i]['idstatutprojet_statutprojet']!= REFUSE){
            $dateStatutFini = $row[$i]['datestatutfini'];
        }else{
            $dateStatutFini = '';
        }
        $numero = $row[$i]['numero'];
//ADRESSE
        $Adresse = utf8_decode(filtredonnee($row[$i]['adresse']));
        $adresse = str_replace(";", ",", $Adresse);

        $nom = removeDoubleQuote(stripslashes(utf8_decode(trim($row[$i]['nom']))));
        $prenom = removeDoubleQuote(stripslashes(utf8_decode(trim($row[$i]['prenom']))));

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
        
        $dureeEstimeProjet = '';
        if (!empty($row[$i]['dureeestime'])) {
            $duree = $row[$i]['dureeestime'];
            $dureeEstimeProjet = ceil($duree); 
        }else{
            $dureeEstimeProjet='';
        }       
        
        if (!empty($row[$i]['datedebuttravaux'])) {
            $date = date_create($row[$i]['datedebuttravaux']);
            $datedebuttravaux = date_format($date, 'd-m-Y');
            ;
        } else {
            $datedebuttravaux = '';
        }
//TYPE DE PROJET
        if (!empty($row[$i]['idtypeprojet_typeprojet'])) {
            $libelletypeprojet = $manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $row[$i]['idtypeprojet_typeprojet']);
            //TRAITEMENT DES TYPE DE FORMATIONS
            if (!empty($row[$i]['typeformation'])) {
                $typeformation = removeDoubleQuote(html_entity_decode(stripslashes($row[$i]['typeformation'])));
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
                $nomformateur = removeDoubleQuote(utf8_decode(stripslashes($row[$i]['nomformateur'])));
            } else {
                $nomformateur = '';
            }
        }
//CONTEXTE
        if (isset($row[$i]['contexte'])) {
            $contexte = cleanForExportOther($row[$i]['contexte']);
        } else {
            $contexte = '';
        }
//CONTEXTE
        if (isset($row[$i]['description'])) {
            $descriptif = cleanForExportOther($row[$i]['description']);
        } else {
            $descriptif = '';
        }
//DESCRIPTIF TECHNOLOGIQUE
        if (isset($row[$i]['descriptiftechnologique'])) {
            $descriptifTechno = cleanForExportOther($row[$i]['descriptiftechnologique']);
        } else {
            $descriptifTechno = '';
        }
//PIECE POINTE   
        if (isset($row[$i]['attachement'])) {
            $attachement = cleanForExportOther($row[$i]['attachement']);
        } else {
            $attachement = '';
        }
        if (isset($row[$i]['attachementdesc'])) {
            $attachementdesc = cleanForExportOther($row[$i]['attachementdesc']);
        } else {
            $attachementdesc = '';
        }
//VERROUS IDENTIFIES
        if (isset($row[$i]['verrouidentifiee'])) {
            $verrouidentifiee = cleanForExport($row[$i]['verrouidentifiee']);
        } else {
            $verrouidentifiee = '';
        }
//REUSSIE ESCOMPTE
        if (isset($row[$i]['reussite'])) {
            $reussite = cleanForExport($row[$i]['reussite']);
        } else {
            $reussite = '';
        }
//EMAIL RESPONSABLE DEVIS        
        if (isset($row[$i]['emailrespdevis'])) {
            $emailrespdevis = $row[$i]['emailrespdevis'];
        } else {
            $emailrespdevis = '';
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
        
        $devtechnologique =$row[$i]['devtechnologique'];
        if ($devtechnologique == 't') {
            $devtechnologique = TXT_OUI;
        } else {
            $devtechnologique = TXT_NON;
        }
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                              ETAPES AUTRES CENTRALE
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                        
        $etapeautrecentrale  =$row[$i]['etapeautrecentrale'];
        if ($etapeautrecentrale == 't') {
            $etapeautrecentrale = TXT_OUI;
        } else {
            $etapeautrecentrale = TXT_NON;
        }
        
        //CENTRALES
        $arraycentrale = $manager->getList2("SELECT c.libellecentrale FROM projetautrecentrale p,centrale c WHERE p.idcentrale = c.idcentrale and p.idprojet=?",$idprojet);
        $nbcentrale =count($arraycentrale);
        $libelleautrecentrale= "";
        for ($ce = 0; $ce < $nbcentrale; $ce++) {
            $libelleautrecentrale .=  $arraycentrale[$ce]['libellecentrale'].' - ';
        }
        $libelleAutreCentrale = substr($libelleautrecentrale,0,-2);
        //Description de l'(ou des) étape(s):
        if(!empty($row[$i]['descriptionautrecentrale'])){
            $descriptionEtape= cleanForExportOther($row[$i]['descriptionautrecentrale']);
        }else{
            $descriptionEtape='';
        }
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                              CENTRALES DE PROXIMITES
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------          
        
        
        
        $centraleproximite = $row[$i]['centraleproximite'];
        if($centraleproximite){
            $centraleproximite = TXT_OUI;
            $arraycentraleproximte = $manager->getList2("SELECT cp.libellecentraleproximite FROM centraleproximite cp,centraleproximiteprojet pc  WHERE cp.idcentraleproximite = pc.idcentraleproximite and pc.idprojet =?",$idprojet);
            $nbcentraleproximite =count($arraycentraleproximte); 
            $libellecentraleproximite= "";
            for ($cp = 0; $cp < $nbcentraleproximite; $cp++) {                
                $libellecentraleproximite .=  $arraycentraleproximte[$cp]['libellecentraleproximite'].' - ';
            }            
            $libelleCentraleProximite = cleanForExportOther(substr($libellecentraleproximite,0,-2));
            if(!empty($row[$i]['descriptioncentraleproximite'])){
            $descriptioncentraleproximite = cleanForExportOther($row[$i]['descriptioncentraleproximite']);
            }else{
                $descriptioncentraleproximite = '';
            }
        }else {
            $centraleproximite = TXT_NON;
            $libelleCentraleProximite="";
            $descriptioncentraleproximite = '';
        }        
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                              RECUPERATION DES RESSOURCES
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
        //$libelleressource = '';
        $rowRessource = $manager->getListbyArray("SELECT libelleressource FROM ressourceprojet,ressource,projet WHERE idprojet_projet = idprojet AND idressource_ressource = idressource and idprojet =?", array($idprojet));
        $nbrowressource = count($rowRessource);        
        
        for ($k = 1; $k <= NBRESSOURCE; $k++) {
            ${'rowRessource'.$k} =  $manager->getListbyArray("SELECT libelleressource FROM ressourceprojet,ressource,projet WHERE idprojet_projet = idprojet AND idressource_ressource = idressource and idprojet =? "
                    . "and idressource=?", array($idprojet,$k));
            if(!empty(${'rowRessource'.$k})){
                if($lang=='fr'){
                    ${'ressource'.$k} = ${'rowRessource'.$k}[0]['libelleressource'];
                }else{
                    ${'ressource'.$k} = ${'rowRessource'.$k}[0]['libelleressourceen'];
                }
            }else{
                ${'ressource'.$k} ='';
            }            
        }
        //$libelleRessource = substr($libelleressource, 0, -2);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
//                              CONFIDENTIEL
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
        $confidentiel = $row[$i]['confidentiel'];
        if ($confidentiel == 't') {
            $confidentiel = TXT_OUI;
        } else {
            $confidentiel = TXT_NON;
        }
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
//                              QUESTION PORTEUR DU PROJET -->    Etes vous le coordinateur du projet? : *
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
        $porteur = $row[$i]['porteurprojet'];
        if ($porteur == 't') {
            $porteur = TXT_OUI;
        } else {
            $porteur = TXT_NON;
        }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              COMMENTAIRE PHASE1
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
        $commentaire = cleanForExportOther($manager->getSinglebyArray("select commentaireprojet from concerne where idprojet_projet=? and idcentrale_centrale=?", array($idprojet, $idcentrale)));
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PORTEUR DU PROJET
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                

        $arrayPorteur = $manager->getList2("SELECT idutilisateur,dateaffectation,nom,prenom FROM utilisateurporteurprojet,utilisateur WHERE idutilisateur_utilisateur = idutilisateur AND idprojet_projet=?", $idprojet);
        if (!empty($arrayPorteur[0]['idutilisateur'])) {
            $nomporteur = cleanForExportOther($arrayPorteur[0]['nom']);
            $prenomporteur = cleanForExportOther($arrayPorteur[0]['prenom']);
            $dateaffectation = $arrayPorteur[0]['dateaffectation'];
        } else {
            $nomporteur = '';
            $prenomporteur = '';
            $dateaffectation = '';
        }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              ADMINISTRATEUR DU PROJET
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
        $arrayAdministrateur = $manager->getList2("SELECT u.nom,u.prenom,u.idutilisateur, ua.dateaffectation FROM utilisateur u, utilisateuradministrateur ua WHERE ua.idutilisateur = u.idutilisateur  AND ua.idprojet=?", $idprojet);
        if (!empty($arrayAdministrateur[0]['idutilisateur'])) {
            $nomAdministrateur = cleanForExportOther($arrayAdministrateur[0]['nom']);
            $prenomAdministrateur = cleanForExportOther($arrayAdministrateur[0]['prenom']);
            $dateAdministrateur = $arrayAdministrateur[0]['dateaffectation'];
        } else {
            $nomAdministrateur = '';
            $prenomAdministrateur = '';
            $dateAdministrateur = '';
        }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              SOURCE DE FINANCEMENT
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
        $arraysourcefinancement1 = $manager->getList2("SELECT   libellesourcefinancement,acronymesource FROM    sourcefinancement,   projetsourcefinancement WHERE idsourcefinancement = idsourcefinancement_sourcefinancement AND idprojet_projet = ?
            and idsourcefinancement_sourcefinancement=1", $idprojet);
        if (!empty($arraysourcefinancement1)) {
            if ($arraysourcefinancement1[0]['libellesourcefinancement'] != TXT_AUTRES) {
                $sourcefinancement1 = $arraysourcefinancement1[0]['libellesourcefinancement'];
                $acronymesourcefinancement1 = $arraysourcefinancement1[0]['acronymesource'];
            } elseif ($arraysourcefinancement1[0]['libellesourcefinancement'] == TXT_AUTRES) {
                $sourcefinancement1 = $arraysourcefinancement1[0]['acronymesource'];
            } else {
                $sourcefinancement1 = '';
                $acronymesourcefinancement1='';
            }
        } else {
            $sourcefinancement1 = '';
            $acronymesourcefinancement1='';
        }
        $arraysourcefinancement2 = $manager->getList2("SELECT   libellesourcefinancement,acronymesource FROM    sourcefinancement,   projetsourcefinancement WHERE idsourcefinancement = idsourcefinancement_sourcefinancement AND idprojet_projet = ?
            and idsourcefinancement_sourcefinancement=2", $idprojet);
        if (!empty($arraysourcefinancement2)) {
            if ($arraysourcefinancement2[0]['libellesourcefinancement'] != TXT_AUTRES) {
                $sourcefinancement2 = $arraysourcefinancement2[0]['libellesourcefinancement'];
                $acronymesourcefinancement2 = $arraysourcefinancement2[0]['acronymesource'];
            } elseif ($arraysourcefinancement2[0]['libellesourcefinancement'] == TXT_AUTRES) {
                $sourcefinancement2 = $arraysourcefinancement2[0]['acronymesource'];
            } else {
                $sourcefinancement2 = '';
                $acronymesourcefinancement2='';
            }
        } else {
            $sourcefinancement2 = '';
            $acronymesourcefinancement2='';
        }
        $arraysourcefinancement3 = $manager->getList2("SELECT   libellesourcefinancement,acronymesource FROM    sourcefinancement,   projetsourcefinancement WHERE idsourcefinancement = idsourcefinancement_sourcefinancement AND idprojet_projet = ?
            and idsourcefinancement_sourcefinancement=3", $idprojet);
        if (!empty($arraysourcefinancement3)) {
            if ($arraysourcefinancement3[0]['libellesourcefinancement'] != TXT_AUTRES) {
                $sourcefinancement3 = $arraysourcefinancement3[0]['libellesourcefinancement'];
                $acronymesourcefinancement3 = $arraysourcefinancement3[0]['acronymesource'];
            } elseif ($arraysourcefinancement3[0]['libellesourcefinancement'] == TXT_AUTRES) {
                $sourcefinancement3 = $arraysourcefinancement3[0]['acronymesource'];
            } else {
                $sourcefinancement3 = '';
                $acronymesourcefinancement3='';
            }
        } else {
            $sourcefinancement3 = '';
            $acronymesourcefinancement3='';
        }

        $arraysourcefinancement4 = $manager->getList2("SELECT   libellesourcefinancement,acronymesource FROM    sourcefinancement,   projetsourcefinancement WHERE idsourcefinancement = idsourcefinancement_sourcefinancement AND idprojet_projet = ?
            and idsourcefinancement_sourcefinancement=4", $idprojet);
        if (!empty($arraysourcefinancement4)) {
            if ($arraysourcefinancement4[0]['libellesourcefinancement'] != TXT_AUTRES) {
                $sourcefinancement4 = $arraysourcefinancement4[0]['libellesourcefinancement'];
                $acronymesourcefinancement4 = $arraysourcefinancement4[0]['acronymesource'];
            } elseif ($arraysourcefinancement4[0]['libellesourcefinancement'] == TXT_AUTRES) {
                $sourcefinancement4 = $arraysourcefinancement4[0]['acronymesource'];
            } else {
                $sourcefinancement4 = '';
                $acronymesourcefinancement4='';
            }
        } else {
            $sourcefinancement4 = '';
            $acronymesourcefinancement4='';
        }
        $arraysourcefinancement5 = $manager->getList2("SELECT   libellesourcefinancement,acronymesource FROM    sourcefinancement,   projetsourcefinancement WHERE idsourcefinancement = idsourcefinancement_sourcefinancement AND idprojet_projet = ?
            and idsourcefinancement_sourcefinancement=5", $idprojet);
        if (!empty($arraysourcefinancement5)) {
            if ($arraysourcefinancement5[0]['libellesourcefinancement'] != TXT_AUTRES) {
                $sourcefinancement5 = $arraysourcefinancement5[0]['libellesourcefinancement'];
                $acronymesourcefinancement5 = $arraysourcefinancement5[0]['acronymesource'];
            } elseif ($arraysourcefinancement5[0]['libellesourcefinancement'] == TXT_AUTRES) {
                $sourcefinancement5 = $arraysourcefinancement5[0]['acronymesource'];
            } else {
                $sourcefinancement5 = '';
                $acronymesourcefinancement5='';
            }
        } else {
            $sourcefinancement5 = '';
            $acronymesourcefinancement5='';
        }
        $arraysourcefinancement6 = $manager->getList2("SELECT   libellesourcefinancement,acronymesource FROM    sourcefinancement,   projetsourcefinancement WHERE idsourcefinancement = idsourcefinancement_sourcefinancement AND idprojet_projet = ?
            and idsourcefinancement_sourcefinancement=6", $idprojet);
        if (!empty($arraysourcefinancement6)) {
            if ($arraysourcefinancement6[0]['libellesourcefinancement'] != TXT_AUTRES) {
                $sourcefinancement6 = $arraysourcefinancement6[0]['libellesourcefinancement'];
            } elseif ($arraysourcefinancement6[0]['libellesourcefinancement'] == TXT_AUTRES) {
                $sourcefinancement6 = $arraysourcefinancement6[0]['acronymesource'];
            } else {
                $sourcefinancement6 = '';
            }
        } else {
            $sourcefinancement6 = '';
        }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//      RECUPERATION DES INFOS DES PERSONNES ACCUEILS CENTRALES
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
        for ($e = 1; $e <= 21; $e++) {                           
                ${'rowpersonne'.$e} = $manager->getListbyArray("SELECT nomaccueilcentrale,prenomaccueilcentrale,mailaccueilcentrale,telaccueilcentrale,connaissancetechnologiqueaccueil,"
                        . "idqualitedemandeuraca_qualitedemandeuraca,idpersonnequalite,idautresqualite,idprojet FROM personneaccueilcentrale,projetpersonneaccueilcentrale,projet "
                        . "WHERE idprojet_projet = idprojet AND idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale AND idprojet =? order by idpersonneaccueilcentrale asc limit 1 offset ?", 
                        array($idprojet,$e-1));
            if(!empty(${'rowpersonne'.$e})){     
                ${'nomPersonne'.$e} = cleanForExportOther(${'rowpersonne'.$e}[0]['nomaccueilcentrale']);
                ${'prenomPersonne'.$e} = cleanForExportOther(${'rowpersonne'.$e}[0]['prenomaccueilcentrale']);
                ${'mailPersonne'.$e} = cleanForExportOther(${'rowpersonne'.$e}[0]['mailaccueilcentrale']);
                if($lang=='fr'){
                    ${'qualite'.$e} = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca = ?",${'rowpersonne'.$e}[0]['idqualitedemandeuraca_qualitedemandeuraca']);
                }else{
                    ${'qualite'.$e} = $manager->getSingle2("select libellequalitedemandeuracaen from qualitedemandeuraca where idqualitedemandeuraca = ?",${'rowpersonne'.$e}[0]['idqualitedemandeuraca_qualitedemandeuraca']);
                }
                if(!empty(${'rowpersonne'.$e}[0]['telaccueilcentrale'])){
                    ${'telPersonne'.$e} = cleanForExportOther(${'rowpersonne'.$e}[0]['telaccueilcentrale']);
                }else{
                    ${'telPersonne'.$e} = '';
                }
                if(!empty(${'rowpersonne'.$e}[0]['connaissancetechnologiqueaccueil'])){
                    ${'connaissancePersonne'.$e}=cleanForExportOther(${'rowpersonne'.$e}[0]['connaissancetechnologiqueaccueil']);
                }else{
                    ${'connaissancePersonne'.$e}='';
                }
                if(!empty(${'rowpersonne'.$e}[0]['idpersonnequalite'])&&${'rowpersonne'.$e}[0]['idpersonnequalite']!=IDNAAUTRESQUALITE){
                    if($lang=='fr'){
                        ${'QualiteNonPermanent'.$e} = $manager->getSingle2("select libellepersonnequalite from personnecentralequalite where idpersonnequalite = ?",${'rowpersonne'.$e}[0]['idpersonnequalite']);
                    }else{
                        ${'QualiteNonPermanent'.$e} = $manager->getSingle2("select libellepersonnequaliteen from personnecentralequalite where idpersonnequalite = ?",${'rowpersonne'.$e}[0]['idpersonnequalite']);
                    }
                }else{
                    ${'QualiteNonPermanent'.$e} = '';
                }
                if(!empty(${'rowpersonne'.$e}[0]['idautresqualite'])&&${'rowpersonne'.$e}[0]['idautresqualite']!=IDNAAUTREQUALITE){
                    ${'AutreQualite'.$e} = cleanForExportOther($manager->getSingle2("select libelleautresqualite from autresqualite where idautresqualite = ?",${'rowpersonne'.$e}[0]['idautresqualite']));
                }else{
                    ${'AutreQualite'.$e} = '';
                }
                
            }else{
                ${'nomPersonne'.$e} = '';
                ${'prenomPersonne'.$e} = '';
                ${'mailPersonne'.$e} = '';
                ${'telPersonne'.$e} = '';
                ${'connaissancePersonne'.$e} = '';
                ${'qualite'.$e} = '';
                ${'QualiteNonPermanent'.$e} = '';
                ${'AutreQualite'.$e} = '';
            }
        }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//      RECUPERATION DES INFOS DES PARTENAIRES 
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $rowpartenaireprojet = $manager->getList2("SELECT centralepartenaireprojet FROM projet WHERE idprojet=?", $idprojet); 
        //  DONNEES INCLUES DANS LA TABLE PROJET
        //ENTREPRISE N°1
        if (!empty($row[$i]['centralepartenaireprojet'])) {
            $laboentreprise1 = cleanForExportOther($row[$i]['centralepartenaireprojet']);
        } else {
            $laboentreprise1 = '';
        }
        
        if (!empty($row[$i]['idtypecentralepartenaire'])) {
            if($lang=='fr'){
                $typeentreprise1 = utf8_decode($manager->getSingle2("select libelletypepartenairefr from typepartenaire where idtypepartenaire=?",($row[$i]['idtypecentralepartenaire'])));
            }else{
                $typeentreprise1 = utf8_decode($manager->getSingle2("select libelletypepartenaireen from typepartenaire where idtypepartenaire=?",($row[$i]['idtypecentralepartenaire'])));
            }
        } else {
            $typeentreprise1 = '';
        }
        
        //  DONNEES INCLUES DANS LA TABLE PARTENAIREPROJET
        for ($f = 2; $f <= 10; $f++) {            
                ${'rowpartenaireprojet'.$f}= $manager->getList2("SELECT nomlaboentreprise FROM partenaireprojet,projetpartenaire WHERE idpartenaire_partenaireprojet = idpartenaire AND idprojet_projet=? "
                . "order by idpartenaire_partenaireprojet asc limit 1 offset ".($f-2)." ", $idprojet);
            if (!empty(${'rowpartenaireprojet'.$f}[0]['nomlaboentreprise'])) {
                ${'laboentreprise'.$f} = removeDoubleQuote(${'rowpartenaireprojet'.$f}[0]['nomlaboentreprise']);               
            }else{
                ${'laboentreprise'.$f} = '';
            }           
        }
        
        //  DONNEES INCLUES DANS LA TABLE PARTENAIRETYPEPROJET
        for ($f = 2; $f <= 10; $f++) {            
                ${'rowpartenairetypeprojet'.$f}= $manager->getList2("SELECT libelletypepartenairefr FROM typepartenaire,projettypepartenaire WHERE idtypepartenaire = idtypepartenaire_typepartenaire AND idprojet_projet=? "
                . "order by idtypepartenaire_typepartenaire asc limit 1 offset ".($f-2)." ", $idprojet);                
            if (!empty(${'rowpartenairetypeprojet'.$f}[0]['libelletypepartenairefr'])) {
                ${'libelletypepartenaire'.$f} = ${'rowpartenairetypeprojet'.$f}[0]['libelletypepartenairefr'];
            }else{
                ${'libelletypepartenaire'.$f} = '';
            }
        }
 
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//      VARIABLES LIBELLES MULTIPLE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
        $salleBlanche='';
        for ($z = 1; $z <=21; $z++) {
            $salleBlanche .= ${'nomPersonne'.$z} . ";" .${'prenomPersonne'.$z}. ";" .${'mailPersonne'.$z} . ";" .${'telPersonne'.$z}. ";" .${'connaissancePersonne'.$z} . ";" .${'qualite'.$z}. ";".${'QualiteNonPermanent'.$z}.";".${'AutreQualite'.$z} .";";
        }
        $varpartenaires='';
        for ($w = 2; $w <= 10; $w++) {
            $varpartenaires .= utf8_decode(${'laboentreprise'.$w}) . ";" .utf8_decode(${'libelletypepartenaire'.$w}) . ";"  ;
        }
        
        $varressource='';
        for ($r = 1; $r <= NBRESSOURCE; $r++) {
            $varressource.= utf8_decode(${'ressource'.$r}) . ";";
        }
        
        $varsourcefinancement = '';
        for ($s = 1; $s < NBSOURCEDEFINACEMENT; $s++) {
            $varsourcefinancement.= utf8_decode(${'sourcefinancement'.$s}) . ";".utf8_decode(${'acronymesourcefinancement'.$s}) . ";";
        }
         $varsourcefinancement .=utf8_decode($sourcefinancement6).";";
      
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//      FIN DE LA RECUPERATION DES INFOS DES PARTENAIRES 
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
        //date du jour au bon format
        $originalDate = date('d-m-Y');
        $data .= "" .
                $idprojet . ";" .
                $dateprojet . ";" .
                $titre . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($refinterne)))) . ";" .
                $commentaire . ';' .
                $nomporteur . ';' .
                $prenomporteur . ';' .
                $dateaffectation . ';' .
                $nomAdministrateur . ';' .
                $prenomAdministrateur . ';' .
                $dateAdministrateur . ';' .
                removeDoubleQuote(stripslashes(utf8_decode(trim($acronyme)))) . ";" .
                $numero . ";" .
                $nom . ";" .
                $prenom . ";" .
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
                $dureeEstimeProjet.";".
                $nbplaque . ";" .
                $nbrun . ";" .
                $nbpersonneaccueil . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($secteuractivite))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode($centralepartenaire))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($contactscentraleaccueil)))) . ";" .
                $varressource.
                $confidentiel . ";" .
                $contexte . ";" .
                $descriptif . ";" .
                $attachement . ";" .                                
                $varsourcefinancement.
                $porteur.";".
                removeDoubleQuote(stripslashes(utf8_decode($libellestatutprojet))) . ";" .               
                $laboentreprise1.";" .  
                $typeentreprise1.";".
                $varpartenaires.
                $descriptifTechno . ";" .
                $attachementdesc . ";" .
                $devtechnologique.";".
                stripslashes(utf8_decode($verrouidentifiee)) . ";" .
                $etapeautrecentrale.";".
                $libelleAutreCentrale.";".
                $descriptionEtape.";".
                $centraleproximite.";".
                $libelleCentraleProximite.";".
                $descriptioncentraleproximite.";".
                $emailrespdevis . ";" .
                stripslashes(utf8_decode($reussite)) .";".
                $salleBlanche.
                $dateStatutFini .";".
                $datedebutprojet . "\n";
        
    }
    $libcentrale = $manager->getSingle2("SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale AND pseudo=?", $_SESSION['pseudo']);
// Déclaration du type de contenu
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=export_donnee_Brute_centrale_" . $libcentrale . '_' . $originalDate . ".csv");
    print $data;
    exit;
} else {
    if(IDTYPEUSER==ADMINLOCAL){
        echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/exportdesProjetsBrute.php?lang=' . $lang . '")</script>';
        exit();
    }elseif(IDTYPEUSER==ADMINNATIONNAL){
        echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/exportProjetBruteNational.php?lang=' . $lang . '")</script>';
        exit();
    }
}
BD::deconnecter();
