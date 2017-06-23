<?php

$row = $manager->getListbyArray("SELECT p.idprojet,s.idstatutprojet
    FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
    WHERE p.idtypeprojet_typeprojet = t.idtypeprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = p.idprojet AND
      ce.idcentrale = co.idcentrale_centrale AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet
    AND s.idstatutprojet=? and ce.libellecentrale =? AND trashed =FALSE", array(REFUSE, $libellecentrale));

$arrayIdProjet = array();
for ($i = 0; $i < count($row); $i++) {
    array_push($arrayIdProjet, $row[$i]['idprojet']);
}
$test = array();
foreach ($arrayIdProjet as $idprojet) {
    array_push($test, $manager->getSinglebyArray("select idprojet_projet from concerne where idprojet_projet=? and idcentrale_centrale!=? and idstatutprojet_statutprojet!=4", array($idprojet, IDCENTRALEUSER)));
}
$array = array_values(array_filter($test));
$arrayIdprojetTousCentrale = array_diff($arrayIdProjet, $array);

$rowProjetRefusee = array();
foreach ($arrayIdprojetTousCentrale as $idprojet) {
    array_push($rowProjetRefusee, $manager->getListbyArray("SELECT p.numero,co.commentaireprojet,p.acronyme,p.titre,p.idprojet,p.dateprojet,co.datestatutrefuser,ce.libellecentrale,s.libellestatutprojet,p.idprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire,p.refinterneprojet
    FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
    WHERE p.idtypeprojet_typeprojet = t.idtypeprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = p.idprojet AND
      ce.idcentrale = co.idcentrale_centrale AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet
    and p.idprojet=? and co.idcentrale_centrale=?", array($idprojet, IDCENTRALEUSER)));
}
$fpProjetRefusee = fopen("../tmp/ProjetRefuseecentrale" . IDCENTRALEUSER . ".json", 'w');
$dataProjetRefusee = "";
fwrite($fpProjetRefusee, '{"items": [');
$nbrowProjetRefusee = count($rowProjetRefusee);
for ($i = 0; $i < $nbrowProjetRefusee; $i++) {
    if (!empty($rowProjetRefusee[$i][0]['numero'])) {
        $dataProjetRefusee = "" . '{"dateProjet":' . '"' . $rowProjetRefusee[$i][0]['dateprojet'] . '"' . ","
                . '"datestatutrefuser":' . '"' . $rowProjetRefusee[$i][0]['datestatutrefuser'] . '"' . ","
                . '"libellecentrale":' . '"' . $rowProjetRefusee[$i][0]['libellecentrale'] . '"' . ","
                . '"numero":' . '"' . $rowProjetRefusee[$i][0]['numero'] . '"'
                . "," . '"refinterneprojet":' . '"' . filtredonnee($rowProjetRefusee[$i][0]['refinterneprojet']) . '"'
                . "," . '"titre":' . '"' . filtredonnee($rowProjetRefusee[$i][0]['titre']) . '"'
                . "," . '"nom":' . '"' . filtredonnee($rowProjetRefusee[$i][0]['nom']) . '"'
                . "," . '"commentaire":' . '"' . strip_tags(filtredonnee($rowProjetRefusee[$i][0]['commentaireprojet'])) . '"'
                . "," . '"nomentreprise":' . '"' . filtredonnee($rowProjetRefusee[$i][0]['nomentreprise']) . '"'
                . "," . '"entrepriselaboratoire":' . '"' . filtredonnee($rowProjetRefusee[$i][0]['entrepriselaboratoire']) . '"'
                . "," . '"acronyme":' . '"' . $rowProjetRefusee[$i][0]['acronyme'] . '"' . "},";
    }
    fputs($fpProjetRefusee, $dataProjetRefusee);
    fwrite($fpProjetRefusee, '');
}
fwrite($fpProjetRefusee, ']}');
$json_fileRefuseeCentrale = "../tmp/ProjetRefuseecentrale" . IDCENTRALEUSER . ".json";
$jsonRefuseeCentrale = file_get_contents($json_fileRefuseeCentrale);
$jsonRefuseeCentrale1 = str_replace('},]}', '}]}', $jsonRefuseeCentrale);
file_put_contents($json_fileRefuseeCentrale, $jsonRefuseeCentrale1);
$cache->write('refuse_' . LIBELLECENTRALEUSER, $jsonRefuseeCentrale1);
fclose($fpProjetRefusee);
chmod("../tmp/ProjetRefuseecentrale" . IDCENTRALEUSER . ".json", 0777);
$_SESSION['nbProjetRefusee'] = $nbrowProjetRefusee;
