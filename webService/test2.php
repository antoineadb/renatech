<?php
header("Content-type: application/json");
include '../class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$datas = $manager->getList("SELECT acronyme,idprojet,"
        . " idutilisateur_utilisateur as iddemandeur,"
        . " numero as numero_projet,dateprojet as date_demande,libellecentrale as centrale ,datedebutprojet,idstatutprojet as statut, dureeprojet as duree_projet_mois,"
        . " datedebutprojet + interval  '1 month' * dureeprojet as date_fin_projet, datestatutfini as date_statut_fini"
        . " FROM PROJET "
        . " LEFT JOIN concerne co on co.idprojet_projet=idprojet "
        . " LEFT JOIN creer c on c.idprojet_projet=idprojet "
        . " LEFT JOIN centrale on idcentrale_centrale =idcentrale "
        . " LEFT JOIN statutprojet on idstatutprojet_statutprojet =idstatutprojet "
        . " WHERE datedebutprojet BETWEEN '2017-01-01' AND '2018-12-31' AND confidentiel is not TRUE AND idprojet IN(2024,1006)");
for ($i = 0; $i < count($datas); $i++) {
    foreach ($datas[$i] as $key => $value) {
        if (is_int($key)) {
            unset($datas[$i][$key]);
        }
    }
}
$jsonData = json_encode($datas);
$fp = fopen('../tmp/projetJson.json', 'w');
$data = "";
fwrite($fp, $jsonData);
fputs($fp, $data);
fwrite($fp, '');
$json_file = "../tmp/projetJson.json";
$json1 = file_get_contents($json_file);
file_put_contents($json_file, $json1);
fclose($fp);
chmod('../tmp/projetJson.json', 0777);

$json = file_get_contents("../tmp/projetJson.json");

echo(($json));

BD::deconnecter();
