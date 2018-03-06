<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arrayRessource = $manager->getList("select libelleressource,idressource,libelleressourceen from ressource");
$ressources = $manager->getList("select libelleressource,idressource,libelleressourceen from ressource");
$totalressource = count($arrayRessource);
$string0 = '';
$title = TXT_ORIGINEPARTENAIREINDUSTRIEL;
if (IDTYPEUSER == ADMINNATIONNAL) {
    $nbtotalressource = 0;
    
    foreach ($ressources as $key => $ressource) {
        $nbressource = $manager->getSinglebyArray("SELECT count(rp.idressource_ressource) FROM ressourceprojet rp,projet p,concerne c,ressource r WHERE rp.idprojet_projet = p.idprojet "
                . "AND rp.idressource_ressource = r.idressource AND c.idprojet_projet = p.idprojet  AND rp.idressource_ressource=? and idstatutprojet_statutprojet=? AND c.idcentrale_centrale!=? and p.trashed !=?"
                , array($ressource['idressource'], ENCOURSREALISATION,IDCENTRALEAUTRE,TRUE));
        if($lang=='fr'){
            $string0 .='["' . $ressource['libelleressource'] . '",' . $nbressource . '],';
        }else{
            $string0 .='["' . $ressource['libelleressourceen'] . '",' . $nbressource . '],';
        }
        $nbtotalressource+=$nbressource;
    }   
    $subtitle = TXT_NBRESSOURCE . ': ' . $nbtotalressource;
    
}
if (IDTYPEUSER == ADMINLOCAL) {
    $nbtotalressource = 0;
    for ($i = 0; $i < $totalressource; $i++) {
        $nbressource = $manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet "
                . "AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and idressource=? and idstatutprojet_statutprojet=?  "
                . "", array(IDCENTRALEUSER, $arrayRessource[$i]['idressource'], ENCOURSREALISATION, ));        
        if($lang=='fr'){
            $string0 .='["' . $arrayRessource[$i]['libelleressource'] . '",' . $nbressource . '],';
        }else{
            $string0 .='["' . $arrayRessource[$i]['libelleressourceen'] . '",' . $nbressource . '],';
        }
        
        $nbtotalressource+=$nbressource;
    }

    $subtitle = TXT_NBRESSOURCE . ': ' . $nbtotalressource;
    
}
$string = substr($string0, 0, -1);
include_once 'commun/scriptPie.php';
