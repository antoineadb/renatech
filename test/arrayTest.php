<?php

include_once '../class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellesourcefinancement = $manager->getList2("SELECT libellesourcefinancement FROM sourcefinancement,projetsourcefinancement WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet_projet =?", 519);


$arraySF=array();
for ($i = 0; $i < count($arraylibellesourcefinancement); $i++) {
    array_push($arraySF,$arraylibellesourcefinancement[$i]['libellesourcefinancement']);
}
if(in_array('Europe', $arraySF)){
    echo 'ok';
}