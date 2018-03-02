<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string1 = '';
$string2 = '';
$string3 = '';
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeOriginePorteurProjetEncours'])) {
    $title = TXT_ORIGINEPORTEURPROJETENCOURSDATE . (date('Y') - 1);    
    $nbPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?",
            array(PERMANENT,ENCOURSREALISATION,(date('Y') - 1)));
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?", 
            array(PERMANENT,ENCOURSREALISATION,(date('Y') - 1)));    
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idstatutprojet_statutprojet=?",
            array(PERMANENTINDUST,(date('Y') - 1),ENCOURSREALISATION));
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?",
            array(NONPERMANENT,ENCOURSREALISATION,(date('Y') - 1)));
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?", 
            array(NONPERMANENT,ENCOURSREALISATION,(date('Y') - 1)));
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?",
            array(NONPERMANENTINDUST,ENCOURSREALISATION,(date('Y') - 1)));
    
    $nbTotaluser = $nbPermanentExterne + $nbPermanentIndustriel + $nbNonPermanentExterne + $nbNonPermanentIndustriel + $nbPermanentInterne + $nbNonPermanentInterne;

    $string0 = '["' . TXT_PERMANENTINTERNE . '",' . $nbPermanentInterne . '],';
    $string3 = '["' . TXT_NONPERMANENTINTERNE . '",' . $nbNonPermanentInterne . '],';
    $string1 = '["' . TXT_PERMANENTEXTERNE . '",' . $nbPermanentExterne . '],';
    $string2 = '["' . TXT_PERMANENTINDUSTRIEL . '",' . $nbPermanentIndustriel . '],';
    $string4 = '["' . TXT_NONPERMANENTEXTERNE . '",' . $nbNonPermanentExterne . '],';
    $string5 = '["' . TXT_NONPERMANENTINDUSTRIEL . '",' . $nbNonPermanentIndustriel . '],';
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeOriginePorteurProjetEncours'])) {
    $title = TXT_ORIGINEPORTEURPROJETENCOURSDATE . $_GET['anneeOriginePorteurProjetEncours'];
    $nbPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?",
            array(PERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?", 
            array(PERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));    
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idstatutprojet_statutprojet=?",
            array(PERMANENTINDUST,$_GET['anneeOriginePorteurProjetEncours'],ENCOURSREALISATION));
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?",
            array(NONPERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?", 
            array(NONPERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?",
            array(NONPERMANENTINDUST,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));

    $nbTotaluser = $nbPermanentExterne + $nbPermanentIndustriel + $nbNonPermanentExterne + $nbNonPermanentIndustriel + $nbPermanentInterne + $nbNonPermanentInterne;

    $string0 = '["' . TXT_PERMANENTINTERNE . '",' . $nbPermanentInterne . '],';
    $string3 = '["' . TXT_NONPERMANENTINTERNE . '",' . $nbNonPermanentInterne . '],';
    $string1 = '["' . TXT_PERMANENTEXTERNE . '",' . $nbPermanentExterne . '],';
    $string2 = '["' . TXT_PERMANENTINDUSTRIEL . '",' . $nbPermanentIndustriel . '],';
    $string4 = '["' . TXT_NONPERMANENTEXTERNE . '",' . $nbNonPermanentExterne . '],';
    $string5 = '["' . TXT_NONPERMANENTINDUSTRIEL . '",' . $nbNonPermanentIndustriel . '],';
}

if (IDTYPEUSER == ADMINLOCAL) {
    $title = TXT_ORIGINEPORTEURPROJETENCOURSDATE . (date('Y') - 1);
    $nbPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?"
    . "and u.idcentrale_centrale=?",array(PERMANENT,ENCOURSREALISATION,(date('Y') - 1),IDCENTRALEUSER));
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?"
            . "and co.idcentrale_centrale=?",array(PERMANENT,ENCOURSREALISATION,(date('Y') - 1),IDCENTRALEUSER));
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idstatutprojet_statutprojet=?"
    . "and co.idcentrale_centrale=?",array(PERMANENTINDUST,(date('Y') - 1),ENCOURSREALISATION,IDCENTRALEUSER));
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=? "
    . "and u.idcentrale_centrale=?",array(NONPERMANENT,ENCOURSREALISATION,(date('Y') - 1),IDCENTRALEUSER));
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?"
    . "and co.idcentrale_centrale=?", array(NONPERMANENT,ENCOURSREALISATION,(date('Y') - 1),IDCENTRALEUSER));
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=? "
    . "and co.idcentrale_centrale=?",array(NONPERMANENTINDUST,ENCOURSREALISATION,(date('Y') - 1),IDCENTRALEUSER));

    
    $nbTotaluser = $nbPermanentInterne+$nbPermanentExterne + $nbPermanentIndustriel + $nbNonPermanentExterne + $nbNonPermanentIndustriel + $nbNonPermanentInterne;

    $string0 = '["' . TXT_PERMANENTINTERNE . '",' . $nbPermanentInterne . '],';
    $string1 = '["' . TXT_PERMANENTEXTERNE . '",' . $nbPermanentExterne . '],';
    $string2 = '["' . TXT_PERMANENTINDUSTRIEL . '",' . $nbPermanentIndustriel . '],';
    $string3 = '["' . TXT_NONPERMANENTINTERNE . '",' . $nbNonPermanentInterne . '],';
    $string4 = '["' . TXT_NONPERMANENTINDUSTRIEL . '",' . $nbNonPermanentIndustriel . '],';
    $string5 = '["' . TXT_NONPERMANENTEXTERNE . '",' . $nbNonPermanentExterne . '],';
}

$str = $string0 . $string1 . $string2 . $string3 . $string4 . $string5;
$string = substr($str, 0, -1);
$subtitle = TXT_NOMBREBUSER . ' ' . $nbTotaluser;
include_once 'commun/scriptPie.php';
BD::deconnecter();
