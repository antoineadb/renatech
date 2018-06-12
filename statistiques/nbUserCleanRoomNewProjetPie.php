<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$string2 = '';
$string3 = '';
 $title = TXT_CLEANROOMUSERNEWPROJECT;

if (IDTYPEUSER == ADMINNATIONNAL) {     
    $totalUser = $manager->getList("select idcentrale,libelle, count(mailaccueilcentrale	) as nb from tmpUserCleanRoom group by idcentrale,libelle order by idcentrale asc");
    $nbTotaluser = $manager->getSingle("select count(mailaccueilcentrale	) as nb from tmpUserCleanRoom");
    for ($i = 0; $i < count($totalUser); $i++) {
        $string0 .= '["' . $totalUser[$i]['libelle'] . '",' . $totalUser[$i]['nb']  . '],';
    }
}
if (IDTYPEUSER == ADMINLOCAL) {
    
    $totalUser = $manager->getList2("select annee,count(mailaccueilcentrale	) as nb from tmpUserCleanRoom where idcentrale=? group by annee order by annee asc", IDCENTRALEUSER);
    $nbTotaluser = $manager->getSingle2("select count(mailaccueilcentrale	) as nb from tmpUserCleanRoom where idcentrale=?",IDCENTRALEUSER);
    for ($i = 0; $i < count($totalUser); $i++) {
        $string0 .= '["' . $totalUser[$i]['annee'] . '",' . $totalUser[$i]['nb']  . '],';
    }   
} $string = substr($string0, 0, -1);
$subtitle =TXT_NOMBREBUSER.' '.$nbTotaluser;
include_once 'commun/scriptPie.php';
BD::deconnecter();
