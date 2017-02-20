<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string1 = '';
$string2 = '';
$string3 = '';
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeNouveauxPorteursProjet']) ) {
    $title = TXT_ORIGINENOUVEAUPORTEURPROJETDATE.(date('Y')-1);    
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and extract(year from datecreation)<=?",
            array(PERMANENT,(date('Y')-1)));    
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=?",
            array(PERMANENTINDUST,(date('Y')-1)));    
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)<=?",
            array(NONPERMANENT,(date('Y')-1)));    
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)<=?",
            array(NONPERMANENTINDUST,(date('Y')-1)));    
    $nbPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null "
            . "and extract(year from datecreation)<=?",array(PERMANENT,(date('Y')-1)));    
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null and EXTRACT(YEAR from datecreation)<=?",
            array(NONPERMANENT,(date('Y')-1)));
    $nbTotaluser = $nbPermanentExterne+$nbPermanentIndustriel+$nbNonPermanentExterne+$nbNonPermanentIndustriel+$nbPermanentInterne+$nbNonPermanentInterne;   
    
    $string0 = '["' . TXT_PERMANENTINTERNE . '",' . $nbPermanentInterne . '],';
    $string3 = '["' . TXT_NONPERMANENTINTERNE . '",' . $nbNonPermanentInterne . '],';
    $string1 = '["' . TXT_PERMANENTEXTERNE . '",' . $nbPermanentExterne . '],';
    $string2 = '["' . TXT_PERMANENTINDUSTRIEL . '",' . $nbPermanentIndustriel . '],';
    $string4 = '["' . TXT_NONPERMANENTEXTERNE . '",' . $nbNonPermanentExterne . '],';
    $string5 = '["' . TXT_NONPERMANENTINDUSTRIEL . '",' . $nbNonPermanentIndustriel . '],';
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeNouveauxPorteursProjet'])) {
    $title = TXT_ORIGINENOUVEAUPORTEURPROJETDATE.$_GET['anneeNouveauxPorteursProjet'];    
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and extract(year from datecreation)<=?",
            array(PERMANENT,$_GET['anneeNouveauxPorteursProjet']));    
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=?",
            array(PERMANENTINDUST,$_GET['anneeNouveauxPorteursProjet'])); 
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)<=?",
            array(NONPERMANENT,$_GET['anneeNouveauxPorteursProjet']));    
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)<=?",
            array(NONPERMANENTINDUST,$_GET['anneeNouveauxPorteursProjet']));
    $nbPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null "
            . "and extract(year from datecreation)<=?",array(PERMANENT,$_GET['anneeNouveauxPorteursProjet']));
    
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null and EXTRACT(YEAR from datecreation)<=?",
            array(NONPERMANENT,$_GET['anneeNouveauxPorteursProjet']));
    
    $nbTotaluser = $nbPermanentExterne+$nbPermanentIndustriel+$nbNonPermanentExterne+$nbNonPermanentIndustriel+$nbPermanentInterne+$nbNonPermanentInterne;
    
    $string0 = '["' . TXT_PERMANENTINTERNE . '",' . $nbPermanentInterne . '],';
    $string3 = '["' . TXT_NONPERMANENTINTERNE . '",' . $nbNonPermanentInterne . '],';
    $string1 = '["' . TXT_PERMANENTEXTERNE . '",' . $nbPermanentExterne . '],';
    $string2 = '["' . TXT_PERMANENTINDUSTRIEL . '",' . $nbPermanentIndustriel . '],';
    $string4 = '["' . TXT_NONPERMANENTEXTERNE . '",' . $nbNonPermanentExterne . '],';
    $string5 = '["' . TXT_NONPERMANENTINDUSTRIEL . '",' . $nbNonPermanentIndustriel . '],';
}

if (IDTYPEUSER == ADMINLOCAL) {
    $title = TXT_ORIGINENOUVEAUPORTEURPROJETDATE.(date('Y')-1);
    
   $nbPermanentInterne =$manager->getSinglebyArray("select count(idutilisateur) from utilisateur  WHERE idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null and idcentrale_centrale=? and extract(year from datecreation)<=?",
    array(PERMANENT,IDCENTRALEUSER,(date('Y')-1)));    
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idcentrale_centrale=?",
            array(PERMANENTINDUST,(date('Y')-1),IDCENTRALEUSER));     
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)<=? and co.idcentrale_centrale=?",
            array(NONPERMANENT,(date('Y')-1),IDCENTRALEUSER));
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet "
            . "and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idcentrale_centrale=? and EXTRACT(YEAR from datecreation)<=?",array(NONPERMANENTINDUST,IDCENTRALEUSER,(date('Y')-1)));    
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null and EXTRACT(YEAR from datecreation)<=?"
            . "and idcentrale_centrale=?",array(NONPERMANENT,(date('Y')-1),IDCENTRALEUSER));
    $nbPermanentExterne= $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur  AND cr.idprojet_projet = co.idprojet_projet "
            . "and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idcentrale_centrale=?",array(PERMANENT,(date('Y')-1),IDCENTRALEUSER));  
    
    $nbTotaluser = $nbPermanentInterne+$nbPermanentExterne+$nbPermanentIndustriel+$nbNonPermanentExterne+$nbNonPermanentIndustriel+$nbNonPermanentInterne;        
    
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
