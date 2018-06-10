<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$statutProjets = $manager->getList2("select libellestatutprojet,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet  asc",TRANSFERERCENTRALE);
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneedatecreation from utilisateur order by anneedatecreation asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL) {
    $serie = "";
    foreach ($centrales as $key => $centrale) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? and  trashed != ?"
                    . "and idstatutprojet_statutprojet=? and  EXTRACT(YEAR from dateprojet)>2012   AND idcentrale!=?",array($centrale[1],TRUE,ENCOURSREALISATION,IDCENTRALEAUTRE));
            $serie .= '{name: "' . $centrale[0] . '", data: [{name: "' . TXT_DETAILS .
                    '",y: ' . $nbProjet . ',drilldown: "' . $centrale[0] . '"}]},';

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serieY="";
    $serieY = str_replace("},]}", "}]}", $serieY);
    $subtitle = TXT_CLICDETAIL;
    $title = TXT_NBRUNNINGPROJET;
    $xasisTitle = TXT_NOMBREOCCURRENCE;
} 

if (IDTYPEUSER == ADMINLOCAL) {
    $serie = "";
    foreach ($years as $key => $year) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? "
                    . "and extract(year from dateprojet)=? and  trashed != ? and idstatutprojet_statutprojet=?", array(IDCENTRALEUSER,$year[0],TRUE,ENCOURSREALISATION));            
            if($nbProjet==0){$nbProjet=0;}
            $serie .= '{name: "' . $year[0] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet . ',drilldown: "' . $year[0] . '"}]},';
            

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
$serie3 = '';
foreach ($years as $key => $year) {
        $nbByYear = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? "
                . "and extract(year from dateprojet)<=? and  trashed != ? and idstatutprojet_statutprojet=? ", array(IDCENTRALEUSER,$year[0],TRUE,ENCOURSREALISATION));
        if (empty($nbByYear)) {$nbByYear = 0;}
        if($year[0]==2013){
            $serie3.="{id: '" . $year[0] . "',name: '" . TXT_INFERIEUR2013 . "'" . ',data: [';
        }else{
            $serie3.="{id: '" . $year[0] . "',name: '" . $year[0] . "'" . ',data: [';
        }
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearByMonth = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,statutprojet WHERE idprojet_projet = idprojet AND idstatutprojet_statutprojet = idstatutprojet "
                    . "AND idcentrale_centrale = ? AND  extract(year from dateprojet)=? and idstatutprojet=? and  trashed != ? and extract(month from dateprojet)=?", 
                    array(IDCENTRALEUSER,$year[0],ENCOURSREALISATION,TRUE, $mois));
            if (empty($nbByYearByMonth)) {$nbByYearByMonth = 0;}
            $serie3.= '["' . showMonth($mois, $lang) . '",' . $nbByYearByMonth . '],';
        }$serie3.=']},';       
    }
$serieY0 = str_replace("},]}", "}]}", $serie3);
$serieY1 =  str_replace("],]", "]]",$serieY0);
$serieY=  substr($serieY1, 0,-1);
$subtitle = TXT_CLICDETAIL;
$title = TXT_NBRUNNINGPROJET;
$xasisTitle = TXT_PROJETDATESTATUT;
}
include_once 'commun/scriptBar.php';
       