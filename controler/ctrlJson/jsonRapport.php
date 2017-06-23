<?php

$rowProjetRapport = $manager->getListbyArray("select c.idstatutprojet_statutprojet,r.idprojet,cr.idutilisateur_utilisateur,p.numero,r.title, r.datecreation,r.datemiseajour,p.refinterneprojet "
        . "from projet p,rapport r, concerne c,creer cr where p.idprojet=r.idprojet and c.idprojet_projet=r.idprojet and cr.idprojet_projet=r.idprojet and c.idcentrale_centrale=? "
        . "and c.idstatutprojet_statutprojet!=?", array($idcentrale, REFUSE));
$fpProjetRapport = fopen("../tmp/ProjetRapport" . IDCENTRALEUSER . ".json", 'w');
$dataProjetRapport = "";
fwrite($fpProjetRapport, '{"items": [');

$nbrowProjetRapport = count($rowProjetRapport);
for ($i = 0; $i < $nbrowProjetRapport; $i++) {
    $idutilisateur = $rowProjetRapport[$i]['idutilisateur_utilisateur'];
    $arraycreateur = $manager->getList2("select nom,prenom from utilisateur where idutilisateur=?", $idutilisateur);
    $dataProjetRapport = ""
            . '{"numero":' . '"' . $rowProjetRapport[$i]['numero'] . '"' . ","
            . '"datecreation":' . '"' . $rowProjetRapport[$i]['datecreation'] . '"' . ","
            . '"identite":' . '"' . $arraycreateur[0]['nom'] . ' - ' . $arraycreateur[0]['prenom'] . '"' . ","
            . '"datemiseajour":' . '"' . $rowProjetRapport[$i]['datemiseajour'] . '"' . ","
            . '"title":' . '"' . filtredonnee($rowProjetRapport[$i]['title']) . '"' . ","
            . '"idprojet":' . '"' . $rowProjetRapport[$i]['idprojet'] . '"' . ","
            . '"idstatutprojet":' . '"' . $rowProjetRapport[$i]['idstatutprojet_statutprojet'] . '"' . ","
            . '"refinterneprojet":' . '"' . filtredonnee($rowProjetRapport[$i]['refinterneprojet']) . '"' . ","
            . '"imprime":' . '"' . TXT_PDF . '"' . "},";
    fputs($fpProjetRapport, $dataProjetRapport);
    fwrite($fpProjetRapport, '');
}

fwrite($fpProjetRapport, ']}');
$json_fileRapport = "../tmp/ProjetRapport" . IDCENTRALEUSER . ".json";

$jsonRapport = file_get_contents($json_fileRapport);


$jsonRapport1 = str_replace('},]}', '}]}', $jsonRapport);
file_put_contents($json_fileRapport, $jsonRapport1);
$cache->write('rapport_' . LIBELLECENTRALEUSER, $jsonRapport1);
fclose($fpProjetRapport);
chmod("../tmp/ProjetRapport" . IDCENTRALEUSER . ".json", 0777);
$_SESSION['nbProjetRapport'] = $nbrowProjetRapport;
