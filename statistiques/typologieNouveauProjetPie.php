<?php

include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)<=? and idtypeprojet=?"
        . "and idcentrale_centrale!=? and trashed !=?";
$tousdateunecentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=? "
        . " and idcentrale_centrale!=? AND EXTRACT(YEAR from dateprojet)<=? and trashed !=?";
$touscentraletoutesdate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? and trashed!=?";
//TRAITEMENT PAR ANNEE
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeTypo'])) {
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),ACADEMIC,IDCENTRALEAUTRE,TRUE));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),ACADEMICPARTENARIAT,IDCENTRALEAUTRE,TRUE));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),INDUSTRIEL,IDCENTRALEAUTRE,TRUE));
    $nbformation = (int)$manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),FORMATION,IDCENTRALEAUTRE,TRUE)); if(empty($nbformation)){$nbformation=0;}
    $nbNonDefini = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),1,IDCENTRALEAUTRE,TRUE));
    $title=TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE.' '. (date('Y')-1);
}elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeTypo'])) {
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'],ACADEMIC,IDCENTRALEAUTRE,TRUE));;
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'],ACADEMICPARTENARIAT,IDCENTRALEAUTRE,TRUE));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'],INDUSTRIEL,IDCENTRALEAUTRE,TRUE));    
    $nbformation = (int)$manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'],FORMATION,IDCENTRALEAUTRE,TRUE)); if(empty($nbformation)){$nbformation=0;}
    $nbNonDefini = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'],1,IDCENTRALEAUTRE,TRUE));
    $title=TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE.' '. $_GET['anneeTypo'];    
} 

if (IDTYPEUSER == ADMINLOCAL) {
    $title=TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE.' '. (date('Y')-1);
    $nbprojetAcademique = $manager->getSinglebyArray($tousdateunecentrale, array(ACADEMIC, IDCENTRALEUSER,IDCENTRALEAUTRE,(date('Y')-1),TRUE));echo $nbprojetAcademique;
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($tousdateunecentrale, array(ACADEMICPARTENARIAT, IDCENTRALEUSER,IDCENTRALEAUTRE,(date('Y')-1),TRUE));
    $nbprojetindustriel = $manager->getSinglebyArray($tousdateunecentrale, array(INDUSTRIEL, IDCENTRALEUSER,IDCENTRALEAUTRE,(date('Y')-1),TRUE));
    $nbformation = $manager->getSinglebyArray($tousdateunecentrale, array(FORMATION, IDCENTRALEUSER,IDCENTRALEAUTRE,(date('Y')-1),TRUE));
    $nbNonDefini = $manager->getSinglebyArray($tousdateunecentrale, array(1, IDCENTRALEUSER,IDCENTRALEAUTRE,(date('Y')-1),TRUE));
} 
if(empty($nbformation)){$nbformation=0;}
$string0 = '["' . TXT_ACADEMIQUE . '",' . $nbprojetAcademique . '],';
$string3 = '["' . TXT_ACADEMICPARTENARIAT . '",' . $nbprojetAcademiquepartenariat  . '],';
$string4 = '["' . TXT_FORMATION . '",' . $nbformation . '],';
$string5 = '["' . TXT_INDUSTRIEL . '",' . $nbprojetindustriel  . '],';
$string6 = '["' . "Non défini" . '",' . $nbNonDefini  . '],';
$string = substr($string0 . $string3 . $string4 . $string5 .$string6, 0, -1);
$nbtotalprojet = $nbprojetAcademique + $nbprojetAcademiquepartenariat + $nbprojetindustriel + $nbformation + $nbNonDefini;
$xasisTitle = "";
 $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
include_once 'commun/scriptPie.php';
BD::deconnecter();
