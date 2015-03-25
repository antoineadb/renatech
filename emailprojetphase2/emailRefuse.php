<?php

$body = htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILREFUSEPHASE20'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . $commentaire . '<br><br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILREFUSEPHASE21'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL10'))), ENT_QUOTES, 'UTF-8') . '' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . ' <br> ' . $emailCentrale . '<br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . "<br><br><a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br>    '
        . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
$txtbodyref = html_entity_decode((htmlentities(affiche('TXT_BOBYREF'), ENT_QUOTES, 'UTF-8')));
$infodemandeur = array($manager->getList2('SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur 
            AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale	FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
if (empty($idcentrale)) {
    $idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM utilisateuradministrateur,concerne WHERE idprojet = idprojet_projet and idprojet=?", $idprojet);
}
envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste  
header('Location: /' . REPERTOIRE . '/RefusedProject/' . $lang . '/' . $idprojet . '/' . $numprojet . '/' . $idcentrale . '/' . rand(0, 100000));
exit();
