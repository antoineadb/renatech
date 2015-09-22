<?php

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
if(isset($_GET['etautrecentrale'])){
    $descriptionautrecentrale = $_GET['etautrecentrale'];
}else{
    $descriptionautrecentrale = '';
}
//DESTINATAIRE
$maildestinataire = $emailcentrales;
$semailcentrale = '<br>' . substr($sEmailcentrale, 0, -1);
$body = utf8_decode(htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8')) . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDEANDESOUTIENT'))), ENT_QUOTES, 'UTF-8') . ': ' . $numprojet . ' ' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDEPOSECENTRALE'))), ENT_QUOTES, 'UTF-8') . ': ' .
        htmlentities($slibellecentrale, ENT_QUOTES, 'UTF-8') . ', ' . htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_ETAPETECHNO')))) . ' ' .
        htmlentities(stripslashes(str_replace("''", "'", strip_tags(affiche('TXT_ETAPE')))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDESCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br>' .
        htmlentities(stripslashes(str_replace("''", "'", strip_tags($descriptionautrecentrale))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_bodyEMAILCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_ADRESSEEMAILPART'))), ENT_QUOTES, 'UTF-8') .
        $semailcentrale . '<br><br><br><br>' .
        "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br><br>' .
        htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');
$sujet = utf8_decode(TXT_PROJETNUM) . $numprojet;
if (isset($_POST['integerspinner']) && !empty($_POST['integerspinner'])) {
    $nbpersonnecentrale = $_POST['integerspinner'];
} else {
    $nbpersonnecentrale = 0;
}
envoieEmail($body, $sujet, $maildestinataire, $maildemandeur);
header('Location: /' . REPERTOIRE . '/accepted_project/' . $lang . '/' . $idprojet . '/' . ACCEPTE . '/' . $numprojet);
exit();
