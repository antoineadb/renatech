<?php

include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)<=? and idtypeprojet=?"
        . "and EXTRACT(YEAR from dateprojet)>2012 and idcentrale_centrale!=?";
$tousdateunecentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=? "
        . "and EXTRACT(YEAR from dateprojet)>2012 and idcentrale_centrale!=? AND EXTRACT(YEAR from dateprojet)<=?";


$touscentraletoutesdate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? and EXTRACT(YEAR from dateprojet)>2012";
//TRAITEMENT PAR ANNEE
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeTypo'])) {
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),ACADEMIC,IDCENTRALEAUTRE));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),ACADEMICPARTENARIAT,IDCENTRALEAUTRE));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),INDUSTRIEL,IDCENTRALEAUTRE));
    $nbformation = (int)$manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),FORMATION,IDCENTRALEAUTRE)); if(empty($nbformation)){$nbformation=0;}
    $title=TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE.' '. (date('Y')-1);
}elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeTypo'])) {
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'],ACADEMIC,IDCENTRALEAUTRE));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'],ACADEMICPARTENARIAT,IDCENTRALEAUTRE));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'],INDUSTRIEL,IDCENTRALEAUTRE));
    $nbformation = (int)$manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'],FORMATION,IDCENTRALEAUTRE)); if(empty($nbformation)){$nbformation=0;}
    $title=TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE.' '. $_GET['anneeTypo'];    
} 

if (IDTYPEUSER == ADMINLOCAL) {
    $title=TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE.' '. (date('Y')-1);
    $nbprojetAcademique = $manager->getSinglebyArray($tousdateunecentrale, array(ACADEMIC, IDCENTRALEUSER,IDCENTRALEAUTRE,(date('Y')-1)));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($tousdateunecentrale, array(ACADEMICPARTENARIAT, IDCENTRALEUSER,IDCENTRALEAUTRE,(date('Y')-1)));
    $nbprojetindustriel = $manager->getSinglebyArray($tousdateunecentrale, array(INDUSTRIEL, IDCENTRALEUSER,IDCENTRALEAUTRE,(date('Y')-1)));
    $nbformation = $manager->getSinglebyArray($tousdateunecentrale, array(FORMATION, IDCENTRALEUSER,IDCENTRALEAUTRE,(date('Y')-1)));
} 
if(empty($nbformation)){$nbformation=0;}
$string0 = '["' . TXT_ACADEMIQUE . '",' . $nbprojetAcademique . '],';
$string3 = '["' . TXT_ACADEMICPARTENARIAT . '",' . $nbprojetAcademiquepartenariat  . '],';
$string4 = '["' . TXT_FORMATION . '",' . $nbformation . '],';
$string5 = '["' . TXT_INDUSTRIEL . '",' . $nbprojetindustriel  . '],';
$string = substr($string0 . $string3 . $string4 . $string5, 0, -1);
$nbtotalprojet = $nbprojetAcademique + $nbprojetAcademiquepartenariat + $nbprojetindustriel + $nbformation;
$xasisTitle = "";
 $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
include_once 'commun/scriptPie.php';
BD::deconnecter();
