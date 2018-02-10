<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$string0 = '';
$string1 = '';
$string2 = '';
$string3 = '';
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneePartenaireHorsRenatech'])) {
    $title=TXT_PARTHORSRENATECHDATE.(date('Y')-1);
    $nbTotaluser =$manager->getSingle2("select sum(nb)from tmpUserpartenairePorteurDate where extract(year from datedebutprojet)<=?",(date('Y')-1));
    $totalUser = $manager->getList2("select sum(nb) as nb,libellecentrale,idcentrale from tmpUserpartenairePorteurDate where extract(year from datedebutprojet)<=? group by idcentrale,libellecentrale order by idcentrale asc",(date('Y')-1));
    for ($i = 0; $i < count($totalUser); $i++) {
        $string0 .= '["' . $totalUser[$i]['libellecentrale'] . '",' . $totalUser[$i]['nb'] . '],';
    }
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneePartenaireHorsRenatech'])) {
    $title=TXT_PARTHORSRENATECHDATE.$_GET['anneePartenaireHorsRenatech'];
    $nbTotaluser =$manager->getSingle2("select sum(nb)from tmpUserpartenairePorteurDate where extract(year from datedebutprojet)<=?",$_GET['anneePartenaireHorsRenatech']);
    $totalUser = $manager->getList2("select sum(nb) as nb,libellecentrale,idcentrale from tmpUserpartenairePorteurDate where extract(year from datedebutprojet)<=? group by idcentrale,libellecentrale order by idcentrale asc",$_GET['anneePartenaireHorsRenatech']);
    for ($i = 0; $i < count($totalUser); $i++) {
        $string0 .= '["' . $totalUser[$i]['libellecentrale'] . '",' . $totalUser[$i]['nb']. '],';
    }
}
if (IDTYPEUSER == ADMINLOCAL) {
    $title=TXT_PARTHORSRENATECH;
    $years = $manager->getList("select distinct EXTRACT(YEAR from datecreation)as year from utilisateur order by year asc");
    $nbTotaluser =$manager->getSingle("select sum(nb)from tmpUserpartenairePorteurDate");
        foreach ($years as $key => $year) {
        $nbByYear = $manager->getSingle2("select sum(nb) from tmpUserpartenairePorteurDate where EXTRACT(YEAR from datedebutprojet)<=?", $year[0]);
        $string0 .= '["' . $year[0] . '",' .$nbByYear . '],';
    }
}
$string = substr($string0, 0, -1);
$subtitle = TXT_NOMBREBUSER . ' ' . $nbTotaluser;
include_once 'commun/scriptPie.php';
BD::deconnecter();
