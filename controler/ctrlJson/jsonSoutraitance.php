<?php

$rowProjetsoustraitance = $manager->getListbyArray("SELECT p.idprojet,s.idstatutprojet,p.titre,p.acronyme,p.refinterneprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom,u.prenom,p.numero,p.dateprojet FROM projet p,projetautrecentrale pa,statutprojet s,concerne co,creer cr,utilisateur u
    WHERE p.idprojet = pa.idprojet AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet AND cr.idprojet_projet = p.idprojet AND  cr.idutilisateur_utilisateur = u.idutilisateur
    and  pa.idcentrale=? AND trashed =FALSE and s.idstatutprojet <> ? and s.idstatutprojet <> ?", array($idcentrale, FINI, CLOTURE));
$fpProjetSoustraitance = fopen("../tmp/Projetsoustraitance" . IDCENTRALEUSER . ".json", 'w');
$dataProjetSoustraitance = "";
fwrite($fpProjetSoustraitance, '{"items": [');
$nbrowProjetSoustraitance = count($rowProjetsoustraitance);
for ($i = 0; $i < $nbrowProjetSoustraitance; $i++) {
    $centrale = $manager->getSingle2("SELECT  c.libellecentrale FROM centrale c,concerne co WHERE  co.idcentrale_centrale = c.idcentrale and co.idprojet_projet=?", $rowProjetsoustraitance[$i]['idprojet']);
    $dataProjetSoustraitance = ""
            . '{"dateProjet":' . '"' . $rowProjetsoustraitance[$i]['dateprojet'] . '"' . ","
            . '"numero":' . '"' . $rowProjetsoustraitance[$i]['numero'] . '"' . ","
            . '"titre":' . '"' . filtredonnee($rowProjetsoustraitance[$i]['titre']) . '"' . ","
            . '"idprojet":' . '"' . $rowProjetsoustraitance[$i]['idprojet'] . '"' . ","
            . '"libellestatutProjet":' . '"' . str_replace("''", "'", $rowProjetsoustraitance[$i]['libellestatutprojet']) . '"' . ","
            . '"idstatutprojet":' . '"' . $rowProjetsoustraitance[$i]['idstatutprojet'] . '"' . ","
            . '"refinterneprojet":' . '"' . filtredonnee($rowProjetsoustraitance[$i]['refinterneprojet']) . '"' . ","
            . '"nom":' . '"' . filtredonnee($rowProjetsoustraitance[$i]['nom']) . '"' . ","
            . '"centrale":' . '"' . $centrale . '"' . ","
            . '"prenom":' . '"' . filtredonnee($rowProjetsoustraitance[$i]['prenom']) . '"' . ","
            . '"acronyme":' . '"' . filtredonnee($rowProjetsoustraitance[$i]['acronyme']) . '"' . "},";
    fputs($fpProjetSoustraitance, $dataProjetSoustraitance);
    fwrite($fpProjetSoustraitance, '');
}
fwrite($fpProjetSoustraitance, ']}');
$json_fileSoustraitance = "../tmp/Projetsoustraitance" . IDCENTRALEUSER . ".json";
$jsonSoustraitance = file_get_contents($json_fileSoustraitance);
$jsonSoustraitance1 = str_replace('},]}', '}]}', $jsonSoustraitance);
file_put_contents($json_fileSoustraitance, $jsonSoustraitance1);
$cache->write('soustraitance_' . LIBELLECENTRALEUSER, $jsonSoustraitance1);
fclose($fpProjetSoustraitance);
chmod("../tmp/Projetsoustraitance" . IDCENTRALEUSER . ".json", 0777);


$_SESSION['nbProjetSoustraitance'] = $nbrowProjetSoustraitance;
