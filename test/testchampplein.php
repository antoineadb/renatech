<?php

include '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
//DELETE FROM partenaireprojet where nompartenaire='' and nomlaboentreprise=''
/*
 select idpartenaire from partenaireprojet where nomLaboEntreprise='nomLaboEntreprise0' and nomPartenaire='nomPartenaire0'; 
select idpartenaire from partenaireprojet where nomLaboEntreprise='nomLaboEntreprise1' and nomPartenaire='nomPartenaire1';
select idpartenaire from partenaireprojet where nomLaboEntreprise='nomLaboEntreprise2' and nomPartenaire='nomPartenaire2';
select idpartenaire from partenaireprojet where nomLaboEntreprise='nomLaboEntreprise3' and nomPartenaire='nomPartenaire3';
*/

//$a_idpartenairevide = $manager->getListbyArray("select idpartenaire from partenaireprojet where nompartenaire=? and nomlaboentreprise=? order by idpartenaire asc", array('nomPartenaire3', 'nomLaboEntreprise3'));
//echo '<pre>';print_r($a_idpartenairevide);echo '</pre>';
/*for ($i = 0; $i < count($a_idpartenairevide); $i++) {
    echo $manager->getSingle2("select idprojet_projet from projetpartenaire where idpartenaire_partenaireprojet = ?", $a_idpartenairevide[$i][0]) . '<br>';
}*/

$arrayidprojet0 =array(
array("idprojet"=>859),array("idprojet"=>894),array("idprojet"=>896),array("idprojet"=>861),array("idprojet"=>862),array("idprojet"=>863),array("idprojet"=>864),array("idprojet"=>865),
array("idprojet"=>867),array("idprojet"=>868),array("idprojet"=>869),array("idprojet"=>870),array("idprojet"=>871),array("idprojet"=>872),array("idprojet"=>873),array("idprojet"=>874),array("idprojet"=>875),
array("idprojet"=>876),array("idprojet"=>877),array("idprojet"=>879),array("idprojet"=>881),array("idprojet"=>882),array("idprojet"=>883),array("idprojet"=>884),array("idprojet"=>885),array("idprojet"=>886),
array("idprojet"=>887),array("idprojet"=>888),array("idprojet"=>889),array("idprojet"=>890),array("idprojet"=>891),array("idprojet"=>892),array("idprojet"=>893),array("idprojet"=>924),array("idprojet"=>923),
array("idprojet"=>922),array("idprojet"=>921),array("idprojet"=>915),array("idprojet"=>913),array("idprojet"=>907),array("idprojet"=>910),array("idprojet"=>908),array("idprojet"=>901),array("idprojet"=>906),
array("idprojet"=>903),array("idprojet"=>925),array("idprojet"=>905),array("idprojet"=>909),array("idprojet"=>926),array("idprojet"=>927),array("idprojet"=>928),array("idprojet"=>929),array("idprojet"=>930),
array("idprojet"=>931),array("idprojet"=>933),array("idprojet"=>938),array("idprojet"=>949),array("idprojet"=>951),array("idprojet"=>964),array("idprojet"=>965),array("idprojet"=>966),array("idprojet"=>967),
array("idprojet"=>936),array("idprojet"=>945),array("idprojet"=>992),array("idprojet"=>999),array("idprojet"=>1008),array("idprojet"=>1009),array("idprojet"=>1077),array("idprojet"=>1078),array("idprojet"=>1079),
array("idprojet"=>952),array("idprojet"=>1007),array("idprojet"=>981),array("idprojet"=>1074),array("idprojet"=>1013),array("idprojet"=>1090),array("idprojet"=>1000),array("idprojet"=>1084),array("idprojet"=>911),
array("idprojet"=>1101),array("idprojet"=>1102)
);
$arrayidprojet1 =array(
array("idprojet"=>518),array("idprojet"=>536),array("idprojet"=>542),array("idprojet"=>551),array("idprojet"=>552),array("idprojet"=>556),array("idprojet"=>557),array("idprojet"=>558),array("idprojet"=>524),
array("idprojet"=>519),array("idprojet"=>895),array("idprojet"=>964),array("idprojet"=>965),array("idprojet"=>1004),array("idprojet"=>963),array("idprojet"=>1012),array("idprojet"=>920),array("idprojet"=>993),
array("idprojet"=>1088),array("idprojet"=>1086),array("idprojet"=>970),array("idprojet"=>1089),array("idprojet"=>1103),array("idprojet"=>1104));

$arrayidprojet2 =array(
array("idprojet"=>858),array("idprojet"=>897),array("idprojet"=>964),array("idprojet"=>965),array("idprojet"=>968),array("idprojet"=>972),array("idprojet"=>976),array("idprojet"=>982),array("idprojet"=>943),
array("idprojet"=>969)
);

$arrayidprojet3 =array(array("idprojet"=>956),array("idprojet"=>964),array("idprojet"=>965));


//echo '<pre>';print_r($arrayidprojet);echo '</pre>';


for ($i = 0; $i < count($arrayidprojet0); $i++) { 
        $manager->exeRequete("delete from projetpartenaire where idprojet_projet=" . $arrayidprojet0[$i]['idprojet'] . " and idpartenaire_partenaireprojet in (
    select idpartenaire from partenaireprojet where nompartenaire='nomPartenaire0' and nomlaboentreprise='nomLaboEntreprise0')");
}
for ($i = 0; $i < count($arrayidprojet1); $i++) { 
    $manager->exeRequete("delete from projetpartenaire where idprojet_projet=" . $arrayidprojet1[$i]['idprojet'] . " and idpartenaire_partenaireprojet in (
select idpartenaire from partenaireprojet where nompartenaire='nomPartenaire1' and nomlaboentreprise='nomLaboEntreprise1')");
}
for ($i = 0; $i < count($arrayidprojet2); $i++) { 
    $manager->exeRequete("delete from projetpartenaire where idprojet_projet=" . $arrayidprojet2[$i]['idprojet'] . " and idpartenaire_partenaireprojet in (
select idpartenaire from partenaireprojet where nompartenaire='nomPartenaire2' and nomlaboentreprise='nomLaboEntreprise2')");
}
for ($i = 0; $i < count($arrayidprojet3); $i++) { 
    $manager->exeRequete("delete from projetpartenaire where idprojet_projet=" . $arrayidprojet3[$i]['idprojet'] . " and idpartenaire_partenaireprojet in (
select idpartenaire from partenaireprojet where nompartenaire='nomPartenaire3' and nomlaboentreprise='nomLaboEntreprise3')");
}

$manager->exeRequete("DELETE FROM partenaireprojet where nompartenaire='nomPartenaire0' and nomlaboentreprise='nomLaboEntreprise0'");
$manager->exeRequete("DELETE FROM partenaireprojet where nompartenaire='nomPartenaire1' and nomlaboentreprise='nomLaboEntreprise1'");
$manager->exeRequete("DELETE FROM partenaireprojet where nompartenaire='nomPartenaire2' and nomlaboentreprise='nomLaboEntreprise2'");
$manager->exeRequete("DELETE FROM partenaireprojet where nompartenaire='nomPartenaire3' and nomlaboentreprise='nomLaboEntreprise3'");