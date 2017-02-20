<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arrayRessource = $manager->getList("select libelleressource,idressource,libelleressourceen from ressource");
$totalressource = count($arrayRessource);
$string0 = '';
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeRessources'])) {
    $nbtotalressource = 0;
    for ($i = 0; $i < $totalressource; $i++) {
        $nbressource = $manager->getSinglebyArray("SELECT  count(r.idressource) FROM concerne co,projet p,ressourceprojet rp,ressource r WHERE co.idprojet_projet = p.idprojet AND rp.idprojet_projet = p.idprojet 
                AND rp.idressource_ressource = r.idressource AND  extract(year from p.dateprojet)<=? and r.idressource=?  and idstatutprojet_statutprojet=?  AND extract(year from p.dateprojet)>2012", array((date('Y') - 1), $arrayRessource[$i]['idressource'], ENCOURSREALISATION));
        if($lang=='fr'){
            $string0 .='["' . $arrayRessource[$i]['libelleressource'] . '",' . $nbressource . '],';
        }else{
            $string0 .='["' . $arrayRessource[$i]['libelleressourceen'] . '",' . $nbressource . '],';
        }
        $nbtotalressource+=$nbressource;
    }
    $subtitle = TXT_NBRESSOURCE . ': ' . $nbtotalressource;
    $title = TXT_REPARTITIONRESSOURCEPROJETENCOURSANNEE . (date('Y') - 1);
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeRessources'])) {
    $nbtotalressource = 0;
    for ($i = 0; $i < $totalressource; $i++) {
        $nbressource = $manager->getSinglebyArray("SELECT  count(r.idressource) FROM concerne co,projet p,ressourceprojet rp,ressource r WHERE co.idprojet_projet = p.idprojet AND rp.idprojet_projet = p.idprojet 
                AND rp.idressource_ressource = r.idressource AND  extract(year from p.dateprojet)<=? and r.idressource=?  and idstatutprojet_statutprojet=?  AND extract(year from p.dateprojet)>2012", array($_GET['anneeRessources'], $arrayRessource[$i]['idressource'], ENCOURSREALISATION));
        if($lang=='fr'){
            $string0 .='["' . $arrayRessource[$i]['libelleressource'] . '",' . $nbressource . '],';
        }else{
            $string0 .='["' . $arrayRessource[$i]['libelleressourceen'] . '",' . $nbressource . '],';
        }
        $nbtotalressource+=$nbressource;
    }
    $subtitle = TXT_NBRESSOURCE . ': ' . $nbtotalressource;
    $title = TXT_REPARTITIONRESSOURCEPROJETENCOURSANNEE . $_GET['anneeRessources'];
    $title = TXT_REPARTITIONRESSOURCEPROJETENCOURSANNEE . $_GET['anneeRessources'];
}


if (IDTYPEUSER == ADMINLOCAL) {
    $nbtotalressource = 0;
    for ($i = 0; $i < $totalressource; $i++) {
        $nbressource = $manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet "
                . "AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and idressource=? and idstatutprojet_statutprojet=?  "
                . "and extract(year from dateprojet)>2012 and extract(year from dateprojet)<=?", array(IDCENTRALEUSER, $arrayRessource[$i]['idressource'], ENCOURSREALISATION, (date('Y') - 1)));        
        if($lang=='fr'){
            $string0 .='["' . $arrayRessource[$i]['libelleressource'] . '",' . $nbressource . '],';
        }else{
            $string0 .='["' . $arrayRessource[$i]['libelleressourceen'] . '",' . $nbressource . '],';
        }
        
        $nbtotalressource+=$nbressource;
    }

    $subtitle = TXT_NBRESSOURCE . ': ' . $nbtotalressource;
    $title = TXT_REPARTITIONRESSOURCEPROJETENCOURSANNEE . (date('Y') - 1);
}
$string = substr($string0, 0, -1);
include_once 'commun/scriptPie.php';
