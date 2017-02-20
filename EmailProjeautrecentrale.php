<?php

session_start();
include_once 'class/Securite.php';
include_once 'decide-lang.php';
include 'class/email.php';
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (isset($_SESSION["idprojet"])) {
    $idprojet = $_SESSION['idprojet'];
    $numero=$manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
} elseif (isset($_SESSION['numProjet'])) {
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $_SESSION['numProjet']);
    $numero =  $_SESSION['numProjet'];
}


$arrayid = $manager->getList2("select idcentrale from projetautrecentrale where idprojet=?",$idprojet);
$emailcentrales =array();
$sEmailcentrale ='';

for ($i = 0; $i < count($arrayid); $i++) {
    $mailcentrale = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale WHERE idcentrale = ? ", $arrayid[$i]['idcentrale']);
    for ($j = 0; $j <= 5; $j++) {
        if (!empty($mailcentrale[0][$j])) {
            array_push($emailcentrales, $mailcentrale[0][$j]); //construction d'un tableau d'email            
            $sEmailcentrale.=$mailcentrale[0][$j].'<br>';
        }
    }
}
//DESTINATAIRE
$maildestinataire = $emailcentrales;
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


if(!empty($_GET['nbpersonne'])){
    $nbpersonne= $_GET['nbpersonne'];
}else{
    $nbpersonne= 0;
}
//DEMANDEUR
$maildemandeur = array($manager->getSingle2("select mail from loginpassword,creer,utilisateur where idlogin_loginpassword = idlogin and idutilisateur_utilisateur = idutilisateur and idprojet_projet=?", $idprojet));


//REUPERATION DES DONNEES
$arraydonneeprojet = $manager->getList2("SELECT titre,numero,descriptionautrecentrale,acronyme,refinterneprojet FROM projet where idprojet=?", $idprojet);
$descriptionautrecentrale = strip_tags(filterEditor($arraydonneeprojet[0]['descriptionautrecentrale']));


//récupération des adresses mail et nom des centrale
$titreProjet = $arraydonneeprojet[0]['titre'];
$titre = removeDoubleQuote($titreProjet);
$body =utf8_decode(htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_MAILCONTACTDEB'))), ENT_QUOTES, 'UTF-8')) . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_BODYEMAILDEANDESOUTIENT'))), ENT_QUOTES, 'UTF-8') . ': '.$numero.' ' .
        htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_BODYEMAILDEPOSECENTRALE'))), ENT_QUOTES, 'UTF-8') . ': ' .
        htmlentities($slibellecentrale, ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_BODYEMAILDESCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br>' .
        htmlentities(stripslashes(removeDoubleQuote($descriptionautrecentrale)), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_bodyEMAILCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_RESPONSABLEBODYTEMAIL8'))), ENT_QUOTES, 'UTF-8'). '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_MAILCONTACTFIN'))), ENT_QUOTES, 'UTF-8'). '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_ADRESSEEMAILPART'))), ENT_QUOTES, 'UTF-8') .
        $semailcentrale.'<br><br><br><br>'.        
        "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br><br>' .        
        htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_NORESPONSE1'))), ENT_QUOTES, 'UTF-8');

$sujet = utf8_decode(TXT_PROJETNUM) . $numero;
envoieEmail($body, $sujet, $maildestinataire,$maildemandeur); //envoie de l'email au responsable centrale et au copiste

header('Location: /' . REPERTOIRE . '/update_project3/' . $lang. '/' . $idprojet . '/' . $numero. '/' . $nbpersonne);

