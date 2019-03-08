<?php
header("Content-type: application/json");
session_start();
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
if (isset($_POST['login']) && isset($_POST['pass'])) {
    $pseudo = $_POST['login'];
    $idlogin = $manager->getSingle2("select idlogin from loginpassword where pseudo=?", $pseudo);
    $idcentrale = $_POST['idcentrale'];
    if (!empty($idlogin)) {// LE LOGIN EXISTE
        $passe = $manager->getSingle2("select motdepasse from loginpassword where pseudo=?", $pseudo); //RECUPERATION DU MOT DE PASSE
        if (sha1($_POST['pass']) == $passe) {//CONTROLE QUE LE MOT DE PASSE EST IDENTIQUE
            //VERIFICATION SI LE COMPTE N'EST PAS DESACTIVE
            $actif = $manager->getSingle2("select actif from loginpassword where pseudo=?", $pseudo);
            if (empty($actif)) {
                $actif = 'FALSE';
                 echo 'Ce compte est désactivé' ;
                exit();
            } else {
                $actif = 'TRUE';
            }
            if ($actif == 'FALSE') {
                echo 'Ce compte est désactivé' ;
                exit();
            }
        } else {//MOT DE PASSE ERRONNE        
            echo 'Erreur de mot de passe' ;
            exit();
        }
    } else {
        echo "VOUS N'ETES PAS ENCORE INSCRIT";
        exit();
    }
}else{
    echo "L'authentification ne fonctionne pas!";
    exit();
}
if(is_file("./tmp/donnees.json")){
   // unlink("./tmp/donnees.json");
}

$fp = fopen('../tmp/donnees.json', 'w');
fwrite($fp, '[');
$datas = $manager->getListbyArray("
        SELECT acronyme,idprojet,idutilisateur_utilisateur as iddemandeur,titre,numero as numero_projet,dateprojet as date_demande,libellecentrale as centrale ,datedebutprojet,
        idstatutprojet as statut, dureeprojet as duree_projet_mois,datedebutprojet + interval  '1 month' * dureeprojet as date_fin_projet, datestatutfini as date_statut_fini
        FROM PROJET
        LEFT JOIN concerne co on co.idprojet_projet=idprojet
        LEFT JOIN creer c on c.idprojet_projet=idprojet 
        LEFT JOIN centrale on idcentrale_centrale =idcentrale
        LEFT JOIN statutprojet on idstatutprojet_statutprojet =idstatutprojet 
        WHERE  confidentiel is not TRUE AND idstatutprojet !=? AND idcentrale=? LIMIT 10", array(CLOTURE,$idcentrale));
for ($i = 0; $i < count($datas); $i++) {
    $donnee = ""
            . '{"acronyme":' . '"' . $datas[$i]['acronyme'] . '"'. ","
            . '"idprojet":' . '"' . $datas[$i]['idprojet'] . '"'. ","
            . '"iddemandeur":' . '"' . $datas[$i]['iddemandeur'] . '"'. ","             
            . '"titre":' . '"' . $datas[$i]['titre'] . '"'. ","
            . '"numero_projet":' . '"' . $datas[$i]['numero_projet'] . '"'. ","
            . '"date_demande":' . '"' . $datas[$i]['date_demande'] . '"'. ","
            . '"centrale":' . '"' . $datas[$i]['centrale'] . '"'. ","
            . '"datedebutprojet":' . '"' . $datas[$i]['datedebutprojet'] . '"'. ","
            . '"statut":' . '"' . $datas[$i]['statut'] . '"'. ","
            . '"duree_projet_mois":' . '"' . $datas[$i]['duree_projet_mois'] . '"'. ","
            . '"date_fin_projet":' . '"' . $datas[$i]['date_fin_projet'] . '"'. ","
            . '"date_statut_fini":' . '"' . $datas[$i]['date_statut_fini'] . '"}'.",";
    fputs($fp, $donnee);
    fwrite($fp, '');
}
fwrite($fp, ']');
$json = "../tmp/donnees.json";
$json1 = file_get_contents($json);
$json2 = str_replace('},]', '}]', $json1);
file_put_contents($json, $json2);
fclose($fp);
chmod('../tmp/donnees.json', 0777);
$repertoire = explode('/', $_SERVER['PHP_SELF']);
$url_distant = "https://" . $_SERVER['SERVER_NAME'] . '/' . $repertoire[1] . '/tmp/donnee.json';

$fichier = file_get_contents($json);
header("Content-type: application/json;charset=UTF-8");
header("Content-disposition: attachment; filename=exportjson_" . date('d-m-Y') . ".json");
print $fichier;
exit;


