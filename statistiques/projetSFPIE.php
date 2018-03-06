<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraySourcefinancement = $manager->getList("select libellesourcefinancement,idsourcefinancement,libellesourcefinancementen from sourcefinancement");
$nbsf = count($arraySourcefinancement);
$string0 = '';
$title = TXT_ORIGINESOURCEFINANCEMENT;
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeSF'])) {
    $nbtotalPie = 0;
    for ($i = 0; $i < $nbsf; $i++) {
        $nbsource = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co WHERE "
                . "ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet   "
                . "and s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=? AND EXTRACT(YEAR from p.datedebutprojet)=?", array($arraySourcefinancement[$i]['idsourcefinancement'], ENCOURSREALISATION, date('Y')));
        $nbtotalPie +=$nbsource;
        if($nbsource>0){
            if($lang=='fr'){
                $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",' . $nbsource . '],';
            }else {
                $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancementen'] . '",' . $nbsource . '],';
            }
        }else{
            $string0 .='["' . 'pas de donnée' . '",' .'1'. '],';
        }
    }
    
} 


if (IDTYPEUSER == ADMINLOCAL) {
    $nbtotalPie = 0;
    for ($i = 0; $i < $nbsf; $i++) {
        $nbsource = $manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps WHERE c.idprojet_projet = p.idprojet "
                . "AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and ps.idsourcefinancement_sourcefinancement=?  AND EXTRACT(YEAR from p.datedebutprojet)=?"
                . "AND c.idstatutprojet_statutprojet=?", 
                array(IDCENTRALEUSER, $arraySourcefinancement[$i]['idsourcefinancement'],date('Y'),ENCOURSREALISATION));
        if($nbsource>0){
            if($lang=='fr'){
                $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",' . $nbsource . '],';
            }else {
                $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancementen'] . '",' . $nbsource . '],';
            }
        }else {
            $string0 .='["' . 'pas de donnée' . '",' .'1'. '],';  
        }
        
         $nbtotalPie +=$nbsource;
    }   
}
$string = substr($string0, 0, -1);

$subtitle = TXT_NBSF . ': <b>' . $nbtotalPie . '</b>';
include_once 'commun/scriptPie.php';
