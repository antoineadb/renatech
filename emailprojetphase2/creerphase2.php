<?php
include_once '../class/email.php';
include_once '../emailprojetphase2/emailCentrale.php';
if(empty($numprojet)){
    $numprojet=$_GET['numProjet'];
}
if(!isset($txtbodyref)){
    $txtbodyref = utf8_decode(affiche('TXT_BOBYREF'));
}

if(!isset($_POST['creerprojetphase2'])){
    $titre = utf8_decode($titre);
}
$bodyref = utf8_decode((affiche('TXT_BOBYREF')));
$sujet = TXT_DEMANDEPROJET . ' : ' . $titre . ' ' . $txtbodyref . ' ' . $numprojet;
$centraleaccueil = $manager->getSingle2("select libellecentrale from concerne,centrale where idcentrale_centrale=idcentrale and idprojet_projet=?", $idprojet);
$body = affiche('TXT_BODYEMAILPROJET0') . '<br><br>' . htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_DEMANDEPROJETPHASE2'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_BODYEMAILPROJET2'))), ENT_QUOTES, 'UTF-8') .
        '<br><br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_BODYEMAILPROJET3')))) .
        '<br><br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_INFOQUESTION')))) .
        '<br><br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_SINCERESALUTATION')))) .
        '<br><br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_RESEAURENATECH')))) .
        '<br><br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_BODYEMAILPROJET4')))) . ' ' . $centraleaccueil . '<br>' . $emailCentrale . '<br>' .
        "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br>    ' .
        '<br><br>' . utf8_decode(stripslashes(removeDoubleQuote( affiche('TXT_DONOTREPLY'))));
envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste

if (!empty($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {//EMAILPROJETPHASE2 AVEC UNE AUTRE CENTRALE  
    include '../outils/envoiEmailAutreCentrale.php';
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nbpersonnecentrale . '/' . $idcentrale);
}