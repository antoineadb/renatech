<?php

 //SUPPRESSION DE LA TABLE TEMPORAIRE SI ELLE EXISTE
            $manager->exeRequete("drop table if exists tmpaccepte;");
    //CREATION DE LA TABLE TEMPORAIRE
            $manager->getRequete("create table tmpaccepte as
        (
    SELECT p.idprojet,p.acronyme,s.libellestatutprojet,s.idstatutprojet,p.numero,p.titre,p.dateprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire,null as nomAffecte,null as prenomAffecte,ce.libellecentrale,p.refinterneprojet
      FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
      WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
      co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet
      AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.libellecentrale =? and s.idstatutprojet=? AND trashed =FALSE
    union
    SELECT idprojet,acronyme,s.libellestatutprojet,s.idstatutprojet,numero,titre,dateprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire,u1.nom,u1.prenom,ce.libellecentrale,refinterneprojet
    FROM projet,utilisateur u,creer,centrale ce,concerne,typeprojet,statutprojet s,utilisateurporteurprojet,utilisateur u1
    WHERE idtypeprojet_typeprojet = typeprojet.idtypeprojet AND u.idutilisateur = creer.idutilisateur_utilisateur AND
      creer.idprojet_projet = idprojet AND concerne.idcentrale_centrale = idcentrale AND concerne.idprojet_projet = idprojet AND
      concerne.idstatutprojet_statutprojet = s.idstatutprojet AND utilisateurporteurprojet.idprojet_projet = idprojet AND
      utilisateurporteurprojet.idutilisateur_utilisateur = u1.idutilisateur AND ce.libellecentrale =? and s.idstatutprojet=? AND trashed =FALSE order by idprojet asc
    );", array($libellecentrale, ACCEPTE, $libellecentrale, ACCEPTE));
            $rowProjetAcceptee = $manager->getList("
            select  idprojet,acronyme,libellestatutprojet,idstatutprojet,numero,titre,dateprojet,nom,nomentreprise,entrepriselaboratoire,nomaffecte,prenomaffecte,libellecentrale,refinterneprojet from tmpaccepte
            where idprojet not in (select idprojet from tmpaccepte where nomaffecte is not null)
            union
            select idprojet,acronyme,libellestatutprojet,idstatutprojet,numero,titre,dateprojet,nom,nomentreprise,entrepriselaboratoire,nomaffecte,prenomaffecte,libellecentrale,refinterneprojet from tmpaccepte where nomaffecte is not null order by idprojet asc");
            $fpProjetAcceptee = fopen("../tmp/ProjetAccepteecentrale".IDCENTRALEUSER.".json", 'w');
            $dataProjetAcceptee = "";
            fwrite($fpProjetAcceptee, '{"items": [');
            $nbrowProjetAcceptee = count($rowProjetAcceptee);
            for ($i = 0; $i < $nbrowProjetAcceptee; $i++) {
                $dataProjetAcceptee = "" . '{"dateProjet":' . '"' . $rowProjetAcceptee[$i]['dateprojet'] . '"' . "," . '"numero":' . '"' . $rowProjetAcceptee[$i]['numero'] . '"' . "," . '"titre":' . '"' .
                        filtredonnee($rowProjetAcceptee[$i]['titre']) . '"' . ","
                        . '"libellestatutProjet":' . '"' . str_replace("''", "'", $rowProjetAcceptee[$i]['libellestatutprojet']) . '"' . ","
                        . '"idstatutprojet":' . '"' . $rowProjetAcceptee[$i]['idstatutprojet'] . '"' . ","
                        . '"libellecentrale":' . '"' . $rowProjetAcceptee[$i]['libellecentrale'] . '"' . ","
                        . '"refinterneprojet":' . '"' . filtredonnee($rowProjetAcceptee[$i]['refinterneprojet']) . '"' . ","
                        . '"nom":' . '"' . filtredonnee($rowProjetAcceptee[$i]['nom']) . '"' . ","
                        . '"nomentreprise":' . '"' . filtredonnee($rowProjetAcceptee[$i]['nomentreprise']) . '"' . "," . '"entrepriselaboratoire":' . '"' . filtredonnee($rowProjetAcceptee[$i]['entrepriselaboratoire']) . '"'
                        . "," . '"acronyme":' . '"' . filtredonnee($rowProjetAcceptee[$i]['acronyme']) . '"' . "},";
                fputs($fpProjetAcceptee, $dataProjetAcceptee);
                fwrite($fpProjetAcceptee, '');
            }
            fwrite($fpProjetAcceptee, ']}');
            $json_fileAccepteeCentrale = "../tmp/ProjetAccepteecentrale".IDCENTRALEUSER.".json";
            $jsonAccepteeCentrale = file_get_contents($json_fileAccepteeCentrale);
            $jsonAccepteeCentrale1 = str_replace('},]}', '}]}', $jsonAccepteeCentrale);
            file_put_contents($json_fileAccepteeCentrale, $jsonAccepteeCentrale1);
            $cache->write('accepte_'.LIBELLECENTRALEUSER,$jsonAccepteeCentrale1);
            fclose($fpProjetAcceptee);
            chmod("../tmp/ProjetAccepteecentrale".IDCENTRALEUSER.".json", 0777);
            $_SESSION['nbprojetaccepte'] = $nbrowProjetAcceptee;