<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datedebutprojet) as annee  from projet where EXTRACT(YEAR from datedebutprojet)>2012  order by annee asc");

if (IDTYPEUSER == ADMINNATIONNAL&& !isset($_GET['anneeDureeeProjet'])) {
    $title = TXT_DUREEPROJETENCOURSDATE.(date('Y')-1);    
    $xasisTitle = "";    
    $nbtotalprojet = $manager->getSingle2("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? ",(date('Y') - 1));
    $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
    
    $nbprojetRang1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? and rang=?", array(date('Y') - 1,1));
    $nbprojetRang2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? and rang=?", array(date('Y') - 1,2));
    $nbprojetRang3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? and rang=?", array(date('Y') - 1,3));    
    
    $string0 = '["' .TXT_INF1AN. '",' . $nbprojetRang1 . '],';
    $string3 = '["' .TXT_SUP1ANINF3ANS . '",' . $nbprojetRang2 . '],';
    $string4 = '["' .TXT_SUP3ANS. '",' . $nbprojetRang3 . '],';    
}elseif (IDTYPEUSER == ADMINNATIONNAL&& isset($_GET['anneeDureeeProjet'])) {
    $title = TXT_DUREEPROJETENCOURSDATE.$_GET['anneeDureeeProjet'];    
    $xasisTitle = "";    
    $nbtotalprojet = $manager->getSingle2("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? ",$_GET['anneeDureeeProjet']);
    $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
    
    $nbprojetRang1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? and rang=?", array($_GET['anneeDureeeProjet'],1));
    $nbprojetRang2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? and rang=?", array($_GET['anneeDureeeProjet'],2));
    $nbprojetRang3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? and rang=?", array($_GET['anneeDureeeProjet'],3));    
    
    $string0 = '["' .TXT_INF1AN. '",' . $nbprojetRang1 . '],';
    $string3 = '["' .TXT_SUP1ANINF3ANS . '",' . $nbprojetRang2 . '],';
    $string4 = '["' .TXT_SUP3ANS. '",' . $nbprojetRang3 . '],';
    
}


if (IDTYPEUSER == ADMINLOCAL){
    $title = TXT_DUREEPROJETENCOURSDATE.(date('Y')-1);    
    $xasisTitle = "";    
    $nbprojetRang1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? and rang=? and idcentrale_centrale=?", array((date('Y') - 1),1,IDCENTRALEUSER));
    $nbprojetRang2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? and rang=? and idcentrale_centrale=?", array((date('Y') - 1),2,IDCENTRALEUSER));
    $nbprojetRang3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where extract(year from datedebutprojet)<=? and rang=? and idcentrale_centrale=?", array((date('Y') - 1),3,IDCENTRALEUSER));    
    $nbtotalprojet = $nbprojetRang1+$nbprojetRang2+$nbprojetRang3;
    $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;    
    $string0 = '["' .TXT_INF1AN. '",' . $nbprojetRang1 . '],';
    $string3 = '["' .TXT_SUP1ANINF3ANS . '",' . $nbprojetRang2 . '],';
    $string4 = '["' .TXT_SUP3ANS. '",' . $nbprojetRang3 . '],';    
}
$string = substr($string0 . $string3 . $string4, 0, -1);
include_once 'commun/scriptPie.php';
?>

<?php
BD::deconnecter();
