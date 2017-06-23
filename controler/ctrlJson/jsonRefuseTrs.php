<?php

$row = $manager->getListbyArray("SELECT p.numero,co.commentaireprojet,p.titre,p.idprojet,co.datestatutrefuser,ce.libellecentrale,s.libellestatutprojet,p.idprojet,u.nom,
                u.nomentreprise,u.entrepriselaboratoire,p.refinterneprojet
                FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
                WHERE p.idtypeprojet_typeprojet = t.idtypeprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = p.idprojet AND
                ce.idcentrale = co.idcentrale_centrale AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet
                AND s.idstatutprojet=? and ce.libellecentrale =? and co.commentaireprojet like ?", array(REFUSE, $libellecentrale, "%Transféré dans la centrale%"));

$fpProjetRefuseTransfert = fopen("../tmp/ProjetRefuseTransfert" . IDCENTRALEUSER . ".json", 'w');
$dataProjetRefuseTransfert = "";
fwrite($fpProjetRefuseTransfert, '{"items": [');
$nbrowProjetRefuseTransfert = count($row);
for ($i = 0; $i < $nbrowProjetRefuseTransfert; $i++) {
    $centraleAcceptation = $manager->getSinglebyArray("SELECT libellecentrale FROM concerne LEFT JOIN centrale on idcentrale= idcentrale_centrale where idprojet_projet=? "
            . "and idstatutprojet_statutprojet<>?", array($row[$i]['idprojet'], REFUSE));
    $s_Centrales = '';
    $centraleRefus = $manager->getListbyArray("SELECT commentaireprojet FROM concerne where idprojet_projet=? and idstatutprojet_statutprojet=?", array($row[$i]['idprojet'], REFUSE));
    foreach ($centraleRefus as $key => $value) {
        $s_Centrales .= strip_tags($value[0]) . ",";
    }
    $sCentrale = substr($s_Centrales, 0, -1);

    if (!empty($row[$i]['numero'])) {
        $dataProjetRefuseTransfert = "" . '{"datestatutrefuser":' . '"' . $row[$i]['datestatutrefuser'] . '"' . ","
                . '"libellecentrale":' . '"' . $row[$i]['libellecentrale'] . '"' . ","
                . '"numero":' . '"' . $row[$i]['numero'] . '"'
                . "," . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"'
                . "," . '"nom":' . '"' . filtredonnee($row[$i]['nom']) . '"'
                . "," . '"centraleAcceptation":' . '"' . $centraleAcceptation . '"'
                . "," . '"centraleRefus":' . '"' . $sCentrale . '"'
                . "," . '"commentaire":' . '"' . strip_tags(filtredonnee($row[$i]['commentaireprojet'])) . '"'
                . "},";
    }
    fputs($fpProjetRefuseTransfert, $dataProjetRefuseTransfert);
    fwrite($fpProjetRefuseTransfert, '');
}
fwrite($fpProjetRefuseTransfert, ']}');
$json_fileRefuseTransfert = "../tmp/ProjetRefuseTransfert" . IDCENTRALEUSER . ".json";
$jsonRefuseTransfertCentrale = file_get_contents($json_fileRefuseTransfert);
$jsonRefuseTransfertCentrale1 = str_replace('},]}', '}]}', $jsonRefuseTransfertCentrale);
file_put_contents($json_fileRefuseTransfert, $jsonRefuseTransfertCentrale1);
$cache->write('refuse_' . LIBELLECENTRALEUSER, $jsonRefuseTransfertCentrale1);
fclose($fpProjetRefuseTransfert);
chmod("../tmp/ProjetRefuseTransfert" . IDCENTRALEUSER . ".json", 0777);
$_SESSION['nbProjetRefuseTransfert'] = $nbrowProjetRefuseTransfert;
