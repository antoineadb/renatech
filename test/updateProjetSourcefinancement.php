<?php

include '../class/Manager.php';
//FERMETURE DE LA CONNEXION
$db = BD::connecter();
$manager = new Manager($db);

$arrayprojet = $manager->getList("select idprojet,idsourcefinancement_sourcefinancement from projet ");
for ($i = 0; $i < count($arrayprojet); $i++) {
    if (!empty($arrayprojet[$i]['idsourcefinancement_sourcefinancement'])) {
        $projetSF = new Projetsourcefinancement($arrayprojet[$i]['idprojet'], $arrayprojet[$i]['idsourcefinancement_sourcefinancement']);
        $manager->insertProjetSF($projetSF);
    }
}
?>
