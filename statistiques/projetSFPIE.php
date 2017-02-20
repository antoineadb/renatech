<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraySourcefinancement = $manager->getList("select libellesourcefinancement,idsourcefinancement,libellesourcefinancementen from sourcefinancement");
$nbsf = count($arraySourcefinancement);
$string0 = '';

if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeSF'])) {
    $nbtotalPie = 0;
    for ($i = 0; $i < $nbsf; $i++) {
        $nbsource = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co WHERE "
                . "ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet AND EXTRACT(YEAR from p.dateprojet)>2012  "
                . "and s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=? AND EXTRACT(YEAR from p.dateprojet)<=?", array($arraySourcefinancement[$i]['idsourcefinancement'], ENCOURSREALISATION, (date('Y') - 1)));
        $nbtotalPie +=$nbsource;
        if($lang=='fr'){
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",' . $nbsource . '],';
        }else {
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancementen'] . '",' . $nbsource . '],';
        }
    }
    $title = TXT_ORIGINESOURCEFINANCEMENTANNEE . (date('Y') - 1);
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeSF'])) {
    $nbtotalPie = 0;
    for ($i = 0; $i < $nbsf; $i++) {
        $nbsource = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co WHERE "
                . "ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet AND EXTRACT(YEAR from p.dateprojet)>2012  "
                . "and s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=? AND EXTRACT(YEAR from p.dateprojet)<=?", array($arraySourcefinancement[$i]['idsourcefinancement'], ENCOURSREALISATION, $_GET['anneeSF']));
        $nbtotalPie +=$nbsource;
        if($lang=='fr'){
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",' . $nbsource . '],';
        }else {
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancementen'] . '",' . $nbsource . '],';
        }
    }
    $title = TXT_ORIGINESOURCEFINANCEMENTANNEE . $_GET['anneeSF'];
}


if (IDTYPEUSER == ADMINLOCAL) {
    $nbtotalPie = 0;
    for ($i = 0; $i < $nbsf; $i++) {
        $nbsource = $manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps WHERE c.idprojet_projet = p.idprojet "
                . "AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and ps.idsourcefinancement_sourcefinancement=? AND EXTRACT(YEAR from p.dateprojet)>2012 AND EXTRACT(YEAR from p.dateprojet)<=?"
                . "AND c.idstatutprojet_statutprojet=?", 
                array(IDCENTRALEUSER, $arraySourcefinancement[$i]['idsourcefinancement'],(date('Y') - 1),ENCOURSREALISATION));        
        if($lang=='fr'){
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",' . $nbsource . '],';
        }else {
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancementen'] . '",' . $nbsource . '],';
        }
         $nbtotalPie +=$nbsource;
    }
    $title = TXT_ORIGINESOURCEFINANCEMENTANNEE . (date('Y') - 1);
}
$string = substr($string0, 0, -1);

$subtitle = TXT_NBSF . ': <b>' . $nbtotalPie . '</b>';
include_once 'commun/scriptPie.php';
