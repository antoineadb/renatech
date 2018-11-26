<?php

$url = "https://www.renatech.org/projet-dev/webService/processJson2.php";

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
        . " WHERE datedebutprojet BETWEEN '2017-01-01' AND '2018-12-31' AND confidentiel is not TRUE");
for ($i = 0; $i < count($datas); $i++) {
    foreach ($datas[$i] as $key => $value) {
        if (is_int($key)) {
            unset($datas[$i][$key]);
        }
    }
}
$content = json_encode($datas);
$curl = curl_init("https://www.renatech.org/projet-dev/webService/processJson2.php");
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

$json_response = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($status != 201) {
    die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
}


curl_close($curl);

$response = json_decode($json_response, true);
