<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$string2 = '';
$string3 = '';

if (IDTYPEUSER == ADMINNATIONNAL) { 
    $title = TXT_CLEANROOMUSERRUNNINGPROJECT.' '.date('Y');
    $totalUser = $manager->getList2("select idcentrale,libellecentrale, count(nb) as nb from tmpUserCleanRoom where annee<=? group by idcentrale,libellecentrale order by idcentrale asc",date('Y'));
    $nbTotaluser = $manager->getSingle2("select count(nb) as nb from tmpUserCleanRoom where annee<=?",date('Y'));
    $nbTotaluser2013 = $manager->getSingle("select count(nb) as nb from tmpUserCleanRoom where annee <=2013");
    for ($i = 0; $i < count($totalUser); $i++) {
        $string0 .= '["' . $totalUser[$i]['libellecentrale'] . '",' . $totalUser[$i]['nb']  . '],';
    }
}
$string = substr($string0, 0, -1);
$subtitle =TXT_NOMBREBUSER.' '.$nbTotaluser;
include_once 'commun/scriptPie.php';
BD::deconnecter();
