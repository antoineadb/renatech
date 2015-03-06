<?php

$arrayid = $manager->getList2("select idcentrale from projetautrecentrale where idprojet=?", $idprojet);
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

$libellecentrale = array();
$sLibellecentrale = '';
for ($i = 0; $i < count($arrayid); $i++) {
    $arraylibellecentrale = $manager->getList2("SELECT libellecentrale FROM centrale WHERE idcentrale = ?", $arrayid[$i]['idcentrale']);
    if (!empty($arraylibellecentrale[0]['libellecentrale'])) {
        array_push($libellecentrale, $arraylibellecentrale[0]['libellecentrale']); //construction d'un tableau d'email            
        $sLibellecentrale.=$arraylibellecentrale[0]['libellecentrale'] . ', ';
    }
}
if(empty($descriptionautrecentrale)){
    $descriptionAutrecentrale=  stripslashes(str_replace("''", "'", $manager->getSingle2("select descriptionautrecentrale from projet where idprojet=?",$idprojet))) ;
    $descriptionautrecentrale = htmlentities(removeBrEmail($descriptionAutrecentrale), ENT_QUOTES, 'UTF-8').'<br>';
}
$slibellecentrale = substr($sLibellecentrale, 0, -2);
$centraleaccueil = $manager->getSingle2("select libellecentrale from concerne,centrale where idcentrale_centrale=idcentrale and idprojet_projet=?", $idprojet);
$body = utf8_decode(htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8')) . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDEANDESOUTIENT'))), ENT_QUOTES, 'UTF-8') . ': ' . $numero . ' ' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDEPOSECENTRALE'))), ENT_QUOTES, 'UTF-8') . ': ' .
        htmlentities($centraleaccueil, ENT_QUOTES, 'UTF-8') . ', ' .
        htmlentities(stripslashes(str_replace("''", "'", strip_tags(affiche('TXT_ETAPE')))), ENT_QUOTES, 'UTF-8').'<br><br>'.
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDESCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br>' .
        stripslashes(str_replace("''", "'", $descriptionautrecentrale)). '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_bodyEMAILCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_ADRESSEEMAILPART'))), ENT_QUOTES, 'UTF-8') . ' ' . $slibellecentrale . '<br>' .
        $semailcentrale . '<br><br><br><br>' .
        "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');
$sujet = utf8_decode(TXT_PROJETNUM) . $numero;
envoieEmail($body, $sujet, $maildestinataire, $mailCC);