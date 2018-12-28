<?php

include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$nbtotalprojet = $manager->getSinglebyArray("select count(distinct idprojet_projet) from concerne,projet where idprojet_projet=idprojet AND idstatutprojet_statutprojet!=? AND idstatutprojet_statutprojet!=? "
        . "AND idstatutprojet_statutprojet!=? ", array(ACCEPTE, REFUSE, ENATTENTEPHASE2));
//TRAITEMENT PAR ANNEE
 if ($lang == 'fr') {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_PROJETPARDATETYPE . ' en ' . $anneeDepart;
        } else {
            $title = TXT_PROJETPARDATETYPE . ' entre ' . $anneeDepart . " et " . $anneeFin;
        }
    } else {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_PROJETPARDATETYPE . ' in' . $anneeDepart;
        } else {
            $title = TXT_PROJETDATESTATUTANNEE . ' between' . $anneeDepart . " AND " . $anneeFin;
        }
    }
if (IDTYPEUSER == ADMINNATIONNAL) {    
    $xasisTitle = "";    
     $nbtotalprojet = $manager->getSinglebyArray("
            SELECT COUNT(idprojet_projet) 
            FROM projet
            LEFT JOIN concerne ON idprojet=idprojet_projet
            WHERE  trashed !=? 
            AND EXTRACT(YEAR from dateprojet)<=? 
            AND EXTRACT(YEAR from dateprojet)>=? 
            AND idcentrale_centrale !=? ", array(TRUE, $anneeFin, $anneeDepart,IDCENTRALEAUTRE));
    $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
     $nbprojetExogeneExterne = $manager->getSinglebyArray("
            SELECT count(distinct co.idprojet_projet) 
            FROM projet p
            LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet 
            LEFT JOIN utilisateur u ON u.idutilisateur = cr.idutilisateur_utilisateur 
            LEFT JOIN concerne co ON co.idprojet_projet = p.idprojet 
            WHERE  u.idcentrale_centrale is null         
            AND  p.idprojet not in(select idprojet_projet from projetpartenaire)  
            AND trashed !=?
            AND EXTRACT(YEAR from dateprojet)<=? 
            and EXTRACT(YEAR from dateprojet)>=? 
            AND co.idcentrale_centrale!=?", array(TRUE, $anneeFin, $anneeDepart, IDCENTRALEAUTRE));

        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("
            SELECT count(distinct co.idprojet_projet) 
            FROM  projet p
            LEFT JOIN projetpartenaire pr ON pr.idprojet_projet = p.idprojet
            LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet 
            LEFT JOIN concerne co ON co.idprojet_projet = p.idprojet 
            LEFT JOIN utilisateur u ON u.idutilisateur = cr.idutilisateur_utilisateur 
            WHERE   u.idcentrale_centrale IS NOT NULL 
            AND p.trashed !=? 
            AND EXTRACT(YEAR from dateprojet)<=? 
            AND EXTRACT(YEAR from dateprojet)>=? 
            AND co.idcentrale_centrale!=?", array(TRUE, $anneeFin, $anneeDepart, IDCENTRALEAUTRE));
        $nbprojetInterne = $nbtotalprojet - ($nbprojetExogeneExterne + $nbprojetExogeneCollaboratif);
    
    $string0 = '["' . TXT_PROJETINTERNE . '",' . $nbprojetInterne . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . $nbprojetExogeneExterne . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . $nbprojetExogeneCollaboratif . '],';
    
} 
if (IDTYPEUSER == ADMINLOCAL) {
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet 
            AND  u.idutilisateur = cr.idutilisateur_utilisateur AND  u.idcentrale_centrale is null AND  co.idprojet_projet = p.idprojet AND p.idprojet not in(select idprojet_projet from projetpartenaire ) 
            AND co.idcentrale_centrale=? AND trashed !=? AND extract(year from dateprojet)>=? AND extract(year from dateprojet)<=?", array(IDCENTRALEUSER,TRUE,$anneeDepart,$anneeFin));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
        AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur AND  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL AND co.idcentrale_centrale=? 
         AND trashed !=? AND extract(year from dateprojet)>=? AND extract(year from dateprojet)<=?",array(IDCENTRALEUSER,TRUE,$anneeDepart,$anneeFin));
    $nbprojetInterne = $manager->getSinglebyArray("
                  SELECT COUNT(*) 
                  FROM projet p
                  LEFT JOIN creer cr ON p.idprojet= cr.idprojet_projet
                  LEFT JOIN concerne co ON p.idprojet= co.idprojet_projet
                  LEFT JOIN utilisateur u ON u.idutilisateur = cr.idutilisateur_utilisateur 
                  WHERE co.idcentrale_centrale = ?
                  AND u.idcentrale_centrale = ?
                  AND EXTRACT (YEAR FROM dateprojet) between ? AND ?
                  and p.trashed != ?", array(IDCENTRALEUSER,IDCENTRALEUSER,$anneeDepart,$anneeFin,TRUE));
    
    $nbtotalprojet = $nbprojetExogeneExterne+$nbprojetInterne+$nbprojetExogeneCollaboratif;
    $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
    $string0 = '["' . TXT_PROJETINTERNE . '",' . $nbprojetInterne . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . $nbprojetExogeneExterne . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . $nbprojetExogeneCollaboratif . '],';
}
$string = substr($string0 . $string3 . $string4, 0, -1);
include_once 'commun/scriptPie.php';
