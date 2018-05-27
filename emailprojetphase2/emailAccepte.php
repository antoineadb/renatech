<?php
include_once '../outils/constantes.php';
$body = htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . (htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE200'))), ENT_QUOTES, 'UTF-8')) . '<br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE221'))), ENT_QUOTES, 'UTF-8') . ' ' .
        "<a href=".ADRESSESITE." >" . affiche('TXT_BODYEMAILPHASE220') . "</a>" . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE230'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RAPPELINSERTLOGO0'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(affiche('TXT_RESPONSABLEBODYTEMAIL10'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . '<br>' . $emailCentrale . '<br><br>' .
        '<a href='.ADRESSESITE.'>' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>      ' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
$sMailCc = '';
for ($i = 0; $i < count($mailCC); $i++) {
    $sMailCc.=$mailCC[$i] . ',';
}
$sMailCC = substr($sMailCc, 0, -1);
$idcentrale = $manager->getSingle2("select idcentrale_centrale from concerne where idprojet_projet=?", $idprojet);
$nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $idprojet);
createLogInfo(NOW, 'Projet accepté par la centrale ' . $centrale . ' : E-mail demandeur: ' . $maildemandeur[0][0]['mail'] . ' : ' . ' copie E-mail à  : ' . $sMailCC . ' : n°: ' . $numprojet, 'Demandeur: ' . $nomPrenomDemandeur[0]['nom'] .
        ' ' . $nomPrenomDemandeur[0]['prenom'], removeDoubleQuote(TXT_ACCEPTE), $manager,$idcentrale);
envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste 
