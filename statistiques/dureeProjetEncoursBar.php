<?php
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'class/Manager.php';
$db = BD::connecter();
$years = $manager->getList("select distinct EXTRACT(YEAR from datedebutprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? AND masquecentrale!=TRUE  order by idcentrale asc ", IDAUTRECENTRALE);

//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                           CONSTRUCTION DE LA TABLE
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//periodetime = 1 jour
//periodetime = 2 mois
//periodetime = 3 année

$manager->exeRequete("drop table if exists tmpprojet;");

 $manager->getRequete("
    CREATE TABLE tmpprojet AS (
            SELECT DISTINCT idprojet,dureeestime,datedebutprojet, 1 as rang, idcentrale_centrale
            FROM projet
            LEFT JOIN concerne ON  idprojet=idprojet_projet 
            WHERE 
            idstatutprojet_statutprojet=?
            AND dureeestime< ?           
            AND dureeestime!= ?            
            AND trashed != ?            
            AND extract(year from dateprojet)>=?            
            UNION
            SELECT DISTINCT idprojet,dureeestime,datedebutprojet,2 as rang, idcentrale_centrale
            FROM projet
            LEFT JOIN concerne ON  idprojet=idprojet_projet 
            WHERE
            idstatutprojet_statutprojet=?
            AND dureeestime >=? AND dureeestime <?
            AND trashed != ?            
            AND extract(year from dateprojet)>=?
            UNION
            SELECT DISTINCT idprojet,dureeestime,datedebutprojet, 3 as rang, idcentrale_centrale
            FROM projet
            LEFT JOIN concerne ON  idprojet=idprojet_projet 
            WHERE 
            idstatutprojet_statutprojet=?  
            AND dureeestime >=?            
            AND trashed != ?
            AND extract(year from dateprojet)>=?) 
", array(ENCOURSREALISATION,12,0,TRUE,2013,ENCOURSREALISATION,12,36,TRUE,2013,ENCOURSREALISATION,36,TRUE,2013));
/*
        SELECT count(idprojet) as nb, idcentrale_centrale,
        CASE 
            WHEN dureeestime < 12 AND dureeestime is not null THEN 1
            WHEN dureeestime >= 12  AND dureeestime < 36 THEN 2
            WHEN dureeestime >= 36  THEN 3
        END AS rang
        FROM projet
        LEFT JOIN concerne ON  idprojet=idprojet_projet 
        WHERE idstatutprojet_statutprojet=8
        AND dureeestime is not null
        AND trashed != TRUE            
        AND extract(year from dateprojet)>=2013   
        GROUP BY idcentrale_centrale,rang
        order by idcentrale_centrale ASC
 */
     
if (IDTYPEUSER == ADMINNATIONNAL) {
    $title = TXT_DUREEPROJETENCOURS;
    $subtitle = "";
    $xasisTitle = "";

    $nbProjet1 = $manager->getSingle2("select count(distinct idprojet) from tmpprojet where rang=?", 1);
    $nbProjet2 = $manager->getSingle2("select count(distinct idprojet) from tmpprojet where rang=?", 2);
    $nbProjet3 = $manager->getSingle2("select count(distinct idprojet) from tmpprojet where rang=?", 3);
    
    
    $serie1 = '{name: "' . TXT_1AN . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet1 . ',drilldown: "' . 'rang 1' . '"}]},';
    $serie2 = '{name: "' . TXT1_3ANS . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet2 . ',drilldown: "' . 'rang 2' . '"}]},';
    $serie3 = '{name: "' . TXT3ANS . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet3 . ',drilldown: "' . 'rang 3' . '"}]},';

    $serie01 = str_replace("},]}", "}]}", $serie1);
    $serie02 = str_replace("},]}", "}]}", $serie2);
    $serie03 = str_replace("},]}", "}]}", $serie3);
    $serie001 = str_replace("},]", "}]", $serie01);
    $serie002 = str_replace("},]", "}]", $serie02);
    $serie003 = str_replace("},]", "}]", $serie03);
    $serieX = substr($serie001 . $serie002 . $serie003, 0, -1);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                              FIN  DE LA DE L'ABCISSE DES X
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    

    $serieRang1 = "{id: '" . 'rang 1' . "',name: '" . ' < 1 an' . "',data: ["; 
    $serieRang2 = "{id: '" . 'rang 2' . "',name: '" . ' >= 1 an et < 3 ans' . "',data: [";
    $serieRang3 = "{id: '" . 'rang 3' . "',name: '" . ' >= 3 ans' . "',data: [";
    foreach ($centrales as $key => $centrale) {
        $nbprojetRang1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? AND idcentrale_centrale=?", array(1, $centrale[1]));
        $nbprojetRang2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? AND idcentrale_centrale=?", array(2, $centrale[1]));
        $nbprojetRang3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? AND idcentrale_centrale=?", array(3, $centrale[1]));
        $serieRang1 .="{name: '" . $centrale[0] .  "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetRang1 . " , drilldown: '" . 'rang 1' . $centrale[0] . "'},";
        $serieRang2 .="{name: '" . $centrale[0] .  "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetRang2 . " , drilldown: '" . 'rang 2' . $centrale[0] . "'},";
        $serieRang3 .="{name: '" . $centrale[0] .  "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetRang3 . " , drilldown: '" . 'rang 3' . $centrale[0] . "'},";
    }
        $serieRang1 .="]},";
        $serieRang2 .="]},";
        $serieRang3 .="]},";
    
    $serie0 = $serieRang1 . $serieRang2 . $serieRang3;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
}

