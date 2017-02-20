<?php

include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$tmp = $manager->getList('select * from tmp;');
$titresColonne = $manager->getList2("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?", 'tmp');
$data = "";
foreach ($titresColonne as $key => $titre) {
    if ($titre[0] == 'prenom') {
        $titre = 'Prénom';
    } elseif ($titre[0] == 'codepostal') {
        $titre = 'Code postal';
    }elseif ($titre[0] == 'telephone') {
        $titre = 'Téléphone';
    } else {
        $titre = $titre[0];
    }
    $data .=ucfirst(utf8_decode($titre)) . ";";
}
$data .= "\n";
for ($i = 0; $i < count($tmp); $i++) {
    foreach ($titresColonne as $key => $titre) {
        $data .=utf8_decode($tmp[$i]['' . $titre[0] . '']) . ";" ;
    }
    $data .="\n";
}

header("Content-type: application/vnd.ms-excel;charset=UTF-8");
header("Content-disposition: attachment; filename=export_resultat_requete" . date('d-m-Y') . ".csv");
print $data;
BD::deconnecter();
exit;
