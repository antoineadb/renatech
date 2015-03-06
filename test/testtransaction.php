<?php
include_once '../class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$projetsourcefinancement = new Projetsourcefinancement(1140, 6);
$manager->insertProjetSF($projetsourcefinancement);
