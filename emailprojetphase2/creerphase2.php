<?php

if(is_file('../class/email.php')){
    include_once '../class/email.php';
}elseif (is_file('class/email.php')) {
    include_once 'class/email.php';
}
if(is_file('../outils/constantes.php')){
    include_once '../outils/constantes.php';
}elseif (is_file('outils/constantes.php')) {
    include_once 'outils/constantes.php';
}

if(is_file('../emailprojetphase2/emailCentrale.php')){//INFORMATION QUI SERT A COMPLETER L'EMAIL
    include_once '../emailprojetphase2/emailCentrale.php';
}elseif (is_file('emailprojetphase2/emailCentrale.php')) {
    include_once 'emailprojetphase2/emailCentrale.php';
}

if(empty($numprojet)){
    $numprojet=$_GET['numProjet'];
}
if(!isset($txtbodyref)){
    $txtbodyref = utf8_decode(affiche('TXT_BOBYREF'));
}

/**
 * 
 * Votre demande de projet par la ou les plateforme(s) technologique(s) que vous avez sélectionnée(s) dans le cadre du réseau RENATECH a été prise en compte.
    Sincères salutations,
    Le réseau Renatech.
    
 *  FEMTO-ST
    antoineadb@gmail.com
    Retour sur la plateforme Renatech
    Merci de ne pas répondre à cette adresse.
 */

$titre = utf8_decode(removeDoubleQuote($titre));
$sujet = TXT_DEMANDEPROJET . ' : ' . $titre . ' ' . $txtbodyref . ' ' . $numprojet;
$centraleaccueil = $manager->getSingle2("select libellecentrale from concerne,centrale where idcentrale_centrale=idcentrale and idprojet_projet=?", $idprojet);
$body = affiche('TXT_MRSMR6') .'<br>'.affiche('TXT_BODYEMAILPROJET0') . '<br>' . htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_DEMANDEPROJETPHASE2'))), ENT_QUOTES, 'UTF-8') .
        '<br>' . htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_BODYEMAILPROJET2'))), ENT_QUOTES, 'UTF-8') .
        '<br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_BODYEMAILPROJET3')))) .        
        '<br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_SINCERESALUTATION')))) .
        '<br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_RESEAURENATECH')))) .
        '<br>' . $centraleaccueil . '<br>' . $emailCentrale . '<br>' .
        "<a href=".ADRESSESITE." >" . TXT_RETOUR . '</a><br>    ' .
        '<br><br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_DONOTREPLY'))));

$sMailCc='';
for ($i = 0;$i < count($mailCC);$i++) {
    $sMailCc.=$mailCC[$i].',';
}



$sMailCC = substr($sMailCc,0,-1);
$centrale = $manager->getSingle2("SELECT libellecentrale FROM concerne,centrale WHERE idcentrale = idcentrale_centrale and idprojet_projet=?",$idprojet);
$nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $idprojet);
$idcentrale = $manager->getSingle2("select idcentrale_centrale from concerne where idprojet_projet=?", $idprojet);
createLogInfo(NOW, 'Projet soumis dans la centrale '.$centrale.' : E-mail demandeur: ' .$maildemandeur[0].' : '.' E-mail copie : ' .$sMailCC.' : n°: '. $numprojet, 'Demandeur: '.$nomPrenomDemandeur[0]['nom'] .
        ' ' .$nomPrenomDemandeur[0]['prenom'] , TXT_ACCEPTE, $manager,$idcentrale);

envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste

if (!empty($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {//EMAILPROJETPHASE2 AVEC UNE AUTRE CENTRALE
    if(is_file('../outils/envoiEmailAutreCentrale.php')){
        include '../outils/envoiEmailAutreCentrale.php';
    }elseif (is_file('outils/envoiEmailAutreCentrale.php')) {
        include 'envoiEmailAutreCentrale.php';
    }
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nbpersonnecentrale . '/' . $idcentrale);
}