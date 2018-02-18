<?php

include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);


$dateCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet "
        . "  and idtypeprojet=? and idcentrale_centrale=? "
        . "  AND idstatutprojet_statutprojet=?";

$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet "
        . "  and idtypeprojet=?  AND idstatutprojet_statutprojet=?";
//TRAITEMENT PAR ANNEE
if (IDTYPEUSER == ADMINNATIONNAL) {
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array( ACADEMIC,ENCOURSREALISATION));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraleunedate, array( ACADEMICPARTENARIAT,ENCOURSREALISATION));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraleunedate, array( INDUSTRIEL,ENCOURSREALISATION));
    $nbformation = (int)$manager->getSinglebyArray($touscentraleunedate, array( FORMATION,ENCOURSREALISATION));
    if(empty($nbformation)){$nbformation=0;}
}elseif (IDTYPEUSER == ADMINLOCAL) {
    $nbprojetAcademique = $manager->getSinglebyArray($dateCentrale, array(ACADEMIC, IDCENTRALEUSER,ENCOURSREALISATION));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($dateCentrale, array(ACADEMICPARTENARIAT, IDCENTRALEUSER,ENCOURSREALISATION));
    $nbprojetindustriel = $manager->getSinglebyArray($dateCentrale, array(INDUSTRIEL, IDCENTRALEUSER,ENCOURSREALISATION));
    $nbformation = $manager->getSinglebyArray($dateCentrale, array(FORMATION, IDCENTRALEUSER,ENCOURSREALISATION));
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
