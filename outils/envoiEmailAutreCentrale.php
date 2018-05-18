<?php 
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter();
$manager = new Manager($db);
include_once '../class/email.php';
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
    $body = (htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8')) . '<br><br>' .
            htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_BODYEMAILDEANDESOUTIENT'))), ENT_QUOTES, 'UTF-8') . ': ' . $numprojet . ' ' .
            htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_BODYEMAILDEPOSECENTRALE'))), ENT_QUOTES, 'UTF-8') . ': ' .
            htmlentities($centraleaccueil, ENT_QUOTES, 'UTF-8') . ', ' .
            htmlentities(stripslashes(removeDoubleQuote((affiche('TXT_ETAPE')))), ENT_QUOTES, 'UTF-8').'<br><br>'.
            htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_BODYEMAILDESCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br>' .
            htmlentities(removeDoubleQuote($descriptionautrecentrale),ENT_QUOTES,'UTF-8'). '<br><br><br>' .
            htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_bodyEMAILCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_ADRESSEEMAILPART'))), ENT_QUOTES, 'UTF-8') . ' ' . $slibellecentrale . '<br>' .
            $semailcentrale . '<br><br><br><br>' .
            "<a href=".ADRESSESITE." >" . TXT_RETOUR . '</a><br><br><br>' .
            htmlentities(stripslashes(removeDoubleQuote(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');
    $sujet = utf8_decode(TXT_PROJETNUM) . $numprojet;

    if(!empty($maildestinataire)){
        envoieEmail($body, $sujet, $maildestinataire, $mailCC);
    }else{
         createLogInfo(NOW, "Erreur sur l'envoi de l'E-mail au destinataire, E-mail absent ou invalide",'','','','');
    }
    $db = BD::deconnecter();

