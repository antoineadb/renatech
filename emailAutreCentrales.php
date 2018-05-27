<?php

include_once 'class/email.php';
include_once 'outils/constantes.php';
if(isset($_GET['numProjet'])){
    $numprojet = $_GET['numProjet'];
}
$libellecentrale = array();
$sLibellecentrale = '';
$arrayid = $manager->getList2("select idcentrale from projetautrecentrale where idprojet=?", $idprojet);
for ($i = 0; $i < count($arrayid); $i++) {
    $arraylibellecentrale = $manager->getList2("SELECT libellecentrale FROM centrale WHERE idcentrale = ?", $arrayid[$i]['idcentrale']);
    if (!empty($arraylibellecentrale[0]['libellecentrale'])) {
        array_push($libellecentrale, $arraylibellecentrale[0]['libellecentrale']); //construction d'un tableau d'email            
        $sLibellecentrale.=$arraylibellecentrale[0]['libellecentrale'] . ', ';
    }
}
$slibellecentrale = substr($sLibellecentrale, 0, -2);
$emailcentrales = array();
$sEmailcentrale = '';

for ($i = 0; $i < count($arrayid); $i++) {
    $mailcentrale = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale WHERE idcentrale = ? ", $arrayid[$i]['idcentrale']);
    for ($j = 0; $j <= 5; $j++) {
        if (!empty($mailcentrale[0][$j])) {
            array_push($emailcentrales, $mailcentrale[0][$j]); //construction d'un tableau d'email            
            $sEmailcentrale.=$mailcentrale[0][$j] . '<br>';
        }
    }
}
if($mailRespCentrale!=null){
    $mailRespCentrale  =  recupMailAdminProjet($idprojet); 
}


$idstatutprojet = $manager->getSinglebyArray("select idstatutprojet_statutprojet from concerne where idprojet_projet=? and idcentrale_centrale=?",array($idprojet,IDCENTRALEUSER));
$libellestatut = $manager->getSingle2("select libellestatutprojet from statutprojet where idstatutprojet=?",$idstatutprojet);
$maildemandeur = array($manager->getSingle2('SELECT mail FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
 
//DESTINATAIRE
$maildestinataire = $emailcentrales;
$semailcentrale = '<br>' . substr($sEmailcentrale, 0, -1);
$body = utf8_decode(htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8')) . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_BODYEMAILDEANDESOUTIENT'))), ENT_QUOTES, 'UTF-8') . ': ' . $numprojet . ' ' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_BODYEMAILDEPOSECENTRALE'))), ENT_QUOTES, 'UTF-8') . ': ' .
        htmlentities($slibellecentrale, ENT_QUOTES, 'UTF-8') .' '.htmlentities(stripslashes(removeDoubleQuote( strip_tags(affiche('TXT_ETAPEAUTRE')))), ENT_QUOTES, 'UTF-8') .' <b> '.htmlentities(removeDoubleQuote($libellestatut), ENT_QUOTES, 'UTF-8') . '</b><br><br>' .
        
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_bodyEMAILCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_ADRESSEEMAILPART'))), ENT_QUOTES, 'UTF-8') .$semailcentrale . '<br><br><br><br>' .
        "<a href=".ADRESSESITE." >" . TXT_RETOUR . '</a><br><br><br>' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');
$sujet = utf8_decode(TXT_PROJETNUM) . $numprojet;

if (isset($_POST['integerspinner']) && !empty($_POST['integerspinner'])) {
    $nbpersonnecentrale = $_POST['integerspinner'];
} else {
    $nbpersonnecentrale = 0;
}
envoieEmail($body, $sujet, $maildestinataire, $maildemandeur);
