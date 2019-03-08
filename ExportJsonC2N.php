<?php

session_start();
include 'class/Manager.php';
include 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$datas = $manager->getListbyArray("SELECT 
idprojet,
numero as Num_projet,
acronyme as acronyme,
idstatutprojet_statutprojet as statut,
to_char(datedebutprojet, 'DD/MM/YYYY') as date_debut_projet,
CASE WHEN datedebutprojet IS NOT NULL THEN
to_char(datedebutprojet + (dureeprojet::text || ' month')::interval, 'DD/MM/YYYY')
END AS date_fin_projet,
CASE 
    WHEN confidentiel = 'TRUE' THEN 'Oui'
    ELSE 'Non'
 END AS projet_confidentiel,
contactscentraleaccueil as leader_project,
STRING_AGG(CONCAT(lower(trim(prenomaccueilcentrale)),'.',lower(trim(nomaccueilcentrale))),', ') AS personnes_travaillant_sur_le_projet
FROM projet
LEFT JOIN concerne co ON co.idprojet_projet = idprojet
LEFT JOIN projetpersonneaccueilcentrale pp ON pp.idprojet_projet = idprojet
LEFT JOIN personneaccueilcentrale pa ON pa.idpersonneaccueilcentrale = pp.idpersonneaccueilcentrale_personneaccueilcentrale
WHERE co.idcentrale_centrale = ? AND idstatutprojet_statutprojet in (?,?,?)
GROUP BY idprojet,idstatutprojet_statutprojet ;
", array(IDCENTRALEUSER, ENCOURSREALISATION, FINI, CLOTURE));

$fp = fopen('tmp/data.json', 'w');
fwrite($fp, '[');
for ($i = 0; $i < count($datas); $i++) {
    $leader_project = '"leader_project":{';
    $a_leader_project = explode(',', $datas[$i]['leader_project']);
    for ($j = 0; $j < count($a_leader_project); $j++) {
        $k = $j + 1;
        $leader_project .= '"leader_' . $j . '":' . '"' . $a_leader_project[$j] . '",';
    }
    $leader_project = substr($leader_project, 0, -1);
    $leader_project .= "}";

    $personnes_travaillant_sur_le_projet = '"personnes_travaillant_sur_le_projet":{';
    $a_personnes_travaillant_sur_le_projet = explode(',', $datas[$i]['personnes_travaillant_sur_le_projet']);
    for ($j = 0; $j < count($a_personnes_travaillant_sur_le_projet); $j++) {
        $k = $j + 1;
        if (count($a_personnes_travaillant_sur_le_projet) > 0) {
            $personnes_travaillant_sur_le_projet .= '"personne_' . $j . '":' . '"' . skip_accents(trim($a_personnes_travaillant_sur_le_projet[$j])) . '",';
        }
    }
    $personnes_travaillant_sur_le_projet = substr($personnes_travaillant_sur_le_projet, 0, -1);
    $personnes_travaillant_sur_le_projet .= "}";
    $dataC2N = ""
            . '{"idprojet":' . '"' . $datas[$i]['idprojet'] . '"' . ","
            . '"num_projet":' . '"' . $datas[$i]['num_projet'] . '"' . ","
            . '"acronyme":' . '"' . $datas[$i]['acronyme'] . '"' . ","
            . '"statut":' . '"' . $datas[$i]['statut'] . '"' . ","
            . '"date_debut_projet":' . '"' . $datas[$i]['date_debut_projet'] . '"'
            . "," . '"date_fin_projet":' . '"' . $datas[$i]['date_fin_projet'] . '"'
            . "," . '"projet_confidentiel":' . '"' . $datas[$i]['projet_confidentiel'] . '"'
            . "," . $leader_project
            . "," . $personnes_travaillant_sur_le_projet . "},";
    fputs($fp, $dataC2N);
    fwrite($fp, '');
}
fwrite($fp, ']');
$json = "tmp/data.json";
$json1 = file_get_contents($json);
$json2 = str_replace('},]', '}]', $json1);
file_put_contents($json, $json2);
fclose($fp);
chmod('tmp/data.json', 0777);

function skip_accents($str, $charset = 'utf-8') {
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
    $str = preg_replace('#&[^;]+;#', '', $str);
    return $str;
}

$repertoire = explode('/', $_SERVER['PHP_SELF']);
$repertoire[1] = "projet-dev";
$_SERVER['SERVER_NAME'] = "www.renatech.org";
$url_distant = "https://" . $_SERVER['SERVER_NAME'] . '/' . $repertoire[1] . '/tmp/data.json';

$fichier = file_get_contents($url_distant);
header("Content-type: application/json;charset=UTF-8");
header("Content-disposition: attachment; filename=exportjson_" . date('d-m-Y') . ".json");
print $fichier;
exit;
