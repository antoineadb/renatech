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
$subtitle = TXT_CLICDETAIL;
include_once 'commun/scriptBar.php';
BD::deconnecter();
