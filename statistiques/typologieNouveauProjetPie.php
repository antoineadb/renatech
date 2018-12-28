<?php

include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?"
        . "and idcentrale_centrale!=? and trashed !=? AND EXTRACT(YEAR from dateprojet)<=? and EXTRACT(YEAR from dateprojet)>=?";
$tousdateunecentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=? "
        . " and idcentrale_centrale!=? and trashed !=? AND EXTRACT(YEAR from dateprojet)<=? and EXTRACT(YEAR from dateprojet)>=?";
//TRAITEMENT PAR ANNEE
if (IDTYPEUSER == ADMINNATIONNAL) {
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array(ACADEMIC,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraleunedate, array(ACADEMICPARTENARIAT,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraleunedate, array(INDUSTRIEL,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $nbformation = (int)$manager->getSinglebyArray($touscentraleunedate, array(FORMATION,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart)); if(empty($nbformation)){$nbformation=0;}
    $service = $manager->getSinglebyArray($touscentraletoutesdate,array(SERVICE,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));if(empty($service)){$service=0;}
    $maintenance = $manager->getSinglebyArray($touscentraletoutesdate,array(MAINTENANCE,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));if(empty($maintenance)){$maintenance=0;}
    $nbNonDefini = $manager->getSinglebyArray($touscentraleunedate, array(1,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
   if ($lang == 'fr') {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart;
        } else {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart . " à " . $anneeFin;
        }
    } else {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart;
        } else {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart . " to " . $anneeFin;
        }
    }
}

if (IDTYPEUSER == ADMINLOCAL) {    
    $nbprojetAcademique = $manager->getSinglebyArray($tousdateunecentrale, array(ACADEMIC, IDCENTRALEUSER,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($tousdateunecentrale, array(ACADEMICPARTENARIAT, IDCENTRALEUSER,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $nbprojetindustriel = $manager->getSinglebyArray($tousdateunecentrale, array(INDUSTRIEL, IDCENTRALEUSER,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));;
    $nbformation = $manager->getSinglebyArray($tousdateunecentrale, array(FORMATION, IDCENTRALEUSER,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $nbNonDefini = $manager->getSinglebyArray($tousdateunecentrale, array(1, IDCENTRALEUSER,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $service = $manager->getSinglebyArray($touscentraletoutesdate,array(SERVICE,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));if(empty($service)){$service=0;}
    $maintenance = $manager->getSinglebyArray($touscentraletoutesdate,array(MAINTENANCE,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));if(empty($maintenance)){$maintenance=0;}
    if ($lang == 'fr') {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart;
        } else {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart . " à " . $anneeFin;
        }
    } else {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart;
        } else {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart . " to " . $anneeFin;
        }
    }
} 
if(empty($nbformation)){$nbformation=0;}

if($nbprojetAcademique!=0){
    $string0 = '["' . TXT_ACADEMIQUE . '",' . $nbprojetAcademique . '],';
}else{
    $string0 = '';
}
if($nbprojetAcademiquepartenariat!=0){
    $string3 = '["' . TXT_ACADEMICPARTENARIAT . '",' . $nbprojetAcademiquepartenariat  . '],';
}else{
    $string3 = '';
}

if($nbformation!=0){
    $string4 = '["' . TXT_FORMATION . '",' . $nbformation . '],';    
}else{
    $string4 = '';
}
if($nbprojetindustriel!=0){
    $string5 = '["' . TXT_INDUSTRIEL . '",' . $nbprojetindustriel  . '],';
}else{
    $string5 = '';
}
if($service!=0){
    $string6 = '["' . TXT_SERVICE . '",' . $service  . '],';
}else{
    $string6 = '';
}
if($maintenance!=0){
    $string7 = '["' . TXT_MAINTENANCE . '",' . $maintenance  . '],';
}else{
    $string7 = '';
}
if($nbNonDefini!=0){
    $string8 = '["' . "Non défini" . '",' . $nbNonDefini  . '],';
}else{
    $string8 = '';
}
$string = substr($string0 . $string3 . $string4 . $string5 .$string6. $string7 . $string8, 0, -1);
$nbtotalprojet = $nbprojetAcademique + $nbprojetAcademiquepartenariat + $nbprojetindustriel + $nbformation + $nbNonDefini+$service+$maintenance;
$xasisTitle = "";
$subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
include_once 'commun/scriptPie.php';
BD::deconnecter();
