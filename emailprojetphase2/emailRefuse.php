<?php
include_once '../class/Manager.php';
include_once '../class/email.php';
$db = BD::connecter();
$manager = new Manager($db);
if(isset($_POST['commentaireRefusValeur'])){  
 $commentaire  = utf8_decode($_POST['commentaireRefusValeur']);
}elseif(isset($_POST['comment'])){  
 $commentaire  =  utf8_decode($_POST['comment']);
}else{
    $commentaire  =   "";
}

if(isset($_GET['idprojet'])){
    $idprojet=$_GET['idprojet'];
    $numprojet =$manager->getSingle2("select numero from projet where idprojet=?",$idprojet);
}elseif (isset ($_GET['numProjet'])) {
    $numprojet = $_GET['numProjet'];
    $idprojet = $manager->getSingle2("select idprojet from projet where idprojet=?",$numprojet);
}

$idcentrale = $manager->getSingle2("select idcentrale_centrale from concerne where idprojet_projet=?", $idprojet);

$arrayCentrale=$manager->getListbyArray("select libellecentrale,email1 from centrale,concerne where idcentrale_centrale = idcentrale and idprojet_projet=? and idcentrale_centrale=?",array($idprojet,$idcentrale));
$centrale = $arrayCentrale[0]['libellecentrale'];
$emailCentrale = $arrayCentrale[0]['email1'];

$body = htmlentities(removeDoubleQuote(stripslashes(affiche('TXT_MRSMR8'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(removeDoubleQuote( stripslashes(affiche('TXT_BODYEMAILREFUSEPHASE20'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . $commentaire . '<br><br>' .
        htmlentities(removeDoubleQuote(stripslashes(affiche('TXT_BODYEMAILREFUSEPHASE21'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(removeDoubleQuote( stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(removeDoubleQuote( stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(removeDoubleQuote( stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL10'))), ENT_QUOTES, 'UTF-8') . '' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . ' <br> ' . $emailCentrale . '<br>' .
        htmlentities(removeDoubleQuote( stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . "<br><br><a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br>    '
        . htmlentities(removeDoubleQuote( stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';

$txtbodyref =  removeDoubleQuote(utf8_decode(affiche('TXT_BOBYREF')));
$titreProjet = $manager->getSingle("select titre from projet where numero='" . $numprojet . "'");
$titre = removeDoubleQuote(utf8_decode($titreProjet));
$sujet = html_entity_decode(TXT_MAJPROJET,ENT_QUOTES, 'UTF-8').' : '  . $titre . ' ' . $txtbodyref . ' ' . $numprojet;

$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale	FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
if (empty($idcentrale)) {
    $idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM utilisateuradministrateur,concerne WHERE idprojet = idprojet_projet and idprojet=?", $idprojet);
}
$infodemandeur = array($manager->getList2('SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur  AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
$maildemandeur = array($infodemandeur[0][0]['mail']);
$arrayemailCC = array();
if (!empty($infodemandeur[0][0]['mailresponsable'])) {
    array_push($arrayemailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
    $CC = array_merge($arrayemailCC, $arrayemailCC);
} else {
    $CC = $arrayemailCC;
}
$emailcentrales = array();
$mailcentrales = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale,loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword
            AND idcentrale_centrale = idcentrale AND pseudo=? ", $_SESSION['pseudo']);
if (!empty($mailcentrales)) {
    for ($i = 0; $i <= 5; $i++) {
        if (!empty($mailcentrales[0][$i])) {
            array_push($emailcentrales, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
        }
    }
}else{
    $mailcentrales = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?", $idprojet);
    for ($i = 0; $i <= 5; $i++) {
        if (!empty($mailcentrales[0][$i])) {
            array_push($emailcentrales, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
        }
    }
}

$emailcc = array_merge($emailcentrales, $CC);
$mailCC = array_unique($emailcc);

$nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $idprojet);
$sMailCc='';
foreach ($mailCC as $email) {
    $sMailCc.=$email[0].',';
}
$sMailCC = substr($sMailCc,0,-1);
createLogInfo(NOW, 'Projet refusé par la centrale '.$centrale.' : E-mail demandeur: ' .$infodemandeur[0][0]['mail'].' : '.' copie E-mail à  : ' .$sMailCC.' : n°: '. $numprojet, 'Demandeur: '.$nomPrenomDemandeur[0]['nom'] .
        ' ' .$nomPrenomDemandeur[0]['prenom'] , TXT_REFUSE, $manager,$idcentrale);

envoieEmail($body, $sujet, $maildemandeur, $emailcc); //envoie de l'email au responsable centrale et au copiste  
header('Location: /' . REPERTOIRE . '/RefusedProject/' . $lang . '/' . $idprojet . '/' . $numprojet . '/' . $idcentrale . '/' . rand(0, 100000));
exit();
BD::deconnecter();