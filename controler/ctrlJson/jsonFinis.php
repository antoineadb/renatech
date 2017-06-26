<?php

$manager->exeRequete("drop table if exists tmpfini;");
//CREATION DE LA TABLE TEMPORAIRE
$manager->getRequete("create table tmpfini as
    (SELECT p.idprojet,p.acronyme,s.libellestatutprojet,s.idstatutprojet,p.numero,p.titre,p.datedebutprojet,p.datestatutfini,u.nom,u.nomentreprise,u.entrepriselaboratoire,null as nomAffecte,null as prenomAffecte,ce.libellecentrale,p.refinterneprojet
      FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
      WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
      co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet
      AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.libellecentrale = ? and s.idstatutprojet=? AND trashed =FALSE
    union
    SELECT idprojet,acronyme,s.libellestatutprojet,s.idstatutprojet,numero,titre,p.datedebutprojet,p.datestatutfini,u.nom,u.nomentreprise,u.entrepriselaboratoire,u1.nom,u1.prenom,ce.libellecentrale,p.refinterneprojet
    FROM projet p,utilisateur u,creer,centrale ce,concerne,typeprojet,statutprojet s,utilisateurporteurprojet,utilisateur u1
    WHERE idtypeprojet_typeprojet = typeprojet.idtypeprojet AND u.idutilisateur = creer.idutilisateur_utilisateur AND
      creer.idprojet_projet = idprojet AND concerne.idcentrale_centrale = idcentrale AND concerne.idprojet_projet = idprojet AND
      concerne.idstatutprojet_statutprojet = s.idstatutprojet AND utilisateurporteurprojet.idprojet_projet = idprojet AND
      utilisateurporteurprojet.idutilisateur_utilisateur = u1.idutilisateur AND ce.libellecentrale =? and s.idstatutprojet=? AND trashed =FALSE
    order by idprojet asc);", array($libellecentrale, FINI, $libellecentrale, FINI));
$rowFini = $manager->getList("
        select  idprojet,acronyme,libellestatutprojet,idstatutprojet,numero,titre,datedebutprojet,datestatutfini,nom,nomentreprise,entrepriselaboratoire,nomaffecte,prenomaffecte,libellecentrale,refinterneprojet from tmpfini
        where idprojet not in (select idprojet from tmpfini where nomaffecte is not null)
        union
        select idprojet,acronyme,libellestatutprojet,idstatutprojet,numero,titre,datedebutprojet,datestatutfini,nom,nomentreprise,entrepriselaboratoire,nomaffecte,prenomaffecte,libellecentrale,refinterneprojet from tmpfini where nomaffecte is not null
        order by idprojet asc");
$fpFini = fopen("../tmp/projetFiniCentrale" . IDCENTRALEUSER . ".json", 'w');
$dataFini = "";
fwrite($fpFini, '{"items": [');
$nbrowFini = count($rowFini);
for ($i = 0; $i < $nbrowFini; $i++) {
    $dataFini = "" . '{"datedebutprojet":' . '"' . $rowFini[$i]['datedebutprojet'] . '"' . ","
            . '"datestatutfini":' . '"' . $rowFini[$i]['datestatutfini'] . '"' . ","
            . '"libellecentrale":' . '"' . $rowFini[$i]['libellecentrale'] . '"' . ","
            . '"idprojet":' . '"' . $rowFini[$i]['idprojet'] . '"' . ","
            . '"idstatutprojet":' . '"' . $rowFini[$i]['idstatutprojet'] . '"' . ","
            . '"refinterneprojet":' . '"' . filtredonnee($rowFini[$i]['refinterneprojet']) . '"' . ","
            . '"numero":' . '"' . $rowFini[$i]['numero'] . '"' . "," . '"titre":' . '"' .
            filtredonnee($rowFini[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowFini[$i]['libellestatutprojet'] . '"' . ","
            . '"acronyme":' . '"' . $rowFini[$i]['acronyme'] . '"' . "},";
    fputs($fpFini, $dataFini);
    fwrite($fpFini, '');
}
fwrite($fpFini, ']}');
$json_fileFini = "../tmp/projetFiniCentrale" . IDCENTRALEUSER . ".json";
$jsonfini = file_get_contents($json_fileFini);
$jsonFini = str_replace('},]}', '}]}', $jsonfini);
file_put_contents($json_fileFini, $jsonFini);
$cache->write('finis_' . LIBELLECENTRALEUSER, $jsonFini);
fclose($fpFini);
chmod("../tmp/projetFiniCentrale" . IDCENTRALEUSER . ".json", 0777);
$_SESSION['nbFini'] = $nbrowFini;
