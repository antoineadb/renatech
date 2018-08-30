<?php
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'class/Manager.php';
$db = BD::connecter();
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2013 order by year asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE  order by idcentrale asc ", IDAUTRECENTRALE);

//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                           CONSTRUCTION DE LA TABLE
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//periodetime = 1 jour
//periodetime = 2 mois
//periodetime = 3 année

/* < 1 an */
$donneeProjetInfUnAn = $manager->getListbyArray(""
        . "select distinct idprojet,dureeestime,datedebutprojet,periodestime from projet,concerne 
            where idprojet=idprojet_projet 
            and idstatutprojet_statutprojet=? 
            and periodestime =?  
            and dureeestime< ?
            and dureeestime!= ?
            and trashed != ?
            union
            select distinct idprojet,dureeestime,datedebutprojet,periodestime from projet,concerne 
            where idprojet=idprojet_projet 
            and idstatutprojet_statutprojet=?
            and periodestime =?
            and dureeestime< ?            
            and dureeestime!= ?            
            and trashed != ?
            and extract(year from datedebutprojet)>2013
            union
            select distinct idprojet,dureeestime,datedebutprojet,periodestime from projet,concerne 
            where idprojet=idprojet_projet 
            and idstatutprojet_statutprojet=?
            and periodestime =?
            and dureeestime< ?            
            and dureeestime!= ?
            and trashed != ?
            and extract(year from datedebutprojet)>2013
            ", array(ENCOURSREALISATION,1,365,0,TRUE,ENCOURSREALISATION,2,12,0,TRUE,ENCOURSREALISATION,3,1,0,TRUE));
//1 = jours
$nbdonneeProjetInfUnAn = count($donneeProjetInfUnAn);
//periodetime = 2 mois
$donneeProjetInfTroisAns = $manager->getListbyArray(""
        . "select distinct idprojet,dureeestime,datedebutprojet,periodestime from projet,concerne 
            where idprojet=idprojet_projet 
            and idstatutprojet_statutprojet=?
            and periodestime =?
            and dureeestime >=? and dureeestime <?
            and trashed != ?
            and extract(year from datedebutprojet)>2013
            union
            select distinct idprojet,dureeestime,datedebutprojet,periodestime from projet,concerne 
            where idprojet=idprojet_projet 
            and idstatutprojet_statutprojet=? 
            and periodestime =?
            and dureeestime >=? and dureeestime < ? 
            and trashed != ?
            and extract(year from datedebutprojet)>2013
            union
            select distinct idprojet,dureeestime,datedebutprojet,periodestime from projet,concerne 
            where idprojet=idprojet_projet 
            and idstatutprojet_statutprojet=? 
            and periodestime =?
            and dureeestime >=? and dureeestime <?
            and trashed != ?
            ", array(ENCOURSREALISATION,1,365,1095 ,TRUE,ENCOURSREALISATION,2,12,36,TRUE,ENCOURSREALISATION,3,1,3,TRUE));
$nbdonneeProjetInfTroisAns = count($donneeProjetInfTroisAns);
$donneeProjetSupTroisAns = $manager->getListbyArray(""
        . "select distinct idprojet,dureeestime,datedebutprojet,periodestime from projet,concerne 
            where idprojet=idprojet_projet 
            and idstatutprojet_statutprojet=?
            and periodestime =?
            and dureeestime >=?
            and trashed != ?
            and extract(year from datedebutprojet)>2013
            union
            select distinct idprojet,dureeestime,datedebutprojet,periodestime from projet,concerne 
            where idprojet=idprojet_projet 
            and idstatutprojet_statutprojet=?
            and periodestime =?  
            and dureeestime >= ?
            and trashed != ?
            and extract(year from datedebutprojet)>2013
            union
            select distinct idprojet,dureeestime,datedebutprojet,periodestime from projet,concerne 
            where idprojet=idprojet_projet 
            and idstatutprojet_statutprojet=?
            and periodestime =?  
            and dureeestime >=?            
            and trashed != ?
            and extract(year from datedebutprojet)>2013
            ", array(ENCOURSREALISATION,1,1095, TRUE,ENCOURSREALISATION,2,36, TRUE,ENCOURSREALISATION,3,3,TRUE));
$nbdonneeProjetSupTroisAns = count($donneeProjetSupTroisAns);
$manager->exeRequete("drop table if exists tmpprojet;");
$manager->exeRequete("create table tmpprojet as (select idprojet,dureeestime,datedebutprojet,periodestime,idcentrale_centrale  from projet,concerne where idprojet=idprojet_projet and idstatutprojet_statutprojet=8"
        . "and periodestime in(1,2,3) and dureeestime !=0 and extract(year from datedebutprojet)>2013 and trashed != TRUE)");

$manager->exeRequete("ALTER TABLE tmpprojet ADD COLUMN rang varchar(10);");
for ($i = 0; $i < $nbdonneeProjetInfUnAn; $i++) {   
    $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(1, $donneeProjetInfUnAn[$i]['idprojet']));
}
/* > 1 an < 3 ans */
for ($i = 0; $i < $nbdonneeProjetInfTroisAns; $i++) {
    $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(2, $donneeProjetInfTroisAns[$i]['idprojet']));
}
/* > 3 ans */
for ($i = 0; $i < $nbdonneeProjetSupTroisAns; $i++) {    
    $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(3, $donneeProjetSupTroisAns[$i]['idprojet']));
}
if (IDTYPEUSER == ADMINNATIONNAL) {
    $title = TXT_DUREEPROJETENCOURS;
    $subtitle = "";
    $xasisTitle = "";

    $nbProjet1 = $manager->getSingle2("select count(idprojet) from tmpprojet where rang=?", '1');
    $nbProjet2 = $manager->getSingle2("select count(idprojet) from tmpprojet where rang=?", '2');
    $nbProjet3 = $manager->getSingle2("select count(idprojet) from tmpprojet where rang=?", '3');
    
    
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
        $nbprojetRang1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=?", array(1, $centrale[1]));
        $nbprojetRang2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=?", array(2, $centrale[1]));
        $nbprojetRang3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=?", array(3, $centrale[1]));
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

    $nbProjet1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=?",array(1,IDCENTRALEUSER));
    $nbProjet2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=?",array(2,IDCENTRALEUSER));
    $nbProjet3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=?",array(3,IDCENTRALEUSER));    
    $serie1 = '{name: "' . TXT_1AN . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet1 . ',drilldown: "' . 'rang 1' . '"}]},';
    $serie2 = '{name: "' . TXT1_3ANS . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet2 . ',drilldown: "' . 'rang 2' . '"}]},';
    $serie3 = '{name: "' . TXT3ANS  . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet3 . ',drilldown: "' . 'rang 3' . '"}]},';

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

    $serieRang1 = "{id: '" . 'rang1' . "',name: '" . ' < 1 an' . "',data: [";
    $serieRang2 = "{id: '" . 'rang2' . "',name: '" . ' >= 1 an et < 3 ans' . "',data: [";
    $serieRang3 = "{id: '" . 'rang3' . "',name: '" . ' >= 3 ans' . "',data: [";
    
    $serieRang1 .= "]},";
    $serieRang2 .= "]},";
    $serieRang3 .= "]},";
    
    $serie0 = $serieRang1 . $serieRang2 . $serieRang3;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
}
include_once 'commun/scriptBar.php';
BD::deconnecter();