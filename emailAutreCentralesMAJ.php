<?php 
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
include_once 'class/email.php';
include_once 'outils/constantes.php';;
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
        "<a href=".ADRESSESITE." >" . TXT_RETOUR . '</a><br><br><br>' .
        htmlentities(stripslashes(removeDoubleQuote( affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');
$sujet = utf8_decode(TXT_PROJETNUM) . $numprojet;

$sMailCc='';
for ($i = 0;$i < count($mailCC);$i++) {
    $sMailCc.=$mailCC[$i].',';
}
$statut =  removeDoubleQuote($manager->getSingle2("SELECT libellestatutprojet FROM concerne,statutprojet WHERE idstatutprojet = idstatutprojet_statutprojet and idprojet_projet = ?", $idprojet));
$sMailCC = substr($sMailCc,0,-1);
$centrale = $manager->getSingle2("SELECT libellecentrale FROM concerne,centrale WHERE idcentrale = idcentrale_centrale and idprojet_projet=?",$idprojet);
$nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $idprojet);
$idcentrales = $manager->getList2("select idcentrale_centrale from concerne where idprojet_projet=?", $idprojet);
foreach ($idcentrales as $idcentrale) {
createLogInfo(NOW, 'Projet mise à jour, centrale '.$centrale.' : E-mail demandeur: ' .$sEmailcentrale.' : '.' copie E-mail à  : ' .$sMailCC.' : n°: '. $numprojet, 'Demandeur: '.$nomPrenomDemandeur[0]['nom'] .
       ' ' .$nomPrenomDemandeur[0]['prenom'] , $statut, $manager,$idcentrale[0]);
}
envoieEmail($body, $sujet, $maildestinataire, $mailCC);
$db = BD::deconnecter();