<?php

session_start();
include_once '../class/Chiffrement.php';
include_once '../class/Manager.php';
include_once '../decide-lang.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
$db = BD::connecter();

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$manager = new Manager($db);
if (isset($_SESSION['email'])) {
    $mail = $_SESSION['email'];
    $_SESSION['mail'] = $mail;
} else {
    $mail = $_SESSION['mail'];
    $_SESSION['mail'] = $mail;
}

//-----------------------------------------------------------------------------------------------------------------------------------------------
//                                                                  PROJET REFUSES
//-----------------------------------------------------------------------------------------------------------------------------------------------
$rowprojetrefuse = $manager->getList2("SELECT ce.libellecentrale, s.libellestatutprojet, p.titre, p.idprojet, p.numero, p.dateprojet,   c.commentaireprojet FROM concerne c, projet p, centrale ce, statutprojet s 
WHERE c.idprojet_projet = p.idprojet AND  ce.idcentrale = c.idcentrale_centrale AND   s.idstatutprojet = c.idstatutprojet_statutprojet   AND s.idstatutprojet =? ",REFUSE);

$fpprojet = fopen('../tmp/projetRefuseCentrales.json', 'w');
$dataprojet = "";
fwrite($fpprojet, '{"items": [');
$nbrowprojetrefuse = count($rowprojetrefuse);
for ($i = 0; $i < $nbrowprojetrefuse; $i++) {
        $dataprojet = "" .
                '{"numero":' . '"' . $rowprojetrefuse[$i]['numero'] . '"'
                . "," . '"idprojet":' . '"' . $rowprojetrefuse[$i]['idprojet'] . '"'
                . "," . '"dateprojet":' . '"' . $rowprojetrefuse[$i]['dateprojet'] . '"'
                . "," . '"titre":' . '"' . filtredonnee(strip_tags($rowprojetrefuse[$i]['titre'])) . '"'
                . "," . '"libellecentrale":' . '"' . $rowprojetrefuse[$i]['libellecentrale'] . '"'
                . "," . '"libellestatutprojet":' . '"' . $rowprojetrefuse[$i]['libellestatutprojet'] . '"'
                . "," . '"commentaireprojet":' . '"' .stripslashes(strip_tags($rowprojetrefuse[$i]['commentaireprojet'])) . '"' . "},";
        fputs($fpprojet, $dataprojet);
        fwrite($fpprojet, '');
}
fwrite($fpprojet, ']}');
$json_fileprojet = "../tmp/projetRefuseCentrales.json";
$jsonprojet1 = file_get_contents($json_fileprojet);
$jsonprojet = str_replace('},]}', '}]}', $jsonprojet1);
file_put_contents($json_fileprojet, $jsonprojet);
fclose($fpprojet);
chmod('../tmp/projetRefuseCentrales.json', 0777);
//-----------------------------------------------------------------------------------------------------------------------------------------------
//                                                                   PROJET TRANSFERER ENTRE 2 CENTRALES
//-----------------------------------------------------------------------------------------------------------------------------------------------
$projetRefuse = $manager->getlist2("select idprojet_projet from concerne where idstatutprojet_statutprojet = ? ",REFUSE);
$arrayIdProjetRefuse=array();
foreach ($projetRefuse as $value) {
    array_push($arrayIdProjetRefuse, $value[0]);
}
$arrayIdProjetRefuses = array_unique($arrayIdProjetRefuse);

$projetAccepte = $manager->getlist2("select idprojet_projet from concerne where idstatutprojet_statutprojet != ? ",REFUSE);
$arrayIdProjetAccepte=array();
foreach ($projetAccepte as $value) {
    array_push($arrayIdProjetAccepte,$value[0] );
}
$resultat = array_values(array_intersect($arrayIdProjetAccepte, $arrayIdProjetRefuse));

$fpprojetAll = fopen('../tmp/ProjetRefuseeCentraleAdminNat.json', 'w');
fwrite($fpprojetAll, '{"items": [');
$centraleRefusProjet="";
for ($i = 0; $i < count($resultat); $i++) {    
    $centraleaffecteProjet = $manager->getSinglebyArray("SELECT libellecentrale FROM projet,concerne,centrale WHERE idprojet_projet = idprojet and idcentrale_centrale = idcentrale and idprojet_projet=? "
            . "and idstatutprojet_statutprojet !=?",array($resultat[$i],REFUSE));
    
    $centraleRefusProjet .= $manager->getSinglebyArray("SELECT libellecentrale FROM projet,concerne,centrale WHERE idprojet_projet = idprojet and idcentrale_centrale = idcentrale and idprojet_projet=? "
            . "and idstatutprojet_statutprojet =?",array($resultat[$i],REFUSE)).",";
    
    $donneeprojet = $manager->getList2("select numero,dateprojet,idprojet,titre,refinterneprojet,acronyme from projet where idprojet=?",$resultat[$i]);
    $array = explode(",",substr($centraleRefusProjet,0,-1));
    $array = array_unique($array);
    $centraleRefuse = implode(",",$array);
    $dataprojet = "" .
            '{"numero":' . '"' . $donneeprojet[0]['numero'] . '"'
                . "," . '"idprojet":' . '"' . $donneeprojet[0]['idprojet'] . '"'
                . "," . '"dateprojet":' . '"' . $donneeprojet[0]['dateprojet'] . '"'
                . "," . '"centraleaffect":' . '"' . $centraleaffecteProjet . '"'
                . "," . '"centraleRefusProjet":' . '"' . $centraleRefuse . '"'
                . "," . '"titre":' . '"' . filtredonnee(strip_tags($donneeprojet[0]['titre'])) . '"'
                . "," . '"refinterneprojet":' . '"' . filtredonnee(strip_tags($donneeprojet[0]['refinterneprojet'])) . '"'
                . "," . '"acronyme":' . '"' . filtredonnee(strip_tags($donneeprojet[0]['acronyme'])) . '"' . "},";
    fputs($fpprojetAll, $dataprojet);
    fwrite($fpprojetAll, '');
}
fwrite($fpprojetAll, ']}');
$json_fileprojetAll = "../tmp/ProjetRefuseeCentraleAdminNat.json";
$jsonprojetAll1 = file_get_contents($json_fileprojetAll);
$jsonProjet = str_replace('},]}', '}]}', $jsonprojetAll1);
file_put_contents($json_fileprojetAll, $jsonProjet);
fclose($fpprojetAll);
chmod('../tmp/ProjetRefuseeCentraleAdminNat.json', 0777);

header('location:/' . REPERTOIRE . '/viewRefusedProject/' . $lang);
BD::deconnecter();



BD::deconnecter();
