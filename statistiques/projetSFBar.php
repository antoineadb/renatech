<?php
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$sourcefinancements = $manager->getList("select idsourcefinancement,libellesourcefinancement,idsourcefinancement,libellesourcefinancementen from sourcefinancement");
$nbsf = count($sourcefinancements);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$title = TXT_ORIGINESOURCEFINANCEMENT;
if (IDTYPEUSER == ADMINNATIONNAL) {
    $serie="";
    foreach ($sourcefinancements as $key => $sourcefinancement) {        
        $nbBySF = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                . " and s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=?", array(  $sourcefinancement[0], ENCOURSREALISATION));
        if (empty($nbBySF)) {$nbBySF = 0;}
        if ($lang == 'fr') {
             $serie .= '{name: "' . $sourcefinancement[1] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbBySF . ',drilldown: "' . $sourcefinancement[0] . '"},]},';  
        } else {
             $serie .= '{name: "' . $sourcefinancement[2] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbBySF . ',drilldown: "' . $sourcefinancement[2] . '"},]},';  
        }       
    }
    $serieX = substr($serie, 0, -1);
    $centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
   
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie02 = '';
    foreach ($sourcefinancements as $key => $value) {
        $serie02 .="{id: '" . $value['idsourcefinancement'] . "',name: '" . $value['libellesourcefinancement']." ',data: [";
        foreach ($centrales as $key => $centrale) {
            $nbBySF = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                . " WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                . " and s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=? AND co.idcentrale_centrale=?",
                    array($value['idsourcefinancement'], ENCOURSREALISATION,$centrale[1]));
            if (empty($nbBySF)) {$nbBySF = 0;}
            $serie02 .="{name: '" . $centrale[0] .  "',color:'". couleurGraphLib($centrale[0])."', y: ". $nbBySF . " , drilldown: '" . $centrale[0] . $value['idsourcefinancement'] . "'},";
            
        }$serie02 .="]},";  
    }
    
    $serie2 = str_replace("},]}", "}]}", $serie02);

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    $serie03 = substr($serie2, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);  //  echo $serieY;die;
    
    $subtitle = '';
    
}
if (IDTYPEUSER == ADMINLOCAL) {
     $serie="";
    foreach ($sourcefinancements as $key => $sourcefinancement) {        
        $nbBySF = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                . " and s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=? AND co.idcentrale_centrale=?",
                array($sourcefinancement[0], ENCOURSREALISATION,IDCENTRALEUSER));
        if (empty($nbBySF)) {$nbBySF = 0;}
        if ($lang == 'fr') {
             $serie .= '{name: "' . $sourcefinancement[1] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbBySF . ',drilldown: "' . $sourcefinancement[0] . '"},]},';  
        } else {
             $serie .= '{name: "' . $sourcefinancement[2] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbBySF . ',drilldown: "' . $sourcefinancement[2] . '"},]},';  
        }       
    }
    $serieX = substr($serie, 0, -1);
    $serie02 = '';
   $serieY = str_replace("],]}", "]]}", $serie02);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    
    
    
    
    $subtitle = '';
    
}

$xasisTitle = TXT_NOMBREOCCURRENCE;
include_once 'commun/scriptBar.php';
