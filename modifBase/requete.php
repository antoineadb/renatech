<?php

include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}

if (isset($_POST['request']) && !empty($_POST['request'])) {
    $manager->exeRequete('drop table if exists tmp;');
    $manager->exeRequete("create table tmp as (" . $_POST['request'] . ")");
    $result = $manager->getList("select * from tmp");
    $titresColonne = $manager->getList2("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?", 'tmp');
//------------------------------------------------------------------------------------------------------------------------------------------
    $fp = fopen('../tmp/requete.json', 'w');
    $data = "";
    fwrite($fp, '{"items": [');
    for ($i = 0; $i < count($result); $i++) {
        fwrite($fp, '{');
        foreach ($titresColonne as $key => $titre) {
            $data = '"' . $titre[0] . '"' . ':' . '"' . removeDoubleQuote(stripslashes($result[$i]["" . $titre[0] . ""])) . '"';
            fputs($fp, $data.',' );
        }fwrite($fp, '},');
    }fwrite($fp, ',');    
    fwrite($fp, ']}');
    $json_file = "../tmp/requete.json";
    $json_file1 = file_get_contents($json_file);
    $json0 = str_replace(',}', '}', $json_file1);
    $json = str_replace(',,]}', ']}', $json0);
    file_put_contents($json_file, $json);
    fclose($fp);
    chmod('../tmp/requete.json', 0777);
    header('Location: /' . REPERTOIRE . '/resultats.php?lang=' . $lang.'&nb='.count($result));
 }
