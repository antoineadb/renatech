<?php 
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
include_once 'class/email.php';
$arrayid = $manager->getList2("select idcentrale from projetautrecentrale where idprojet=?", $idprojet);
if(empty($numprojet)){
    $numprojet = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
}
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
//DESTINATAIRE
$maildestinataire = $emailcentrales;
$semailcentrale = '<br>' . substr($sEmailcentrale, 0, -1);
//COPISTES
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

$libellecentrale = array();
$sLibellecentrale = '';
for ($i = 0; $i < count($arrayid); $i++) {
    $arraylibellecentrale = $manager->getList2("SELECT libellecentrale FROM centrale WHERE idcentrale = ?", $arrayid[$i]['idcentrale']);
    if (!empty($arraylibellecentrale[0]['libellecentrale'])) {
        array_push($libellecentrale, $arraylibellecentrale[0]['libellecentrale']); //construction d'un tableau d'email            
        $sLibellecentrale.=$arraylibellecentrale[0]['libellecentrale'] . ', ';
        //MISE A TRUE DU FLAG sendmail DANS LA TABLE PROJETAUTRECENTRALE
        $projetautrecentrale = new Projetautrecentrale($arrayid[$i]['idcentrale'], $idprojet, TRUE);
        $manager->updateProjetAutresCentraleEmail($projetautrecentrale, $idprojet, $arrayid[$i]['idcentrale']);
    }
}
if(empty($descriptionautrecentrale)){
    $descriptionautrecentrale=  removeDoubleQuote($manager->getSingle2("select descriptionautrecentrale from projet where idprojet=?",$idprojet));
}
$slibellecentrale = substr($sLibellecentrale, 0, -2);
$centraleaccueil = $manager->getSingle2("select libellecentrale from concerne,centrale where idcentrale_centrale=idcentrale and idprojet_projet=?", $idprojet);
$body = utf8_decode(htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8')) . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_BODYEMAILDEANDESOUTIENT'))), ENT_QUOTES, 'UTF-8') . ': ' . $numprojet . ' ' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_BODYEMAILDEPOSECENTRALE'))), ENT_QUOTES, 'UTF-8') . ': ' .
        htmlentities($slibellecentrale, ENT_QUOTES, 'UTF-8') .' '.htmlentities(stripslashes(removeDoubleQuote( strip_tags(affiche('TXT_ETAPEAUTREMAJ')))), ENT_QUOTES, 'UTF-8') .'<br><br>' .
        
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_bodyEMAILCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_ADRESSEEMAILPART'))), ENT_QUOTES, 'UTF-8') .$semailcentrale . '<br><br><br><br>' .
        "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br><br>' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');
$sujet = utf8_decode(TXT_PROJETNUM) . $numprojet;
envoieEmail($body, $sujet, $maildestinataire, $mailCC);
$db = BD::deconnecter();