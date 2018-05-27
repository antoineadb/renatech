<?php

include '../class/Manager.php';
include_once '../outils/toolBox.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$data = utf8_decode("Titre du projet");
$data .= "\n";
$anneeExport = 2013;
$arrayidcentrale = $manager->getList2("select idcentrale from centrale where libellecentrale !=? order by idcentrale asc", 'Autres');
$nbidcentrale = count($arrayidcentrale);
$arrayid = array();
for ($i = 0; $i < $nbidcentrale; $i++) {
    array_push($arrayid, $arrayidcentrale[$i]['idcentrale']);
}
$nbarrayid = count($arrayid);

$donnee1 = array(1, 2013);
$row1 = $manager->getListbyArray("SELECT titre FROM projet p,creer c,concerne co WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet  AND co.idcentrale_centrale =? AND  datedebutprojet is not null 
        AND  datestatutfini is null  AND  datestatutcloturer is null AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?", $donnee1);

for ($i = 0; $i < count($row1); $i++) {
    $Titre = str_replace("’", "'", stripslashes((($row1[$i]['titre']))));
    $titre = str_replace("''", "'", utf8_decode($Titre));    
    $data .= "" . $titre . ";" . "\n";
}
file_put_contents("../tmp/export_projet_1.csv", $data);

$donnee2 = array(2, 2013);
$row2 = $manager->getListbyArray("SELECT titre FROM projet p,creer c,concerne co WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet  AND co.idcentrale_centrale =? AND  datedebutprojet is not null 
        AND  datestatutfini is null  AND  datestatutcloturer is null AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?", $donnee2);

for ($i = 0; $i < count($row2); $i++) {
    $Titre = str_replace("’", "'", stripslashes((($row2[$i]['titre']))));
    $titre = str_replace("''", "'", utf8_decode($Titre));    
    $data .= "" . $titre . ";" . "\n";
}
file_put_contents("../tmp/export_projet_2.csv", $data);

$donnee3 = array(3, 2013);
$row3 = $manager->getListbyArray("SELECT titre FROM projet p,creer c,concerne co WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet  AND co.idcentrale_centrale =? AND  datedebutprojet is not null 
        AND  datestatutfini is null  AND  datestatutcloturer is null AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?", $donnee3);

for ($i = 0; $i < count($row3); $i++) {
    $Titre = str_replace("’", "'", stripslashes((($row3[$i]['titre']))));
    $titre = str_replace("''", "'", utf8_decode($Titre));    
    $data .= "" . $titre . ";" . "\n";
}
file_put_contents("../tmp/export_projet_3.csv", $data);

$donnee4 = array(1, 2013);
$row4 = $manager->getListbyArray("SELECT titre FROM projet p,creer c,concerne co WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet  AND co.idcentrale_centrale =? AND  datedebutprojet is not null 
        AND  datestatutfini is null  AND  datestatutcloturer is null AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?", $donnee4);

for ($i = 0; $i < count($row4); $i++) {
    $Titre = str_replace("’", "'", stripslashes((($row4[$i]['titre']))));
    $titre = str_replace("''", "'", utf8_decode($Titre));    
    $data .= "" . $titre . ";" . "\n";
}
file_put_contents("../tmp/export_projet_1.csv", $data);

$donnee5 = array(5, 2013);
$row5 = $manager->getListbyArray("SELECT titre FROM projet p,creer c,concerne co WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet  AND co.idcentrale_centrale =? AND  datedebutprojet is not null 
        AND  datestatutfini is null  AND  datestatutcloturer is null AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?", $donnee5);

for ($i = 0; $i < count($row5); $i++) {
    $Titre = str_replace("’", "'", stripslashes((($row5[$i]['titre']))));
    $titre = str_replace("''", "'", utf8_decode($Titre));    
    $data .= "" . $titre . ";" . "\n";
}
file_put_contents("../tmp/export_projet_5.csv", $data);

$donnee6 = array(6, 2013);
$row = $manager->getListbyArray("SELECT titre FROM projet p,creer c,concerne co WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet  AND co.idcentrale_centrale =? AND  datedebutprojet is not null 
        AND  datestatutfini is null  AND  datestatutcloturer is null AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?", $donnee6);

for ($i = 0; $i < count($row6); $i++) {
    $Titre = str_replace("’", "'", stripslashes((($row6[$i]['titre']))));
    $titre = str_replace("''", "'", utf8_decode($Titre));    
    $data .= "" . $titre . ";" . "\n";
}
file_put_contents("../tmp/export_projet_1.csv", $data);


BD::deconnecter();
