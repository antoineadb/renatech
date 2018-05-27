<?php
$manager->exeRequete("drop table if exists tmpencoursrealisation;");
//CREATION DE LA TABLE TEMPORAIRE
        $manager->getRequete("create table tmpencoursrealisation as
(SELECT p.idprojet,p.datemaj,p.acronyme,p.idperiodicite_periodicite,p.dureeprojet,p.refinterneprojet,s.libellestatutprojet,libellestatutprojeten,s.idstatutprojet,p.numero,p.titre,p.datedebutprojet,p.dateprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire,null as nomAffecte,null as prenomAffecte,ce.libellecentrale
  FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
  WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
  co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet
  AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.libellecentrale = ? and s.idstatutprojet=? AND trashed =FALSE
union
SELECT idprojet,p.datemaj,acronyme,p.idperiodicite_periodicite,p.dureeprojet,p.refinterneprojet,s.libellestatutprojet,libellestatutprojeten,s.idstatutprojet,numero,titre,p.datedebutprojet,p.dateprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire,u1.nom,u1.prenom,ce.libellecentrale
FROM projet p,utilisateur u,creer,centrale ce,concerne,typeprojet,statutprojet s,utilisateurporteurprojet,utilisateur u1
WHERE idtypeprojet_typeprojet = typeprojet.idtypeprojet AND u.idutilisateur = creer.idutilisateur_utilisateur AND
  creer.idprojet_projet = idprojet AND concerne.idcentrale_centrale = idcentrale AND concerne.idprojet_projet = idprojet AND
  concerne.idstatutprojet_statutprojet = s.idstatutprojet AND utilisateurporteurprojet.idprojet_projet = idprojet AND
  utilisateurporteurprojet.idutilisateur_utilisateur = u1.idutilisateur AND ce.libellecentrale = ? and s.idstatutprojet=? AND trashed =FALSE
order by idprojet asc);", array($libellecentrale, ENCOURSREALISATION, $libellecentrale, ENCOURSREALISATION));

        $manager->exeRequete("ALTER TABLE tmpencoursrealisation ADD COLUMN calcfinprojet date;");
        $manager->exeRequete("ALTER TABLE tmpencoursrealisation ADD COLUMN finprojetproche date;");
        $arrayprojetrealisation = $manager->getList("select distinct on(idprojet) idprojet,datemaj,acronyme,idperiodicite_periodicite,dureeprojet,refinterneprojet,libellestatutprojet,libellestatutprojeten,idstatutprojet,
            numero,titre,datedebutprojet,dateprojet,nom,nomentreprise,entrepriselaboratoire,nomaffecte,prenomaffecte,libellecentrale,calcfinprojet,finprojetproche from tmpencoursrealisation");
        $nbProjetreal = count($arrayprojetrealisation);
        for ($i = 0; $i < $nbProjetreal; $i++) {
            if ($arrayprojetrealisation[$i]['idperiodicite_periodicite'] == JOUR) {
                $datedepart = strtotime($arrayprojetrealisation[$i]['datedebutprojet']);
                $duree = ($arrayprojetrealisation[$i]['dureeprojet']);
                $dateFin = date('Y-m-d', strtotime('+' . $duree . 'day', $datedepart));
                $dureeproche = $duree - 15;
                $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
                $annee = (int) date('Y', strtotime($dateFinproche));
                $manager->getRequete("update tmpencoursrealisation set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin, $dateFinproche, $arrayprojetrealisation[$i]['idprojet']));
            } elseif ($arrayprojetrealisation[$i]['idperiodicite_periodicite'] == MOIS) {
                $datedepart = strtotime($arrayprojetrealisation[$i]['datedebutprojet']);
                $duree = ($arrayprojetrealisation[$i]['dureeprojet']);
                $dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
                $dureeproche = ($duree * 30) - 15;
                $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
                $annee = (int) date('Y', strtotime($dateFinproche));
                $manager->getRequete("update tmpencoursrealisation set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin, $dateFinproche, $arrayprojetrealisation[$i]['idprojet']));
            } elseif ($arrayprojetrealisation[$i]['idperiodicite_periodicite'] == ANNEE) {
                $datedepart = strtotime($arrayprojetrealisation[$i]['datedebutprojet']);
                $duree = ($arrayprojetrealisation[$i]['dureeprojet']);
                $dateFin = date('Y-m-d', strtotime('+' . $duree . 'year', $datedepart));
                $dureeproche = ($duree * 365) - 15;
                $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
                $annee = (int) date('Y', strtotime($dateFinproche));
                $manager->getRequete("update tmpencoursrealisation set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin, $dateFinproche, $arrayprojetrealisation[$i]['idprojet']));
            }
        }
        $rowProjetEncoursRealisation = $manager->getList("
    select  finprojetproche,datemaj,calcfinprojet,idprojet,acronyme,refinterneprojet,libellestatutprojet,libellestatutprojeten,idstatutprojet,numero,titre,datedebutprojet,dateprojet,nom,nomentreprise,entrepriselaboratoire,nomaffecte,prenomaffecte,libellecentrale from tmpencoursrealisation
    where idprojet not in (select idprojet from tmpencoursrealisation where nomaffecte is not null)
    union
    select finprojetproche,datemaj,calcfinprojet,idprojet,acronyme,refinterneprojet,libellestatutprojet,libellestatutprojeten,idstatutprojet,numero,titre,datedebutprojet,dateprojet,nom,nomentreprise,entrepriselaboratoire,nomaffecte,prenomaffecte,libellecentrale from tmpencoursrealisation where nomaffecte is not null
    order by idprojet asc");
        $fpProjetEncoursRealisation = fopen("../tmp/ProjetEncoursRealisationcentrale".IDCENTRALEUSER.".json", 'w');
        $dataProjetEncoursRealisation = "";
        fwrite($fpProjetEncoursRealisation, '{"items": [');
        $nbrowProjetEncoursRealisation = count($rowProjetEncoursRealisation);
        for ($i = 0; $i < $nbrowProjetEncoursRealisation; $i++) {
            if (!empty($rowProjetEncoursRealisation[$i]['datemaj'])) {
                $datemaj = $rowProjetEncoursRealisation[$i]['datemaj'];
            } else {
                $datemaj = '';
            }
            if ($lang == 'fr') {
                $dataProjetEncoursRealisation = "" . '{"dateprojet":' . '"' . $rowProjetEncoursRealisation[$i]['dateprojet'] . '"' . ","
                        . '"datedebutprojet":' . '"' . $rowProjetEncoursRealisation[$i]['datedebutprojet'] . '"' . ","
                        . '"idstatutprojet":' . '"' . $rowProjetEncoursRealisation[$i]['idstatutprojet'] . '"' . ","
                        . '"libellecentrale":' . '"' . $rowProjetEncoursRealisation[$i]['libellecentrale'] . '"' . ","
                        . '"datemaj":' . '"' . $datemaj . '"' . ","
                        . '"numero":' . '"' . $rowProjetEncoursRealisation[$i]['numero'] . '"' . ","
                        . '"idprojet":' . '"' . $rowProjetEncoursRealisation[$i]['idprojet'] . '"' . ","
                        . '"titre":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['titre']) . '"' . ","
                        . '"libellestatutprojet":' . '"' . $rowProjetEncoursRealisation[$i]['libellestatutprojet'] . '"' . ","
                        . '"nom":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['nom']) . '"' . ","
                        . '"nomentreprise":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['nomentreprise']) . '"' . ","
                        . '"calcfinproche":' . '"' . $rowProjetEncoursRealisation[$i]['finprojetproche'] . '"' . ","
                        . '"calcfinprojet":' . '"' . $rowProjetEncoursRealisation[$i]['calcfinprojet'] . '"' . ","
                        . '"entrepriselaboratoire":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['entrepriselaboratoire']) . '"' . ","
                        . '"acronyme":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['acronyme']) . ' - ' . filtredonnee($rowProjetEncoursRealisation[$i]['refinterneprojet']) . '"' . "},";
                fputs($fpProjetEncoursRealisation, $dataProjetEncoursRealisation);
                fwrite($fpProjetEncoursRealisation, '');
            } elseif ($lang == 'en') {
                for ($i = 0; $i < $nbrowProjetEncoursRealisation; $i++) {
                    $dataProjetEncoursRealisation = "" . '{"dateprojet":' . '"' . $rowProjetEncoursRealisation[$i]['dateprojet'] . '"' . ","
                            . '"datedebutprojet":' . '"' . $rowProjetEncoursRealisation[$i]['datedebutprojet'] . '"' . ","
                            . '"idstatutprojet":' . '"' . $rowProjetEncoursRealisation[$i]['idstatutprojet'] . '"' . ","
                            . '"libellecentrale":' . '"' . $rowProjetEncoursRealisation[$i]['libellecentrale'] . '"' . ","
                            . '"datemaj":' . '"' . $datemaj . '"' . ","
                            . '"numero":' . '"' . $rowProjetEncoursRealisation[$i]['numero'] . '"' . ","
                            . '"titre":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['titre']) . '"' . ","
                            . '"libellestatutprojet":' . '"' . $rowProjetEncoursRealisation[$i]['libellestatutprojeten'] . '"' . ","
                            . '"nom":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['nom']) . '"' . ","
                            . '"nomentreprise":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['nomentreprise']) . '"' . ","
                            . '"calcfinproche":' . '"' . $rowProjetEncoursRealisation[$i]['finprojetproche'] . '"' . ","
                            . '"calcfinprojet":' . '"' . $rowProjetEncoursRealisation[$i]['calcfinprojet'] . '"' . ","
                            . '"entrepriselaboratoire":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['entrepriselaboratoire']) . '"' . ","
                            . '"acronyme":' . '"' . filtredonnee($rowProjetEncoursRealisation[$i]['acronyme']) . ' - ' . filtredonnee($rowProjetEncoursRealisation[$i]['refinterneprojet']) . '"' . "},";
                    fputs($fpProjetEncoursRealisation, $dataProjetEncoursRealisation);
                    fwrite($fpProjetEncoursRealisation, '');
                }
            }
        }
        fwrite($fpProjetEncoursRealisation, ']}');
        $json_fileEncoursRealisation = "../tmp/ProjetEncoursRealisationcentrale".IDCENTRALEUSER.".json";
        $jsonEncoursRealisation = file_get_contents($json_fileEncoursRealisation);
        $jsonEncoursRealisation1 = str_replace('},]}', '}]}', $jsonEncoursRealisation);
        file_put_contents($json_fileEncoursRealisation, $jsonEncoursRealisation1);
        $cache->write('encours_'.LIBELLECENTRALEUSER,$jsonEncoursRealisation1);
        fclose($fpProjetEncoursRealisation);
        chmod("../tmp/ProjetEncoursRealisationcentrale".IDCENTRALEUSER.".json", 0777);
        $_SESSION['nbprojetencoursrealisation'] = $nbProjetreal;
        