if (IDTYPEUSER == ADMINLOCAL) {
    $title = TXT_DUREEPROJETENCOURS;
    $subtitle = "";
    $xasisTitle = "";

    $nbProjet1 = $manager->getSinglebyArray("select count(distinct idprojet) from tmpprojet where rang=? AND idcentrale_centrale=?",array(1,IDCENTRALEUSER));
    $nbProjet2 = $manager->getSinglebyArray("select count(distinct idprojet) from tmpprojet where rang=? AND idcentrale_centrale=?",array(2,IDCENTRALEUSER));
    $nbProjet3 = $manager->getSinglebyArray("select count(distinct idprojet) from tmpprojet where rang=? AND idcentrale_centrale=?",array(3,IDCENTRALEUSER));    
    $serie1 = '{name: "' . TXT_1AN . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet1 . ',drilldown: "' . 'rang1' . '"}]},';
    $serie2 = '{name: "' . TXT1_3ANS . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet2 . ',drilldown: "' . 'rang2' . '"}]},';
    $serie3 = '{name: "' . TXT3ANS  . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet3 . ',drilldown: "' . 'rang3' . '"}]},';

    $serie01 = str_replace("},]}", "}]}", $serie1);
    $serie02 = str_replace("},]}", "}]}", $serie2);
    $serie03 = str_replace("},]}", "}]}", $serie3);
    $serie001 = str_replace("},]", "}]", $serie01);
    $serie002 = str_replace("},]", "}]", $serie02);
    $serie003 = str_replace("},]", "}]", $serie03);
    $serieX = substr($serie001 . $serie002 . $serie003, 0, -1);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                              FIN  DE L'ABCISSE DES X
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    

    $serieRang1 = "{id: '" . '1' . "',name: '" . ' < 1 an' . "',data: [";
    $serieRang2 = "{id: '" . '2' . "',name: '" . ' >= 1 an et < 3 ans' . "',data: [";
    $serieRang3 = "{id: '" . '3' . "',name: '" . ' >= 3 ans' . "',data: [";
    
    $serieRang1 .= "]},";
    $serieRang2 .= "]},";
    $serieRang3 .= "]},";
    
    $serie0 = $serieRang1 . $serieRang2 . $serieRang3;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
}
include_once 'commun/scriptBar.php';
BD::deconnecter();