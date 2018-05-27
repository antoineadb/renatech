<?php

$body = htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_MRSMR9'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILENATTENTEPHASE01'))), ENT_QUOTES, 'UTF-8') . '<br>' . $commentaire . '<br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILENATTENTEPHASE02'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . ' <br> ' . $emailCentrale . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
$nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $idprojet);
$sMailCc = '';
foreach ($mailCC as $email) {
    $sMailCc.=$email[0] . ',';
}
$sMailCC = substr($sMailCc, 0, -1);
$idcentrales = $manager->getList2("select idcentrale_centrale from concerne where idprojet_projet=?", $idprojet);
foreach ($idcentrales as $idcentrale) {
    createLogInfo(NOW, 'Projet remis en attente dans la centrale ' . $centrale . ' : E-mail demandeur: ' . $infodemandeur[0][0]['mail'] . ' : ' . ' copie E-mail à  : ' . $sMailCC . ' : n°: ' . $numprojet, 'Demandeur: ' . $nomPrenomDemandeur[0]['nom'] .
            ' ' . $nomPrenomDemandeur[0]['prenom'], TXT_ENATTENTE, $manager, $idcentrale[0]);
}
header('Location: /' . REPERTOIRE . '/Waiting_project1/' . $lang . '/' . $idprojet . '/' . $numprojet . '/' . ENATTENTEPHASE2);
exit();
