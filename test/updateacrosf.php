<?php

include '../class/Manager.php';
//FERMETURE DE LA CONNEXION
$db = BD::connecter();
$manager = new Manager($db);
$array = $manager->getList("select idprojet ,acrosourcefinancement,idsourcefinancement_sourcefinancement from projet where acrosourcefinancement is not null and acrosourcefinancement!='' order by idprojet asc");
for ($i = 0; $i < count($array); $i++) {
    $projetacro = new ProjetAcrosourcefinancement($array[$i]['idprojet'],$array[$i]['acrosourcefinancement'], $array[$i]['idsourcefinancement_sourcefinancement']);
    $manager->updateProjetacrosourcefinancement($projetacro, $array[$i]['idprojet']);
}
