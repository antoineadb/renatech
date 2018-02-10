<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$string2 = '';
$string3 = '';
 

if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeUserCleanRoom'])) { 
    $title = TXT_CLEANROOMUSERNEWPROJECTDATE.(date('Y')-1);
    $totalUser = $manager->getList2("select idcentrale,libellecentrale, sum(nb) as nb from tmpUserCleanRoom where annee<=? group by idcentrale,libellecentrale order by idcentrale asc",(date('Y')-1));
    $nbTotaluser = $manager->getSingle2("select sum(nb) as nb from tmpUserCleanRoom where annee<=?",(date('Y')-1));    
    for ($i = 0; $i < count($totalUser); $i++) {
        $string0 .= '["' . $totalUser[$i]['libellecentrale'] . '",' . $totalUser[$i]['nb']  . '],';
    }
}elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeUserCleanRoom'])) {
    $title = TXT_CLEANROOMUSERNEWPROJECTDATE.$_GET['anneeUserCleanRoom'];
    $totalUser = $manager->getList2("select idcentrale,libellecentrale, sum(nb) as nb from tmpUserCleanRoom where annee<=? group by idcentrale, libellecentrale order by idcentrale asc",$_GET['anneeUserCleanRoom']);
    $nbTotaluser = $manager->getSingle2("select sum(nb) as nb from tmpUserCleanRoom where annee<=?",$_GET['anneeUserCleanRoom']);    
    for ($i = 0; $i < count($totalUser); $i++) {
        $string0 .= '["' . $totalUser[$i]['libellecentrale'] . '",' . $totalUser[$i]['nb']  . '],';
    }
    
}
if (IDTYPEUSER == ADMINLOCAL) {
    $title = TXT_CLEANROOMUSERNEWPROJECT;
    $totalUser = $manager->getList2("select annee,sum(nb) as nb from tmpUserCleanRoom where libellecentrale=? group by annee order by annee asc", LIBELLECENTRALEUSER);
    $nbTotaluser2013 = $manager->getSingle2("select sum(nb) as nb from tmpUserCleanRoom where libellecentrale=? and annee <=2013",LIBELLECENTRALEUSER);
    $nbTotaluser = $manager->getSingle2("select sum(nb) as nb from tmpUserCleanRoom where libellecentrale=?",LIBELLECENTRALEUSER);
    for ($i = 0; $i < count($totalUser); $i++) {
        if($totalUser[$i]['annee'] <='2013'){               
               $string0 = '["' . "Inférieur ou égale à 2013" . '",' . $nbTotaluser2013  . '],';
        }else{
            $string0 .= '["' . $totalUser[$i]['annee'] . '",' . $totalUser[$i]['nb']  . '],';
        }
    }   
} $string = substr($string0, 0, -1);
$subtitle =TXT_NOMBREBUSER.' '.$nbTotaluser;
include_once 'commun/scriptPie.php';
BD::deconnecter();
