<?php

include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);


$dateCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet "
        . "  and idtypeprojet=? and idcentrale_centrale=? "
        . "  AND idstatutprojet_statutprojet=?  AND EXTRACT(YEAR from dateprojet)>2012 ";

$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet "
        . "  and idtypeprojet=?  AND idstatutprojet_statutprojet=?  AND EXTRACT(YEAR from dateprojet)>2012";
//TRAITEMENT PAR ANNEE
if (IDTYPEUSER == ADMINNATIONNAL) {
    $nbProjetAcademique = $manager->getSinglebyArray($touscentraleunedate, array(ACADEMIC, ENCOURSREALISATION));
    $nbProjetAcademiquePartenariat = $manager->getSinglebyArray($touscentraleunedate, array(ACADEMICPARTENARIAT, ENCOURSREALISATION));
    $nbProjetIndustriel = $manager->getSinglebyArray($touscentraleunedate, array(INDUSTRIEL, ENCOURSREALISATION));
    $nbProjetFormation =  $manager->getSinglebyArray($touscentraleunedate, array(FORMATION, ENCOURSREALISATION));
    $nbProjetMaintenance =  $manager->getSinglebyArray($touscentraleunedate, array(MAINTENANCE, ENCOURSREALISATION));
    $nbProjetService =  $manager->getSinglebyArray($touscentraleunedate, array(SERVICE, ENCOURSREALISATION));
    if (empty($nbProjetMaintenance)) {
        $nbProjetMaintenance = 0;
    }
    if (empty($nbProjetService)) {
        $nbProjetService = 0;
    }
    if (empty($nbProjetFormation)) {
        $nbProjetFormation = 0;
    }
} elseif (IDTYPEUSER == ADMINLOCAL) {
    $nbProjetAcademique =            $manager->getSinglebyArray($dateCentrale, array(ACADEMIC,            IDCENTRALEUSER, ENCOURSREALISATION));
    $nbProjetAcademiquePartenariat = $manager->getSinglebyArray($dateCentrale, array(ACADEMICPARTENARIAT, IDCENTRALEUSER, ENCOURSREALISATION));
    $nbProjetIndustriel =            $manager->getSinglebyArray($dateCentrale, array(INDUSTRIEL,          IDCENTRALEUSER, ENCOURSREALISATION));
    $nbProjetFormation =             $manager->getSinglebyArray($dateCentrale, array(FORMATION,           IDCENTRALEUSER, ENCOURSREALISATION));
    $nbProjetMaintenance =           $manager->getSinglebyArray($dateCentrale, array(MAINTENANCE,         IDCENTRALEUSER, ENCOURSREALISATION));
    $nbProjetService =               $manager->getSinglebyArray($dateCentrale, array(SERVICE,             IDCENTRALEUSER, ENCOURSREALISATION));
    if (empty($nbProjetMaintenance)) {
        $nbProjetMaintenance = 0;
    }
    if (empty($nbProjetService)) {
        $nbProjetService =0;
    }
    if (empty($nbProjetFormation)) {
        $nbProjetFormation = 0;
    }
    if (empty($nbProjetIndustriel)) {
        $nbProjetIndustriel = 0;
    }
}
if (empty($nbProjetFormation)) {
    $nbProjetFormation = 0;
}
    $stringAcademic = '["' . TXT_ACADEMIQUE . '",' . $nbProjetAcademique . '],';
    $stringAcademicPartenariat = '["' . TXT_ACADEMICPARTENARIAT . '",' . $nbProjetAcademiquePartenariat . '],';
    
    $stringIndustriel = '["' . TXT_INDUSTRIEL . '",' . $nbProjetIndustriel . '],';
    if($nbProjetMaintenance!=0){
        $stringMaintenance = '["' . TXT_MAINTENANCE . '",' . $nbProjetMaintenance . '],';
    }else{
        $stringMaintenance="";
    }
    if($nbProjetService!=0){
        $stringService = '["' . TXT_SERVICE . '",' . $nbProjetService . '],';
    }else{
        $stringService ="";
    }
    if($nbProjetFormation!=0){
        $stringFormation = '["' . TXT_FORMATION . '",' . $nbProjetFormation . '],';
    }else{
        $stringFormation ="";
    }
    
    



$string = substr($stringAcademic . $stringAcademicPartenariat . $stringFormation . $stringIndustriel . $stringMaintenance . $stringService, 0, -1);
$nbtotalprojet = $nbProjetAcademique + $nbProjetAcademiquePartenariat + $nbProjetIndustriel + $nbProjetFormation + $nbProjetMaintenance + $nbProjetService;
$xasisTitle = "";
$subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
include_once 'commun/scriptPie.php';
BD::deconnecter();
