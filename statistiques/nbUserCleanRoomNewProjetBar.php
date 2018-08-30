<?php
include_once 'class/Manager.php';
$db = BD::connecter();

$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' and masquecentrale!=TRUE  order by idcentrale asc");
$nblibelle = count($arraylibellecentrale);
$datay = array();
$arraylibelle = array();
$string0 = '';
$string1 = '';

$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneeuserdate from utilisateur order by anneeuserdate asc");
$centrales = $manager->getList2("select libellecentrale from centrale where idcentrale!=? and masquecentrale!=TRUE  order by idcentrale asc", IDAUTRECENTRALE);
$arrayNbUser = array();
$xaxis = '';
$xasisTitle = TXT_NOMBREOCCURRENCE;
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2013 order by year asc");
$manager->exeRequete("drop table if exists tmpUserCleanRoom");

/*$manager->getRequete("create table tmpUserCleanRoom as (SELECT distinct on (LOWER(pac.mailaccueilcentrale)) pac.mailaccueilcentrale , ce.libellecentrale as libelle,ce.idcentrale, EXTRACT (YEAR FROM datedebutprojet) as annee
FROM projet p
LEFT JOIN concerne c ON  c.idprojet_projet = p.idprojet 
JOIN centrale ce ON cE.idcentrale = c.idcentrale_centrale
LEFT JOIN projetpersonneaccueilcentrale  ppac ON ppac.idprojet_projet = p.idprojet
LEFT JOIN personneaccueilcentrale pac ON pac.idpersonneaccueilcentrale = ppac.idpersonneaccueilcentrale_personneaccueilcentrale
WHERE   c.idcentrale_centrale != ? and EXTRACT (YEAR FROM datedebutprojet)>=2014
)", array(IDCENTRALEAUTRE));*/

$manager->getRequete("create table tmpUserCleanRoom as (SELECT annee,libelle,idcentrale,count(*)as nb FROM(
		SELECT distinct on(LOWER(pac.mailaccueilcentrale)) EXTRACT(YEAR FROM p.datedebutprojet) as annee, ce.libellecentrale as libelle,ce.idcentrale
                FROM projet p,personneaccueilcentrale pac,projetpersonneaccueilcentrale ppac,qualitedemandeuraca q,personnecentralequalite pcq,autresqualite aq,concerne c, centrale ce
                WHERE ppac.idprojet_projet = p.idprojet         
                AND ppac.idpersonneaccueilcentrale_personneaccueilcentrale = pac.idpersonneaccueilcentrale 
                AND q.idqualitedemandeuraca = pac.idqualitedemandeuraca_qualitedemandeuraca 
                AND pcq.idpersonnequalite = pac.idpersonnequalite 
                AND aq.idautresqualite = pac.idautresqualite 
                AND c.idprojet_projet = p.idprojet 
                AND c.idcentrale_centrale = ce.idcentrale
                AND c.idcentrale_centrale!=?	        
                AND EXTRACT(YEAR FROM datedebutprojet)>=2014
                ) as nb
                GROUP BY annee,libelle,idcentrale ORDER BY idcentrale
               )", array(IDCENTRALEAUTRE));
    
if (IDTYPEUSER == ADMINNATIONNAL) {
    $title = TXT_CLEANROOMUSERNEWPROJECT;
    $_S_serie = '';        
  $totalUser = $manager->getList("SELECT sum(nb) AS nb,libelle from tmpusercleanroom GROUP BY libelle,idcentrale ORDER BY idcentrale");                                    
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        $_S_serie .= '{name: "' . $totalUser[$i]['libelle'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $totalUser[$i]['nb'] . ',drilldown: "' . $totalUser[$i]['libelle'] . '"}]},';
    }$serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              AXE DES Y
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
    $_S_serie2 = '';
    foreach ($centrales as $key => $centrale) {
        $_S_serie2 .="{id: '" . $centrale[0] . "',name: '" . $centrale[0] . "',data: [";
        foreach ($years as $key => $year) {          
            $nbByYear = $manager->getSinglebyArray("select sum(nb) AS nb from tmpusercleanroom where annee=? and libelle =?", array($year[0], $centrale[0]));            
            if (empty($nbByYear)) {
                $nbByYear = 0;
            }
            $_S_serie2 .="{name: '" . $year[0] . "', y: " . $nbByYear . " , drilldown: '" . 'test' . "'},";            
        }$_S_serie2 .="]},";
    }$serie0 = str_replace("},]}", "}]}", $_S_serie2);
    $_S_serie3 = "";
    foreach ($centrales as $key => $centrale) {
        foreach ($years as $key => $year) {
            $_S_serie3.="{id: '" . $centrale[0] . $year[0] . "',name: '" . $centrale[0] . ' ' . $year[0] . "'" . ',';
            $_S_serie3.='},';
        }
    }$serie_0 = $serie0 . $_S_serie3;
    
    $serieY = substr($serie_0, 0, -1);
}
if (IDTYPEUSER == ADMINLOCAL) {
    $title = TXT_CLEANROOMUSERNEWPROJECT;
    $_S_serie = '';    
    $totalUser = $manager->getList2("SELECT sum(nb) AS nb,libelle,annee,idcentrale from tmpusercleanroom WHERE idcentrale =? GROUP BY libelle,idcentrale,annee ORDER BY annee asc", IDCENTRALEUSER);
    
    $nb=0;
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }        
        $nb =$totalUser[$i]['nb'];
        $_S_serie .= '{name: "' . $totalUser[$i]['annee'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nb . ',drilldown: "' .  'test' . '"}]},';
    }$serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              AXE DES Y
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
    $totalUser = $manager->getList2("select annee ,count(mailaccueilcentrale) as nb from tmpUserCleanRoom where idcentrale=? group by annee order by annee asc", IDCENTRALEUSER);
    $nbTotaluser = $manager->getSingle2("select count(mailaccueilcentrale) as nb  from tmpUserCleanRoom where idcentrale=?",IDCENTRALEUSER);
    $_S_serie3 = "";
    for ($i = 0; $i < count($totalUser); $i++) {
        $_S_serie3 .="{id: '" . $totalUser[$i]['annee']  . "',name: '" . LIBELLECENTRALEUSER . ' ' . $totalUser[$i]['nb']  . "'" . ',data: []},';
    }
    $serieY = substr($_S_serie3, 0, -1);
}
$subtitle = TXT_CLICDETAIL;
include_once 'commun/scriptBar.php';
BD::deconnecter();
