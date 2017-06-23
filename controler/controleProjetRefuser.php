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
header('location:/' . REPERTOIRE . '/viewRefusedProject/' . $lang);

//-----------------------------------------------------------------------------------------------------------------------------------------------
//                                                                   PROJET REFUSES PAR TOUTES LES CENTRALES
//-----------------------------------------------------------------------------------------------------------------------------------------------

$rowprojetrefuseAll = $manager->getList2("SELECT idprojet,libellecentrale,titre,dateprojet,numero,commentaireprojet,idcentrale_centrale FROM   projet,concerne,centrale WHERE idprojet_projet = idprojet and idcentrale_centrale = idcentrale
and idprojet not in (select idprojet from projet,concerne WHERE idprojet_projet = idprojet and idstatutprojet_statutprojet !=? ) order by idprojet asc ",REFUSE);
$fpprojetAll = fopen('../tmp/projectRefusedAll.json', 'w');
fwrite($fpprojetAll, '{"items": [');
for ($i = 0; $i < count($rowprojetrefuseAll); $i++) {
    $dataprojet = "" .
            '{"numero":' . '"' . $rowprojetrefuseAll[$i]['numero'] . '"'
                . "," . '"idprojet":' . '"' . $rowprojetrefuseAll[$i]['idprojet'] . '"'
                . "," . '"dateprojet":' . '"' . $rowprojetrefuseAll[$i]['dateprojet'] . '"'
                . "," . '"titre":' . '"' . filtredonnee(strip_tags($rowprojetrefuseAll[$i]['titre'])) . '"'
                . "," . '"libellecentrale":' . '"' . $rowprojetrefuseAll[$i]['libellecentrale'] . '"'
                . "," . '"libellestatutprojet":' . '"' . $rowprojetrefuseAll[$i]['libellestatutprojet'] . '"'
                . "," . '"commentaireprojet":' . '"' .stripslashes(strip_tags($rowprojetrefuseAll[$i]['commentaireprojet'])) . '"' . "},";
    fputs($fpprojetAll, $dataprojet);
    fwrite($fpprojetAll, '');
}
fwrite($fpprojetAll, ']}');
$json_fileprojetAll = "../tmp/projectRefusedAll.json";
$jsonprojetAll1 = file_get_contents($json_fileprojetAll);
$jsonProjet = str_replace('},]}', '}]}', $jsonprojetAll1);
file_put_contents($json_fileprojetAll, $jsonProjet);
fclose($fpprojetAll);
chmod('../tmp/projectRefusedAll.json', 0777);

header('location:/' . REPERTOIRE . '/viewRefusedProject/' . $lang);
BD::deconnecter();



BD::deconnecter();
