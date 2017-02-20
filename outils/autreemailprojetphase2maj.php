<?php
include_once 'decide-lang.php';
include 'class/email.php';
include_once 'class/Manager.php';
include_once 'class/Securite.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}

$nomPrenomdemandeur = $manager->getList2("SELECT nom,prenom FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$nomprenomdemandeur = TXT_DEMANDEUR . ': ' . $nomPrenomdemandeur[0]['nom'] . ' - ' . $nomPrenomdemandeur[0]['prenom'];
if (isset($_GET['idprojet'])&& !empty($_GET['idprojet'])) {    
    $idprojet =  $_GET['idprojet'];
    $numero=$manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
}
//REUPERATION DES DONNEES PASSEES EN VARIABLE DE SESSION
$arraydonneeprojet = $manager->getList2("SELECT titre,confidentiel,numero,typeformation,datedebutprojet,nbplaque,nbrun,dureeprojet,idprojet,acronyme,dateprojet,contexte,commentaire,contactscentraleaccueil,datedebuttravaux,
centralepartenaire,emailrespdevis,refinterneprojet,idtypeprojet_typeprojet,idthematique_thematique,idperiodicite_periodicite,
nbheure,centralepartenaireprojet,idautrethematique_autrethematique,devtechnologique,descriptiftechnologique,
nbeleve,nomformateur,datestatutfini,datestatutcloturer,partenaire1, verrouidentifiee,reussite FROM projet where idprojet=?", $idprojet);

$arrayPartenaire = $manager->getList2("SELECT nompartenaire,nomlaboentreprise  FROM  partenaireprojet, projetpartenaire WHERE  idpartenaire_partenaireprojet = idpartenaire and idprojet_projet=?", $idprojet);
$sPartenaires = '';
for ($i = 0; $i < count($arrayPartenaire); $i++) {
    $sPartenaires .=$arrayPartenaire[$i]['nompartenaire'] . ' - ' . $arrayPartenaire[$i]['nomlaboentreprise'] . ' / ';
}
if (count($arrayPartenaire) > 0) {
    $s_partenaires = TXT_PARTENAIRE . ': ' . htmlentities(str_replace("''", "'", trim($sPartenaires)), ENT_QUOTES, 'UTF-8');
    $s_partenaires = substr($s_partenaires, 0, -7) . '<br>';
} else {
    $s_partenaires = '';
}
if (!empty($arraydonneeprojet[0]['confidentiel']) && $arraydonneeprojet[0]['confidentiel'] == TRUE) {
    $confid = TXT_PROJETCONFIDENTIEL . ': ' . TXT_OUI;
} else {
    $confid = TXT_PROJETCONFIDENTIEL . ': ' . TXT_NON;
}
$idtypeNA = $manager->getSingle2("select idtypeprojet from typeprojet where libelletype=?", 'n/a');

if (!empty($arraydonneeprojet[0]['idtypeprojet_typeprojet']) && $arraydonneeprojet[0]['idtypeprojet_typeprojet'] != $idtypeNA) {
    $libelletype = $manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $arraydonneeprojet[0]['idtypeprojet_typeprojet']);
    $typeProjet = TXT_TYPEPROJET . ': ' . $libelletype;
    $typeprojet = htmlentities(str_replace("''", "'", $typeProjet), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $typeprojet = '';
}

if (!empty($arraydonneeprojet[0]['contactscentraleaccueil'])) {
    $contactscentraleaccueil = TXT_CONTACTCENTRALACCUEIL . ': ' . $arraydonneeprojet[0]['contactscentraleaccueil'];
    $contactscentraleaccueil = htmlentities(str_replace("''", "'", $contactscentraleaccueil), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $contactscentraleaccueil = "";
}
if (!empty($arraydonneeprojet[0]['typeformation'])) {
    $typeformation = TXT_TYPEFORMATION . ': ' . $arraydonneeprojet[0]['typeformation'];
    $typeformation = htmlentities(str_replace("''", "'", $typeformation), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $typeformation = '';
}
if (isset($arraydonneeprojet[0]['datedebuttravaux'])) {
    $datefomater = date("d-M-Y", strtotime($arraydonneeprojet[0]['datedebuttravaux']));
    $datedebuttravaux = TXT_DATEDEBUTTRAVAUX . ': ' . $datefomater;
    $datedebuttravaux = htmlentities(str_replace("''", "'", $datedebuttravaux), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $datedebuttravaux = '';
}
if (isset($arraydonneeprojet[0]['dureeprojet']) && $arraydonneeprojet[0]['dureeprojet'] > 0) {
    $dureeprojet = TXT_DUREEPROJET . ': ' . $arraydonneeprojet[0]['dureeprojet'];
    $dureeprojet = htmlentities(str_replace("''", "'", $dureeprojet), ENT_QUOTES, 'UTF-8') . ' ';
    if (isset($arraydonneeprojet[0]['idperiodicite_periodicite'])) {
        $libelleperiodicite = $manager->getSingle2("select libelleperiodicite from period where idperiodicite=?", $arraydonneeprojet[0]['idperiodicite_periodicite']);
        $libelleperiodicite = htmlentities(str_replace("''", "'", $libelleperiodicite), ENT_QUOTES, 'UTF-8') . '<br>';
    }
} else {
    $dureeprojet = '';
    $libelleperiodicite = '';
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                          SOURCE DE FINANCEMENT
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$s_Sourcefinancement = '';
$arraysourcefinancement = $manager->getList2("SELECT libellesourcefinancement FROM sourcefinancement,projetsourcefinancement WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet_projet =?", $idprojet);

for ($k = 0; $k < count($arraysourcefinancement); $k++) {
    $s_Sourcefinancement .= htmlentities(str_replace("''", "'", $arraysourcefinancement[$k]['libellesourcefinancement']), ENT_QUOTES, 'UTF-8') . ' - ';
}
if (count($arraysourcefinancement) > 0) {
    $s_Sourcefinancement = TXT_SOURCEFINANCEMENT . ': ' . substr(trim($s_Sourcefinancement), 0, -1) . '<br>';
} else {
    $s_Sourcefinancement = '';
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                      ACRONYME  SOURCE DE FINANCEMENT
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$s_acronymesf = '';
$arrayacrosourcefinancement = $manager->getList2("SELECT acronymesource FROM projetsourcefinancement WHERE idprojet_projet =?", $idprojet);

for ($k = 0; $k < count($arrayacrosourcefinancement); $k++) {
    if (!empty($arrayacrosourcefinancement[$k]['acronymesource'])) {
        $s_acronymesf .= htmlentities(str_replace("''", "'", $arrayacrosourcefinancement[$k]['acronymesource']), ENT_QUOTES, 'UTF-8') . ' - ';
    }
}
if (count($arrayacrosourcefinancement) > 0) {
    if (!empty($s_acronymesf)) {
        $s_acronymesf = TXT_ACROSOURCECFINANCEMENT . ': ' . substr(trim($s_acronymesf), 0, -1) . '<br>';
    }
} else {
    $s_acronymesf = '';
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                     THEMATIQUE
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$idlibelleautrethematique = $manager->getSingle2("select idthematique from thematique where libellethematique=?", 'Autres');

if (!empty($arraydonneeprojet[0]['idthematique_thematique']) && $arraydonneeprojet[0]['idthematique_thematique'] != $idlibelleautrethematique) {
    $libelleThematique = $manager->getSingle2("select libellethematique from thematique where idthematique=?", $arraydonneeprojet[0]['idthematique_thematique']);
    $libellethematique = TXT_THEMATIQUE . ': ' . $libelleThematique;
    $libellethematique = htmlentities(str_replace("''", "'", $libellethematique), ENT_QUOTES, 'UTF-8') . '<br>';
    $libelleautrethematique = '';
} elseif (!empty($arraydonneeprojet[0]['idthematique_thematique'])) {
    $libellethematique = '';
    $libelleautreThematique = $manager->getSingle2("select libelleautrethematique from autrethematique where idautrethematique=?", $arraydonneeprojet[0]['idautrethematique_autrethematique']);
    $libelleautrethematique = TXT_AUTRETHEMATIQUE . ': ' . $libelleautreThematique;
    $libelleautrethematique = htmlentities(str_replace("''", "'", $libelleautrethematique), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $libellethematique = '';
    $libelleautrethematique = '';
}
if (isset($arraydonneeprojet[0]['descriptiftechnologique'])) {
    $descriptiftechnologique = TXT_DESCRIPTIFTECHNOLOGIQUE . ': ' . trim(strip_tags($arraydonneeprojet[0]['descriptiftechnologique']));
    $descriptiftechnologique = strip_tags(htmlentities(str_replace("''", "'", $descriptiftechnologique), ENT_QUOTES, 'UTF-8')) . '<br>';
} else {
    $descriptiftechnologique = '';
}
if (!empty($arraydonneeprojet[0]['emailrespdevis'])) {
    $emailrespdevis = TXT_EMAILRESPDEVIS . ': ' . $arraydonneeprojet[0]['emailrespdevis'];
    $emailrespdevis = htmlentities(str_replace("''", "'", $emailrespdevis), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $emailrespdevis = '';
}
if (!empty($arraydonneeprojet[0]['verrouidentifiee'])) {
    $verrouidentifiee = TXT_VERROUIDENTIFIEE . ': ' . trim(strip_tags($arraydonneeprojet[0]['verrouidentifiee']));
    $verrouidentifiee = htmlentities(str_replace("''", "'", $verrouidentifiee), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $verrouidentifiee = '';
}
if (!empty($arraydonneeprojet[0]['reussite'])) {
    $reussite = TXT_REUSSITEESCOMPTE . ': ' . (trim(strip_tags($arraydonneeprojet[0]['reussite'])));
    $reussite = (htmlentities(str_replace("''", "'", $reussite), ENT_QUOTES, 'UTF-8')) . '<br>';
} else {
    $reussite = '';
}
if (!empty($arraydonneeprojet[0]['refinterneprojet'])) {
    $refinterneprojet = TXT_REFINTERNE . ': ' . $arraydonneeprojet[0]['refinterneprojet'];
    $refinterneprojet = htmlentities(str_replace("''", "'", $refinterneprojet), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $refinterneprojet = '';
}
if (!empty($arraydonneeprojet[0]['nbplaque']) || $arraydonneeprojet[0]['nbplaque'] != 0) {
    $nbplaque = TXT_NBPLAQUE . ': ' . $arraydonneeprojet[0]['nbplaque'] . '<br>';
} else {
    $nbplaque = null;
}
if (!empty($arraydonneeprojet[0]['nbrun']) || $arraydonneeprojet[0]['nbrun'] != 0) {
    $nbrun = TXT_NBRUN . ': ' . $arraydonneeprojet[0]['nbrun'];
    $nbrun = htmlentities(str_replace("''", "'", $nbrun), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $nbrun = null;
}
if (!empty($arraydonneeprojet[0]['nbheure']) || $arraydonneeprojet[0]['nbheure'] != 0) {
    $nbheure = TXT_NBHEURE . ': ' . $arraydonneeprojet[0]['nbheure'];
    $nbheure = htmlentities(str_replace("''", "'", $nbheure), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $nbheure = null;
}
if (!empty($arraydonneeprojet[0]['nbeleve']) || $arraydonneeprojet[0]['nbeleve'] != 0) {
    $nbeleve = TXT_NBELEVE . ': ' . $arraydonneeprojet[0]['nbeleve'];
    $nbeleve = htmlentities(str_replace("''", "'", $nbeleve), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $nbeleve = null;
}
if (!empty($arraydonneeprojet[0]['centralepartenaireprojet'])) {
    $centralepartenaireprojet = TXT_CENTRALEPARTENAIRE . ': ' . $arraydonneeprojet[0]['centralepartenaireprojet'];
    $centralepartenaireprojet = htmlentities(str_replace("''", "'", $centralepartenaireprojet), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $centralepartenaireprojet = '';
}
if (!empty($arraydonneeprojet[0]['nomformateur'])) {
    $nomformateur = TXT_NOMFORMATEUR . ': ' . $arraydonneeprojet[0]['nomformateur'];
    $nomformateur = htmlentities(str_replace("''", "'", $nomformateur), ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    $nomformateur = '';
}
$arraypersonneacceuilcentrale = $manager->getList2("SELECT nomaccueilcentrale,prenomaccueilcentrale,mailaccueilcentrale,telaccueilcentrale,connaissancetechnologiqueaccueil,libellequalitedemandeuraca
FROM personneaccueilcentrale,projetpersonneaccueilcentrale,qualitedemandeuraca
WHERE idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale AND idqualitedemandeuraca =idqualitedemandeuraca_qualitedemandeuraca AND
idprojet_projet=?
", $idprojet);
if (count($arraypersonneacceuilcentrale) > 0) {
    $s_Personneacceuilcentrale = '';
    for ($j = 0; $j < count($arraypersonneacceuilcentrale); $j++) {
        $s_Personneacceuilcentrale .=$arraypersonneacceuilcentrale[$j]['nomaccueilcentrale'] . ' - ' . $arraypersonneacceuilcentrale[$j]['prenomaccueilcentrale'] . ' - ' .
                $arraypersonneacceuilcentrale[$j]['mailaccueilcentrale'] . ' - ' . $arraypersonneacceuilcentrale[$j]['telaccueilcentrale'] . ' - ' .
                $arraypersonneacceuilcentrale[$j]['connaissancetechnologiqueaccueil'] . ' - ' . $arraypersonneacceuilcentrale[$j]['libellequalitedemandeuraca'] . ' / ';
    }
    $s_personneacceuilcentrale = htmlentities(str_replace("''", "'", TXT_PERSONNEACCUEILCENTRALE), ENT_QUOTES, 'UTF-8') . ': ' . htmlentities(str_replace("''", "'", $s_Personneacceuilcentrale), ENT_QUOTES, 'UTF-8');
    $s_Personneacceuilcentrale = substr(htmlentities(str_replace("''", "'", $s_Personneacceuilcentrale), ENT_QUOTES, 'UTF-8'), 0, -2) . '<br>';
} else {
    $s_personneacceuilcentrale = '';
    $s_Personneacceuilcentrale = '';
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                          RESSOURCES
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


$s_Ressources = '';
$arrayressource = $manager->getList2("SELECT libelleressource FROM ressourceprojet,ressource WHERE idressource_ressource = idressource AND idprojet_projet =?", $idprojet);
for ($k = 0; $k < count($arrayressource); $k++) {
    $s_Ressources .= htmlentities(str_replace("''", "'", $arrayressource[$k]['libelleressource']), ENT_QUOTES, 'UTF-8') . ' - ';
}
if (count($arrayressource) > 0) {
    $s_ressources = TXT_RESSOURCES . ': ' . substr(trim($s_Ressources), 0, -1);
} else {
    $s_ressources = '';
}

//récupération des adresses mail et nom des centrale
$titreProjet = $arraydonneeprojet[0]['titre'];
$titre = str_replace("''", "'", $titreProjet);
$body = htmlentities(stripslashes(str_replace("''","'",affiche('TXT_MAILCONTACTDEB10'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILMODIF0'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILMODIF1'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_TITREPROJETEMAIL'))), ENT_QUOTES, 'UTF-8') . ': ' . htmlentities($titre, ENT_QUOTES, 'UTF-8') . '<br>' . //TITRE
        htmlentities($nomprenomdemandeur, ENT_QUOTES, 'UTF-8') . '<br>' .
        htmlentities($confid, ENT_QUOTES, 'UTF-8') . '<br>' .
        $typeprojet .
        $contactscentraleaccueil .
        $typeformation .
        $datedebuttravaux .
        $dureeprojet .
        $libelleperiodicite .
        $s_Sourcefinancement .
        $s_acronymesf .
        $libellethematique .
        $libelleautrethematique .
        $descriptiftechnologique .
        $emailrespdevis .
        $verrouidentifiee .
        $reussite .
        $refinterneprojet .
        $nbplaque .
        $nbrun .
        $nbheure .
        $nbeleve .
        $centralepartenaireprojet .
        $nomformateur .
        $s_personneacceuilcentrale . '    ' . $s_Personneacceuilcentrale .
        $s_partenaires .
        $s_ressources .
        '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL93'))), ENT_QUOTES, 'UTF-8') .
        '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_MAILCONTACTFIN13'))), ENT_QUOTES, 'UTF-8') .
        "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br>    ' .
        '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_NORESPONSE6'))), ENT_QUOTES, 'UTF-8');

$sujet = utf8_decode(TXT_MODIFPROJETNUM) . $numero;

$emailcentrales = array();
$idcentraleAffectation = $manager->getSingle2("select idcentrale_centrale from concerne where idprojet_projet=?", $idprojet);

$mailcentrale = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale WHERE idcentrale = ? ", $idcentraleAffectation);

for ($i = 0; $i <= 5; $i++) {
    if (!empty($mailcentrale[0][$i])) {
        array_push($emailcentrales, $mailcentrale[0][$i]); //construction d'un tableau d'email des responsable de la centrale
    }
}
if (isset($_SESSION['mail'])) {
    $maildemandeur = array($_SESSION['mail']);
} elseif (isset($_SESSION['email'])) {
    $maildemandeur = array($_SESSION['email']);
}
$arrayemailCC = array();
$mailresponsable = $manager->getSingle2("SELECT mailresponsable FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
if (!empty($mailresponsable)) {
    array_push($arrayemailCC, $mailresponsable); //EMAIL DU RESPONSABLE SI IL EXISTE
    $CC = $arrayemailCC;
} else {
    $CC = $arrayemailCC;
}
$emailcc = array_merge($emailcentrales, $CC);
$mailCC = array_unique($emailcc);
/*echo '<pre>maildemandeur';print_r($maildemandeur);echo '</pre>';
echo '<pre>mailCC';print_r($mailCC);echo '</pre>';exit();*/
envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste


if(!empty($_GET['nbpersonne'])){
    $nbpersonne= $_GET['nbpersonne'];
}else{
    $nbpersonne= 0;
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
//                              ENVOIE DE L'EMAIL DES AUTRES CENTRALE
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------



$arrayid = $manager->getList2("select idcentrale from projetautrecentrale where idprojet=?",$idprojet);
$emailCentrales =array();
$sEmailcentrale ='';

for ($i = 0; $i < count($arrayid); $i++) {
    $mailcentrale = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale WHERE idcentrale = ? ", $arrayid[$i]['idcentrale']);
    for ($j = 0; $j <= 5; $j++) {
        if (!empty($mailcentrale[0][$j])) {
            array_push($emailCentrales, $mailcentrale[0][$j]); //construction d'un tableau d'email            
            $sEmailcentrale.=$mailcentrale[0][$j].'<br>';
        }
    }
}
//DESTINATAIRE
$maildestinataire = $emailCentrales;
$semailcentrale =  '<br>'.substr($sEmailcentrale,0,-1);

$libellecentrale =array();
$sLibellecentrale='';
for ($i = 0; $i < count($arrayid); $i++) {
    $arraylibellecentrale = $manager->getList2("SELECT libellecentrale FROM centrale WHERE idcentrale = ?", $arrayid[$i]['idcentrale']);    
    if(!empty($arraylibellecentrale[0]['libellecentrale'])){
            array_push($libellecentrale, $arraylibellecentrale[0]['libellecentrale']); //construction d'un tableau d'email            
            $sLibellecentrale.=$arraylibellecentrale[0]['libellecentrale'].', ';
    }
}
$slibellecentrale =  substr($sLibellecentrale,0,-2);

if(isset($_GET['etautrecentrale'])&&!empty($_GET['etautrecentrale'])){
    $etautrecentrale = utf8_decode(Securite::bdd(trim($_GET['etautrecentrale'])));
}

//DEMANDEUR
$maildemandeur = array($manager->getSingle2("select mail from loginpassword,creer,utilisateur where idlogin_loginpassword = idlogin and idutilisateur_utilisateur = idutilisateur and idprojet_projet=?", $idprojet));


//REUPERATION DES DONNEES
$arrayDonneeprojet = $manager->getList2("SELECT titre,numero,descriptionautrecentrale,acronyme,refinterneprojet FROM projet where idprojet=?", $idprojet);
$descriptionautrecentrale = strip_tags(filterEditor($arrayDonneeprojet[0]['descriptionautrecentrale']));

$body =utf8_decode(htmlentities(stripslashes(str_replace("''","'",affiche('TXT_MAILCONTACTDEB'))), ENT_QUOTES, 'UTF-8')) . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILDEANDESOUTIENT'))), ENT_QUOTES, 'UTF-8') . ': '.$numero.' ' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILDEPOSECENTRALE'))), ENT_QUOTES, 'UTF-8') . ': ' .
        htmlentities($slibellecentrale, ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILDESCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br>' .
        htmlentities(stripslashes(str_replace("''","'",$descriptionautrecentrale)), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_bodyEMAILCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL8'))), ENT_QUOTES, 'UTF-8'). '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_MAILCONTACTFIN'))), ENT_QUOTES, 'UTF-8'). '<br><br>' .
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_ADRESSEEMAILPART'))), ENT_QUOTES, 'UTF-8') .
        $semailcentrale.'<br><br><br><br>'.        
        "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br><br>' .        
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_NORESPONSE1'))), ENT_QUOTES, 'UTF-8');

$sujet = utf8_decode(TXT_PROJETNUM) . $numero;
envoieEmail($body, $sujet, $maildestinataire,$maildemandeur); //envoie de l'email au responsable centrale et au copiste
header('Location: /' . REPERTOIRE . '/update_project3/' . $lang. '/' . $_GET['idprojet'] . '/' . $numero. '/' . $nbpersonne);
