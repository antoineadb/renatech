<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$ressources = $manager->getList("select libelleressource,idressource,libelleressourceen from ressource");

$idressources = $manager->getList("select idressource from ressource order by idressource asc");
$arrayIdressource =array();
foreach ($idressources as $key => $value) {
    array_push($arrayIdressource, $value[0]);
}

$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where EXTRACT(YEAR from dateprojet)>2013 order by year asc");
    
if (IDTYPEUSER == ADMINNATIONNAL) {
    $serie = "";
    foreach ($ressources as $key => $ressource) {
        $nbressource = $manager->getSinglebyArray("SELECT count(rp.idressource_ressource) FROM ressourceprojet rp,projet p,concerne c,ressource r WHERE rp.idprojet_projet = p.idprojet "
                . "AND rp.idressource_ressource = r.idressource AND c.idprojet_projet = p.idprojet  AND rp.idressource_ressource=? and idstatutprojet_statutprojet=? AND c.idcentrale_centrale!=? and p.trashed !=?"
                , array($ressource['idressource'], ENCOURSREALISATION,IDCENTRALEAUTRE,TRUE));
     $serie .= '{name: "' . $ressource['libelleressource'] . '", data: [{name: "' . TXT_DETAILS . '",y: '
             . $nbressource . ',drilldown: "' . $ressource['idressource']  . '"}]},';
    }

    $serie1 = str_replace("},]}", "}]}", $serie);
    $serie01 = str_replace("},]", "}]", $serie1);
    $serieX = substr($serie01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie02 = '';
    foreach ($ressources as $key => $value) {
        $serie02 .="{id: '" . $value['idressource'] . "',name: '" . $value['libelleressource']." ',data: [";
        foreach ($centrales as $key => $centrale) {
            $nbByYear = $manager->getSinglebyArray("SELECT  count(r.idressource) FROM concerne co,projet p,ressourceprojet rp,ressource r WHERE co.idprojet_projet = p.idprojet AND rp.idprojet_projet = p.idprojet "
                    . "AND rp.idressource_ressource = r.idressource  AND r.idressource =? AND co.idcentrale_centrale=?  AND idstatutprojet_statutprojet=? and co.idcentrale_centrale!=?  "
                    . "AND p.trashed!=? ",array($value['idressource'] , $centrale[1], ENCOURSREALISATION,IDCENTRALEAUTRE,TRUE));
            $serie02 .="{name: '" . $centrale[0] .  "',color:'". couleurGraphLib($centrale[0])."', y: ". $nbByYear . " , drilldown: '" . $centrale[0] . $value['idressource'] . "'},";
                        
        }$serie02 .="]},";
    }
    
    $serie2 = str_replace("},]}", "}]}", $serie02);

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $serie3 = "";
    $serie03 = substr($serie2 . $serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;
    $title = TXT_REPARTITIONRESSOURCEPROJETENCOURS;
}
if (IDTYPEUSER == ADMINLOCAL) {
    $serie = "";
    foreach ($ressources as $key => $ressource) {
        $nbressource = $manager->getSinglebyArray("SELECT count(rp.idressource_ressource) FROM ressourceprojet rp,projet p,concerne c,ressource r WHERE rp.idprojet_projet = p.idprojet "
                . "AND rp.idressource_ressource = r.idressource AND c.idprojet_projet = p.idprojet  AND rp.idressource_ressource=? and idstatutprojet_statutprojet=? AND c.idcentrale_centrale=? and p.trashed !=?"
                , array($ressource['idressource'], ENCOURSREALISATION,IDCENTRALEUSER,TRUE));     
        if ($nbressource == 0) {$nbressource = 0;}
        $serie .= '{name: "' . $ressource['libelleressource'] . '", data: [{name: "' . TXT_DETAILS . '",y: '. $nbressource . ',drilldown: "' . $ressource['idressource']  . '"}]},';
        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
    
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie3 = '';    
    $serieY0 = str_replace("},]}", "}]}", $serie3);
    $serieY1 = str_replace("],]", "]]", $serieY0);
    $serieY = substr($serieY1, 0, -1);

    $subtitle = TXT_CLICDETAIL;
    $title = TXT_REPARTITIONRESSOURCEPROJETENCOURS;
}$xasisTitle = TXT_NOMBREOCCURRENCE;
include_once 'commun/scriptBar.php';
