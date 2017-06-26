<?php

$manager->exeRequete("drop table if exists tmpcloturer;");
//CREATION DE LA TABLE TEMPORAIRE
$manager->getRequete("create table tmpcloturer as
    (SELECT p.idprojet,p.acronyme,s.libellestatutprojet,p.numero,p.titre,p.datedebutprojet,p.datestatutcloturer,u.nom,u.nomentreprise,u.entrepriselaboratoire,null as nomAffecte,null as prenomAffecte,ce.libellecentrale,p.refinterneprojet
      FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
      WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
      co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet
      AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.libellecentrale = ? and s.idstatutprojet=? AND trashed =FALSE
    union
    SELECT idprojet,acronyme,s.libellestatutprojet,numero,titre,p.datedebutprojet,p.datestatutcloturer,u.nom,u.nomentreprise,u.entrepriselaboratoire,u1.nom,u1.prenom,ce.libellecentrale,p.refinterneprojet
    FROM projet p,utilisateur u,creer,centrale ce,concerne,typeprojet,statutprojet s,utilisateurporteurprojet,utilisateur u1
    WHERE idtypeprojet_typeprojet = typeprojet.idtypeprojet AND u.idutilisateur = creer.idutilisateur_utilisateur AND
      creer.idprojet_projet = idprojet AND concerne.idcentrale_centrale = idcentrale AND concerne.idprojet_projet = idprojet AND
      concerne.idstatutprojet_statutprojet = s.idstatutprojet AND utilisateurporteurprojet.idprojet_projet = idprojet AND
      utilisateurporteurprojet.idutilisateur_utilisateur = u1.idutilisateur AND ce.libellecentrale =? and s.idstatutprojet=? AND trashed =FALSE
    order by idprojet asc);", array($libellecentrale, CLOTURE, $libellecentrale, CLOTURE));
$rowCloturer = $manager->getList("
        select  idprojet,acronyme,libellestatutprojet,numero,titre,datedebutprojet,datestatutcloturer,nom,nomentreprise,entrepriselaboratoire,nomaffecte,prenomaffecte,libellecentrale,refinterneprojet from tmpcloturer
        where idprojet not in (select idprojet from tmpcloturer where nomaffecte is not null)
        union
        select idprojet,acronyme,libellestatutprojet,numero,titre,datedebutprojet,datestatutcloturer,nom,nomentreprise,entrepriselaboratoire,nomaffecte,prenomaffecte,libellecentrale,refinterneprojet from tmpcloturer where nomaffecte is not null
        order by idprojet asc");
$fpCloturer = fopen("../tmp/projetCloturerCentrale" . IDCENTRALEUSER . ".json", 'w');
$dataCloturer = "";
fwrite($fpCloturer, '{"items": [');
$nbrowCloturer = count($rowCloturer);
for ($i = 0; $i < $nbrowCloturer; $i++) {
    $dataCloturer = "" . '{"datedebutprojet":' . '"' . $rowCloturer[$i]['datedebutprojet'] . '"' . ","
            . '"datestatutcloturer":' . '"' . $rowCloturer[$i]['datestatutcloturer'] . '"' . ","
            . '"libellecentrale":' . '"' . $rowCloturer[$i]['libellecentrale'] . '"' . ","
            . '"numero":' . '"' . $rowCloturer[$i]['numero'] . '"' . ","
            . '"refinterneprojet":' . '"' . filtredonnee($rowCloturer[$i]['refinterneprojet']) . '"' . ","
            . '"titre":' . '"' . filtredonnee($rowCloturer[$i]['titre']) . '"' . ","
            . '"libellestatutprojet":' . '"' . $rowCloturer[$i]['libellestatutprojet'] . '"' . ","
            . '"acronyme":' . '"' . $rowCloturer[$i]['acronyme'] . '"' . "},";
    fputs($fpCloturer, $dataCloturer);
    fwrite($fpCloturer, '');
}
fwrite($fpCloturer, ']}');
$json_fileCloturer = "../tmp/projetCloturerCentrale" . IDCENTRALEUSER . ".json";
$jsoncloturer = file_get_contents($json_fileCloturer);
$jsonCloturer = str_replace('},]}', '}]}', $jsoncloturer);
file_put_contents($json_fileCloturer, $jsonCloturer);
$cache->write('finis_' . LIBELLECENTRALEUSER, $jsonCloturer);
fclose($fpCloturer);
chmod("../tmp/projetCloturerCentrale" . IDCENTRALEUSER . ".json", 0777);
$_SESSION['nbprojetCloturer'] = $nbrowCloturer;
