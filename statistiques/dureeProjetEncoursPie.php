<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datedebutprojet) as annee  from projet where EXTRACT(YEAR from datedebutprojet)>2012  order by annee asc");

if (IDTYPEUSER == ADMINNATIONNAL) {
    $title = TXT_DUREEPROJETENCOURS;
    $xasisTitle = "";    
    $nbtotalprojet = $manager->getSingle("select count(idprojet) from tmpprojet ");
    $subtitle = TXT_NBPROJET . ': ' . $nbtotalprojet;
    
    $nbprojetRang1 = $manager->getSingle2("select count(idprojet) from tmpprojet  WHERE rang=?", 1);
    $nbprojetRang2 = $manager->getSingle2("select count(idprojet) from tmpprojet  WHERE rang=?", 2);
    $nbprojetRang3 = $manager->getSingle2("select count(idprojet) from tmpprojet  WHERE rang=?", 3);
    
    $string0 = '["' .TXT_INF1AN. '",' . $nbprojetRang1 . '],';
    $string3 = '["' .TXT_SUP1ANINF3ANS . '",' . $nbprojetRang2 . '],';
    $string4 = '["' .TXT_SUP3ANS. '",' . $nbprojetRang3 . '],';    
}

if (IDTYPEUSER == ADMINLOCAL){
    $title = TXT_DUREEPROJETENCOURS;
    $xasisTitle = "";    
    $nbprojetRang1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet  WHERE rang=? and idcentrale_centrale=?", array(1,IDCENTRALEUSER));
    $nbprojetRang2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet  WHERE rang=? and idcentrale_centrale=?", array(2,IDCENTRALEUSER));
    $nbprojetRang3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet  WHERE rang=? and idcentrale_centrale=?", array(3,IDCENTRALEUSER));    
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
