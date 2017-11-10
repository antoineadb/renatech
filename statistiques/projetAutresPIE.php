<?php

include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$nbtotalprojet = $manager->getSinglebyArray("select count(distinct idprojet_projet) from concerne,projet where idprojet_projet=idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? "
        . "and idstatutprojet_statutprojet!=? ", array(ACCEPTE, REFUSE, ENATTENTEPHASE2));
//TRAITEMENT PAR ANNEE
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeType'])) {
    $title = TXT_PROJETPARDATETYPEPOURANNEE . (date('Y') - 1);
    $xasisTitle = "";
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne where idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? "
            . "and idstatutprojet_statutprojet!=? and extract(year from dateprojet)<=? and trashed !=? ", array(ACCEPTE, REFUSE, ENATTENTEPHASE2, (date('Y') - 1),TRUE));
    $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE cr.idprojet_projet = p.idprojet "
            . "AND u.idutilisateur = cr.idutilisateur_utilisateur AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and idstatutprojet_statutprojet!=? "
            . "and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and extract(year from dateprojet)<=? and p.idprojet not in(select idprojet_projet from projetpartenaire ) ",
            array( ACCEPTE, REFUSE, ENATTENTEPHASE2, (date('Y') - 1)));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
        AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and idstatutprojet_statutprojet!=?  
        and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and extract(year from dateprojet)<=? and trashed !=?", array(ACCEPTE, REFUSE, ENATTENTEPHASE2, (date('Y') - 1),TRUE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $string0 = '["' . TXT_PROJETINTERNE . '",' . $nbprojetInterne . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . $nbprojetExogeneExterne . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . $nbprojetExogeneCollaboratif . '],';
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeType'])) {
    $title = TXT_PROJETPARDATETYPEPOURANNEE . $_GET['anneeType'];
    $xasisTitle = "";
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne where idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? "
            . "and idstatutprojet_statutprojet!=? and extract(year from dateprojet)<=? and trashed!=?", array(ACCEPTE, REFUSE, ENATTENTEPHASE2, $_GET['anneeType'],TRUE));
    $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE cr.idprojet_projet = p.idprojet "
            . "AND u.idutilisateur = cr.idutilisateur_utilisateur AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and idstatutprojet_statutprojet!=? "
            . "and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and extract(year from dateprojet)<=? and p.idprojet not in(select idprojet_projet from projetpartenaire )",
            array(ACCEPTE, REFUSE, ENATTENTEPHASE2, $_GET['anneeType']));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
        AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and idstatutprojet_statutprojet!=?  
        and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and extract(year from dateprojet)<=? and trashed !=?", array(ACCEPTE, REFUSE, ENATTENTEPHASE2, $_GET['anneeType'],TRUE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $string0 = '["' . TXT_PROJETINTERNE . '",' . $nbprojetInterne . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . $nbprojetExogeneExterne . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . $nbprojetExogeneCollaboratif . '],';
}
if (IDTYPEUSER == ADMINLOCAL) {
    $nbtotalprojet = $manager->getSinglebyArray("select count(distinct idprojet_projet) from concerne,projet where idprojet_projet=idprojet and extract(year from dateprojet)<=? and idcentrale_centrale=? and trashed !=?"
            ,array((date('Y')-1),IDCENTRALEUSER,TRUE));
    $title = TXT_PROJETPARDATETYPEPOURANNEE . (date('Y')-1);
    $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet 
            AND  u.idutilisateur = cr.idutilisateur_utilisateur AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) 
            and co.idcentrale_centrale=? and extract(year from dateprojet)<=? AND trashed !=?", array(IDCENTRALEUSER,(date('Y')-1),TRUE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
        AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and co.idcentrale_centrale=? 
        and extract(year from dateprojet)<=? AND trashed !=?",array(IDCENTRALEUSER,(date('Y')-1),TRUE));
    $nbprojetInterne = $nbtotalprojet - ($nbprojetExogeneExterne + $nbprojetExogeneCollaboratif);
    $string0 = '["' . TXT_PROJETINTERNE . '",' . $nbprojetInterne . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . $nbprojetExogeneExterne . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . $nbprojetExogeneCollaboratif . '],';
}
$string = substr($string0 . $string3 . $string4, 0, -1);
include_once 'commun/scriptPie.php';
