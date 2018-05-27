<?php

$rowProjetAttente = $manager->getListbyArray("
        SELECT p.numero,p.acronyme,p.titre,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,s.idstatutprojet,p.idprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire
        FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
        WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
          co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet AND s.idstatutprojet=? AND trashed =FALSE
          AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.libellecentrale =?", array(ENATTENTEPHASE2, $libellecentrale));
$fpProjetAttente = fopen("../tmp/ProjetEnAttenteCentrale" . IDCENTRALEUSER . ".json", 'w');
$dataProjetAttente = "";
fwrite($fpProjetAttente, '{"items": [');
$nbrowProjetAttente = count($rowProjetAttente);
for ($i = 0; $i < $nbrowProjetAttente; $i++) {
    $dataProjetAttente = "" . '{"dateProjet":' . '"' . $rowProjetAttente[$i]['dateprojet'] . '"' . ","
            . '"numero":' . '"' . $rowProjetAttente[$i]['numero'] . '"' . ","
            . '"libellecentrale":' . '"' . $rowProjetAttente[$i]['libellecentrale'] . '"' . ","
            . '"idstatutprojet":' . '"' . $rowProjetAttente[$i]['idstatutprojet'] . '"' . ","
            . '"titre":' . '"' . filtredonnee($rowProjetAttente[$i]['titre']) . '"' . ","
            . '"libellestatutProjet":' . '"' . $rowProjetAttente[$i]['libellestatutprojet'] . '"' . "," . '"nom":' . '"' . filtredonnee($rowProjetAttente[$i]['nom']) .
            '"' . "," . '"nomentreprise":' . '"' . filtredonnee($rowProjetAttente[$i]['nomentreprise']) . '"' . "," . '"entrepriselaboratoire":' . '"' . filtredonnee($rowProjetAttente[$i]['entrepriselaboratoire']) . '"'
            . "," . '"acronyme":' . '"' . $rowProjetAttente[$i]['acronyme'] . '"' . "},";
    fputs($fpProjetAttente, $dataProjetAttente);
    fwrite($fpProjetAttente, '');
}
fwrite($fpProjetAttente, ']}');
$json_fileAttenteCentrale = "../tmp/ProjetEnAttenteCentrale" . IDCENTRALEUSER . ".json";
$jsonAttenteCentrale = file_get_contents($json_fileAttenteCentrale);
$jsonAttenteCentrale1 = str_replace('},]}', '}]}', $jsonAttenteCentrale);
file_put_contents($json_fileAttenteCentrale, $jsonAttenteCentrale1);
$cache->write('attente_' . LIBELLECENTRALEUSER, $jsonAttenteCentrale1);
fclose($fpProjetAttente);
chmod("../tmp/ProjetEnAttenteCentrale" . IDCENTRALEUSER . ".json", 0777);
$_SESSION['nbprojetattente'] = $nbrowProjetAttente;
