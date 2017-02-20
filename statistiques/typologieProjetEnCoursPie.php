<?php

include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)<=? and idtypeprojet=?"
        . "and EXTRACT(YEAR from dateprojet)>2012  AND idstatutprojet_statutprojet=?";
$dateCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=? "
        . "and EXTRACT(YEAR from dateprojet)>2012  AND idstatutprojet_statutprojet=? and EXTRACT(YEAR from dateprojet)<=?";

//TRAITEMENT PAR ANNEE
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeTypoProjetEncours'])) {
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),ACADEMIC,ENCOURSREALISATION));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),ACADEMICPARTENARIAT,ENCOURSREALISATION));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),INDUSTRIEL,ENCOURSREALISATION));
    $nbformation = (int)$manager->getSinglebyArray($touscentraleunedate, array((date('Y')-1),FORMATION,ENCOURSREALISATION)); if(empty($nbformation)){$nbformation=0;}
    $title=TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE. (date('Y')-1);    
}elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeTypoProjetEncours'])) {
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypoProjetEncours'],ACADEMIC,ENCOURSREALISATION));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypoProjetEncours'],ACADEMICPARTENARIAT,ENCOURSREALISATION));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypoProjetEncours'],INDUSTRIEL,ENCOURSREALISATION));
    $nbformation = (int)$manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypoProjetEncours'],FORMATION,ENCOURSREALISATION)); if(empty($nbformation)){$nbformation=0;}
    $title=TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE. $_GET['anneeTypoProjetEncours'];    
} 

if (IDTYPEUSER == ADMINLOCAL) {
    $nbprojetAcademique = $manager->getSinglebyArray($dateCentrale, array(ACADEMIC, IDCENTRALEUSER,ENCOURSREALISATION,(date('Y')-1)));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($dateCentrale, array(ACADEMICPARTENARIAT, IDCENTRALEUSER,ENCOURSREALISATION,(date('Y')-1)));
    $nbprojetindustriel = $manager->getSinglebyArray($dateCentrale, array(INDUSTRIEL, IDCENTRALEUSER,ENCOURSREALISATION,(date('Y')-1)));
    $nbformation = $manager->getSinglebyArray($dateCentrale, array(FORMATION, IDCENTRALEUSER,ENCOURSREALISATION,(date('Y')-1)));
    $title=TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE. (date('Y')-1); 
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
