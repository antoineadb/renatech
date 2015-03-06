<?php

include_once '../class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$datejour = '01-16-2015';

$loginconnect = $manager->getList2("select * from compteur where c_firstvisit  =?",'01/14/2015');

echo '<pre>';print_r($loginconnect);echo '<pre>';
