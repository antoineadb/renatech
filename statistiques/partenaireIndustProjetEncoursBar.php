<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';

$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
$origines = $manager->getList2("
        SELECT count(libelletypepartenairefr),tp.libelletypepartenairefr,tp.idtypepartenaire FROM partenaireprojet p 
        LEFT JOIN projetpartenaire pp  ON pp.idpartenaire_partenaireprojet= p.idpartenaire
        LEFT JOIN concerne c ON c.idprojet_projet = pp.idprojet_projet 
        LEFT JOIN typepartenaire tp ON tp.idtypepartenaire =  pp.idtypepartenaire_typepartenaire	
        WHERE c.idstatutprojet_statutprojet = ?
        GROUP BY tp.libelletypepartenairefr,tp.idtypepartenaire",ENCOURSREALISATION); 

$title = TXT_ORIGINEPARTENAIREINDUSTRIEL;  
if (IDTYPEUSER == ADMINNATIONNAL) {
    $serie = "";
    foreach ($origines as $key => $origine) {
        if( $origine['idtypepartenaire']!=null){
            $serie .= '{name: "' . $origine['libelletypepartenairefr'] . '", data: [{name: "' . TXT_DETAILS . '",y: '. $origine['count'] . ',drilldown: "' . $origine['idtypepartenaire']  . '"}]},';     
        }
    }
    $serie1 = str_replace("},]}", "}]}", $serie);
    $serie01 = str_replace("},]", "}]", $serie1);
    $serieX = substr($serie01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie02 = '';
    foreach ($origines as $key => $origine) {
        $serie02 .="{id: '" . $origine['idtypepartenaire'] . "',name: '" . $origine['libelletypepartenairefr']." ',data: [";
        foreach ($centrales as $key => $centrale) {
          $nb =   $manager->getSinglebyArray("
                SELECT count(libelletypepartenairefr) FROM partenaireprojet p 
                LEFT JOIN projetpartenaire pp  ON pp.idpartenaire_partenaireprojet= p.idpartenaire
                LEFT JOIN concerne c ON c.idprojet_projet = pp.idprojet_projet 
                LEFT JOIN typepartenaire tp ON tp.idtypepartenaire =  pp.idtypepartenaire_typepartenaire	
                WHERE c.idstatutprojet_statutprojet = ? AND c.idcentrale_centrale=? AND tp.idtypepartenaire =?
                ",array(ENCOURSREALISATION,$centrale[1],$origine['idtypepartenaire'])); 
         
            if( $origine['idtypepartenaire']!=null ){
                $serie02 .="{name: '" . $centrale[0] .  "',color:'". couleurGraphLib($centrale[0])."', y: ". $nb . " , drilldown: '" . $centrale[0] . $origine['idtypepartenaire']  . "'},";
            }
                        
        }$serie02 .="]},";
    }
    
    $serie2 = str_replace("},]}", "}]}", $serie02);

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $serie3 = "";
    $serie03 = substr($serie2 . $serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;
    
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
  
}$xasisTitle = TXT_NOMBREOCCURRENCE;
include_once 'commun/scriptBar.php';
