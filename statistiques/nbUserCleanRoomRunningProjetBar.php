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
$manager->exeRequete("drop table if exists tmpUserCleanRoom");
$manager->getRequete("create table tmpUserCleanRoom as (SELECT distinct on(LOWER(pac.mailaccueilcentrale)) count(LOWER(pac.mailaccueilcentrale)) as nb, ce.idcentrale,ce.libellecentrale,extract(year from datedebutprojet) as annee,
    extract(month from datedebutprojet) as mois FROM personneaccueilcentrale pac,projetpersonneaccueilcentrale pr,projet p,centrale ce,concerne co, autresqualite aq,qualitedemandeuraca q
            WHERE pr.idpersonneaccueilcentrale_personneaccueilcentrale = pac.idpersonneaccueilcentrale 
            AND p.idprojet = pr.idprojet_projet 
            AND co.idprojet_projet = p.idprojet 
            AND co.idcentrale_centrale = ce.idcentrale 
            AND q.idqualitedemandeuraca = pac.idqualitedemandeuraca_qualitedemandeuraca 
            AND aq.idautresqualite = pac.idautresqualite
            and idstatutprojet_statutprojet in(?,?,?)
            AND ce.idcentrale!=?
            group by pac.mailaccueilcentrale,ce.idcentrale,ce.libellecentrale,annee,mois)", array(ENCOURSREALISATION,ENATTENTE,ENCOURSANALYSE,IDCENTRALEAUTRE));
if (IDTYPEUSER == ADMINNATIONNAL) {
    $title = TXT_CLEANROOMUSERRUNNINGPROJECT.' '.date('Y');
    $_S_serie = '';
    $totalUser = $manager->getList("select idcentrale,libellecentrale, count(nb) as nb from tmpUserCleanRoom group by idcentrale,libellecentrale order by idcentrale asc");
    
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        $_S_serie .= '{name: "' . $totalUser[$i]['libellecentrale'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $totalUser[$i]['nb'] . ',drilldown: "' . $totalUser[$i]['libellecentrale'] . '"}]},';
    }$serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              AXE DES Y
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
    $serie_0 = "";    
    $serieY = substr($serie_0, 0, -1);
}
if (IDTYPEUSER == ADMINLOCAL) {
    $title = TXT_CLEANROOMUSERRUNNINGPROJECT.' '.date('Y');
    $_S_serie = '';
    $totalUser = $manager->getList2("select annee,count(nb) as nb from tmpUserCleanRoom where libellecentrale=? and annee>2012 group by annee order by annee asc", LIBELLECENTRALEUSER);
    $nb=0;
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        if($totalUser[$i]['annee']==2013){
            $nb = $manager->getSingle2("select count(nb) as nb from tmpUserCleanRoom where libellecentrale=? and annee<=2013", LIBELLECENTRALEUSER);
        }else{
            $nb+=$totalUser[$i]['nb'];
        }
        if($totalUser[$i]['annee']==2013){
            $_S_serie .= '{name: "' . TXT_INFERIEUR2013 . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nb . ',drilldown: "' . LIBELLECENTRALEUSER . $totalUser[$i]['annee'] . '"}]},';
        }else{
            $_S_serie .= '{name: "' . $totalUser[$i]['annee'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nb . ',drilldown: "' . LIBELLECENTRALEUSER . $totalUser[$i]['annee'] . '"}]},';
        }
    }$serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              AXE DES Y
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $nbUsercentraleYear = $manager->getList2("select annee,mois,count(nb) as nb from tmpUserCleanRoom where libellecentrale=? group by annee,mois order by annee asc", LIBELLECENTRALEUSER);
    $_S_serie3 = "";
    foreach ($nbUsercentraleYear as $key => $year) {
        $_S_serie3 .="{id: '" . LIBELLECENTRALEUSER . $year[0] . "',name: '" . LIBELLECENTRALEUSER . ' ' . $year[0] . "'" . ',data: [';
        for ($mois = 1; $mois < 13; $mois++) {
            $nbUsercentraleYearMonth = $manager->getSinglebyArray("select count(nb) as nb from tmpUserCleanRoom where annee=?  and mois=? and libellecentrale=?", array($year[0], $mois, LIBELLECENTRALEUSER));
            $_S_serie3.= "['" . showMonth($mois,$lang) . "'," . $nbUsercentraleYearMonth . "],";
        }$_S_serie3.=']},';
    }
    $serieY = substr($_S_serie3, 0, -1);
}

$subtitle = TXT_CLICDETAIL;
include_once 'commun/scriptBar.php';
BD::deconnecter();
