<?php

include_once 'decide-lang.php';
include_once 'class/email.php';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

include_once 'outils/toolBox.php';
if (!empty($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (!empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} elseif (!empty($_SESSION['idprojet'])) {
    $idprojet = $_SESSION['idprojet'];
}
$numprojet = $manager->getSingle2("select numero from projet where idprojet=?",$idprojet);
//récupération des adresses mail et nom des centrale
//CENTRALE SOURCE      
//TRAITEMENT DU LIBELLE DANS L'EMAIL
if (!empty($_SESSION['idcentrale'])) {
    $idcentrale = $_SESSION['idcentrale'];
    $infocentrale = $manager->getList2("select libellecentrale,email1,email2,email3,email4,email5 from centrale where idcentrale=?", $_SESSION['idcentrale']);
    $nbinfocentrale=count($infocentrale);				
    $centrale = $infocentrale[0]['libellecentrale'];
    $emailCentrale = '';
    for ($i = 0; $i < count($infocentrale); $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!empty($infocentrale[$i]['email' . $j])) {
                $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
            }
        }
    }
} elseif (!empty($_POST['centrale'])) {
    $idcentrale = (int) substr($_POST['centrale'], -1);
    $infocentrale = $manager->getList2("select libellecentrale,email1,email2,email3,email4,email5 from centrale where idcentrale=?", $idcentrale);
    $centrale = $infocentrale[0]['libellecentrale'];
    $emailCentrale = '';
    for ($i = 0; $i < count($infocentrale); $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!empty($infocentrale[$i]['email' . $j])) {
                $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
            }
        }
    }
} else {
    $centrale = $manager->getSingle2('SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale and pseudo=?', $_SESSION['pseudo']);
    $infocentrale = $manager->getList2("select libellecentrale,email1,email2,email3,email4,email5 from centrale where libellecentrale=?", $centrale);
    $emailCentrale = '';
    for ($i = 0; $i < count($infocentrale); $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!empty($infocentrale[$i]['email' . $j])) {
                $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
            }
        }
    }
}
if(empty($idcentrale)){
    $idcentrale = (int) substr($_POST['centraleV'], -1);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DES EMAILS
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$emailcentrales = array();
$mailcentrales = $manager->getList2("select email1,email2,email3,email4,email5 from centrale where idcentrale=?", $idcentrale);
for ($i = 0; $i <= count($mailcentrales); $i++) {
    if (!empty($mailcentrales[0][$i])) {
        array_push($emailcentrales, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
    }
}
$infodemandeur = array($manager->getList2('SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur
            AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
$maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER
//AJOUT DE L'EMAIL DU RESPONSABLE SI IL EXISTE   
$arrayemailCC = array();
if (!empty($infodemandeur[0][0]['mailresponsable'])) {
    array_push($arrayemailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
    $CC = array_merge($arrayemailCC, $arrayemailCC);
} else {
    $CC = $arrayemailCC;
}
$emailcc = array_merge($emailcentrales, $CC);
$mailCC = array_unique($emailcc);
if (!empty($_POST['comment'])) {
    $commentaires = str_replace("\\", "", $_POST['comment']);
    $commentaire = htmlentities(affiche('TXT_COMMENTSTATUT'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities(strip_tags(str_replace("''", "'", $commentaires)), ENT_QUOTES, 'UTF-8');
} elseif (!empty($_POST['commentairephase2Valeur'])) {
    $commentaires = str_replace("\\", "", $_POST['commentairephase2Valeur']);
    $commentaire = htmlentities(affiche('TXT_COMMENTSTATUT'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities(strip_tags(str_replace("''", "'",$commentaires)), ENT_QUOTES, 'UTF-8');
} else {
    $commentaire = '';
}
$titreProjet = $manager->getSingle("select titre from projet where numero='" . $numprojet . "'");
$titre = str_replace("''", "'", utf8_decode($titreProjet));
if (!empty($_POST['statutProjet'])) {
    $idStatut = (int) substr($_POST['statutProjet'], -1);
} elseif (!empty($_SESSION['idstatutprojet'])) {
    $idStatut = (int) substr($_SESSION['idstatutprojet'], -1);
} else {
    $idStatut = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
}
$txtbodyref = utf8_decode(affiche('TXT_BOBYREF'));
$sujet =  utf8_decode(TXT_DEMANDEPROJET) . ' : ' . utf8_decode($titre) . ' ' . $txtbodyref . ' ' . $numprojet;

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT ACCEPTE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

if ($idStatut === ACCEPTE && isset($_POST['creer_phase2'])) {//PROJET PROVENENANT DE LA CREATION D'UNE DEMANDE DE PROJET PHASE1+PHASE2					
    $body = htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MAILCONTACTDEB1'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .(htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE200'))), ENT_QUOTES, 'UTF-8')) . '<br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE221'))), ENT_QUOTES, 'UTF-8') . ' ' .
            "<a href=".ADRESSESITE." >" . affiche('TXT_BODYEMAILPHASE220') . "</a>" . '<br><br>' .htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE230'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE240'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL81'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MAILCONTACTFIN2'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(affiche('TXT_RESPONSABLEBODYTEMAIL10'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . '<br>' . $emailCentrale . '<br><br>' .
            '<a href='.ADRESSESITE.'>' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>      ' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_NORESPONSE'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
    envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
    header('Location: /' . REPERTOIRE . '/accepted_project/' . $lang . '/' . $idprojet . '/' . ACCEPTE.'/'.$numprojet);
} elseif ($idStatut === ACCEPTE)	{	
    $body = htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MAILCONTACTDEB1'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'", stripslashes(affiche('TXT_BODYEMAILPHASE20'))), ENT_QUOTES, 'UTF-8') . '<br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE21'))), ENT_QUOTES, 'UTF-8') .
            "<a href=".ADRESSESITE." >" . str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE22'))) . "</a>" . '<br><br>' .htmlentities(str_replace("''","'", stripslashes(affiche('TXT_BODYEMAILPHASE23'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''","'", stripslashes(affiche('TXT_BODYEMAILPHASE24'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(affiche('TXT_RESPONSABLEBODYTEMAIL8'), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MAILCONTACTFIN1'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL10'))), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . '<br>' . $emailCentrale . '<br><br>' .
            '<a href='.ADRESSESITE.'>' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>      ' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_NORESPONSE'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
    envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
    header('Location: /' . REPERTOIRE . '/accepted_project/' . $lang . '/' . $idprojet . '/' . ACCEPTE.'/'.$numprojet);
} elseif ($idStatut == REFUSE) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT PROJET REFUSEE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $body = htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MAILCONTACTDEB3'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILREFUSEPHASE20'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . $commentaire . '<br><br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILREFUSEPHASE21'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL83'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MAILCONTACTFIN'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL10'))), ENT_QUOTES, 'UTF-8') . '' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . ' <br> ' . $emailCentrale . '<br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MAILCONTACTFIN4'))), ENT_QUOTES, 'UTF-8') . "<br><br><a href=".ADRESSESITE." >" . TXT_RETOUR . '</a><br><br>    '
            . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_NORESPONSE'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
    $txtbodyref = html_entity_decode((htmlentities(affiche('TXT_BOBYREF'), ENT_QUOTES, 'UTF-8')));
    $infodemandeur = array($manager->getList2('SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur 
            AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
    $idcentrale= $manager->getSingle2("SELECT idcentrale_centrale	FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and pseudo =?",$_SESSION['pseudo']);
    if(empty($idcentrale)){        
        $idcentrale= $manager->getSingle2("SELECT idcentrale_centrale FROM utilisateuradministrateur,concerne WHERE idprojet = idprojet_projet and idprojet=?",$idprojet);
    }
    envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste  
    header('Location: /' . REPERTOIRE . '/RefusedProject/' . $lang . '/' . $idprojet . '/' . $numprojet . '/' . $idcentrale . '/' . rand(0, 100000));
}elseif ($idStatut == TRANSFERERCENTRALE) {
    $libellecentraledestination = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_POST['centrale']);
    //CENTRALE DESTINATION
    $emailcentraledestinations = array();
    $mailcentrales = $manager->getList2("select email1,email2,email3,email4,email5 from centrale where idcentrale=?", $_POST['centrale']);
    for ($i = 0; $i <= 5; $i++) {
        if (!empty($mailcentrales[0][$i])) {
            array_push($emailcentraledestinations, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
        }
    }
    //TRAITEMENT DU LIBELLE DANS L'EMAIL
    if (!empty($_POST['centrale'])) {
        $infocentraledest = $manager->getList2("select email1,email2,email3,email4,email5 from centrale where idcentrale=?", $_POST['centrale']);
        $emailCentrale = '';
        for ($i = 0; $i < count($infocentraledest); $i++) {
            for ($j = 0; $j < 4; $j++) {
                if (!empty($infocentraledest[$i]['email' . $j])) {
                    $emailCentrale .=$infocentraledest[$i]['email' . $j] . '<br>';
                }
            }
        }
    } else {
        $centrale = "";
        $emailCentrale = "";
    }
    //DEMANDEUR
    $infodemandeur = array($manager->getList2("SELECT mail,mailresponsable FROM creer,loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword
            AND  idutilisateur = idutilisateur_utilisateur and   idprojet_projet =?", $idprojet));
    $maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER
    if (!empty($infodemandeur[0][0]['mailresponsable'])) {
        array_push($arrayemailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
        $CC = $arrayemailCC;
    } else {
        $CC = $arrayemailCC;
    }
    $CC = array_merge($arrayemailCC, $emailcentraledestinations); //CENTRALE DESTINATION
    $cc = array_merge($mailCC, $CC); //CENTRALE SOURCE
    $mailCC = array_unique($cc);
    $body = utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_MAILCONTACTDEB2')))) . '<br><br>' . utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILTRSFPHASE20')))) . '<br><br>' .
            utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILTRSFPHASE21')))) . " <a href=".ADRESSESITE." >" . utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_FINSUJETMAILRESPONSABLE')))) . "</a>" .
            '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILTRSFPHASE22')))) . '<br><br>' .
            utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE241')))) . '<br><br>' . utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL82')))) . '<br><br>' . utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_MAILCONTACTFIN3')))) .
            '<br><br>' . utf8_decode(affiche('TXT_BODYEMAILTRSFPHASE23')) . ' ' .  utf8_decode($libellecentraledestination) . ' <br> ' . $emailCentrale . '<br><br>' .
            '<br><br><a href='.ADRESSESITE.'>' . utf8_decode(TXT_RETOUR) . '<a>' . '<br><br>' .
            utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_NORESPONSE'))));
    $EmailCC = array_values($mailCC); //Renumérotation des index
    envoieEmail($body, $sujet, $maildemandeur, $EmailCC); //envoie de l'email au responsable centrale et au copiste
 //   header('Location: /' . REPERTOIRE . '/transfered_project/' . $lang . '/' . $idprojet . '/' . $numprojet . '/' . $idcentrale);    
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
        "<a href=".ADRESSESITE." >" . TXT_RETOUR . '</a><br><br><br>' .        
        htmlentities(stripslashes(str_replace("''","'",affiche('TXT_NORESPONSE1'))), ENT_QUOTES, 'UTF-8');

$sujet = utf8_decode(TXT_PROJETNUM) . $numero;
envoieEmail($body, $sujet, $maildestinataire,$maildemandeur); //envoie de l'email au responsable centrale et au copiste
header('Location: /' . REPERTOIRE . '/update_project3/' . $lang. '/' . $_GET['idprojet'] . '/' . $numero. '/' . $nbpersonne);
