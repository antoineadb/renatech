<?php

session_start();
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
//                                                                  PROJET REFUSES DANS TOUTE LES CENTRALES
//-----------------------------------------------------------------------------------------------------------------------------------------------
$row = $manager->getList2("SELECT p.idprojet,s.idstatutprojet
FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
WHERE p.idtypeprojet_typeprojet = t.idtypeprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = p.idprojet AND
ce.idcentrale = co.idcentrale_centrale AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet
AND s.idstatutprojet=? AND trashed =FALSE", REFUSE);
$arrayIdProjet = array();
for ($i = 0; $i < count($row); $i++) {
        array_push($arrayIdProjet, $row[$i]['idprojet']);
}
$test = array();
foreach ($arrayIdProjet as $idprojet) {
        array_push($test, $manager->getSingle2("select idprojet_projet from concerne where idprojet_projet=? AND idstatutprojet_statutprojet!=4", $idprojet));
}
$array = array_values(array_filter($test));
$arrayIdprojetTouscentrale = array_diff($arrayIdProjet, $array);
$arrayIdprojetTousCentrale = array_unique($arrayIdprojetTouscentrale);

$rowprojetrefuse = array();
foreach ($arrayIdprojetTousCentrale as $idprojet) {
    array_push($rowprojetrefuse, $manager->getList2("SELECT p.numero ,co.commentaireprojet,p.acronyme,p.titre,p.idprojet,p.dateprojet,co.datestatutrefuser,
            s.libellestatutprojet,p.idprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire,p.refinterneprojet ,c.libellecentrale
            FROM projet p,utilisateur u,creer cr,concerne co,typeprojet t,statutprojet s,centrale c
            WHERE p.idtypeprojet_typeprojet = t.idtypeprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = p.idprojet 
            AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet AND c.idcentrale=co.idcentrale_centrale
            AND p.idprojet=? ", $idprojet));
}

$fpprojet = fopen('../tmp/projetRefuseCentrales.json', 'w');
$dataprojet = "";
fwrite($fpprojet, '{"items": [');
$nbrowprojetrefuse = count($rowprojetrefuse);

for ($i = 0; $i < $nbrowprojetrefuse; $i++) {
        $dataprojet = "" .
                '{"numero":' . '"' . $rowprojetrefuse[$i][0]['numero'] . '"'
                . "," . '"idprojet":' . '"' . $rowprojetrefuse[$i][0]['idprojet'] . '"'
                . "," . '"dateprojet":' . '"' . $rowprojetrefuse[$i][0]['dateprojet'] . '"'
                . "," . '"titre":' . '"' . filtredonnee(strip_tags($rowprojetrefuse[$i][0]['titre'])) . '"'
                . "," . '"libellecentrale":' . '"' . $rowprojetrefuse[$i][0]['libellecentrale'] . '"'
                . "," . '"libellestatutprojet":' . '"' . $rowprojetrefuse[$i][0]['libellestatutprojet'] . '"'
                . "," . '"commentaireprojet":' . '"' .stripslashes(strip_tags($rowprojetrefuse[$i][0]['commentaireprojet'])) . '"' . "},";
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
//                                                                   PROJET Transférés dans une autre centrale
//-----------------------------------------------------------------------------------------------------------------------------------------------

$rowprojetrefuseAll = $manager->getListbyArray("SELECT distinct idprojet,p.numero ,co.commentaireprojet,p.acronyme,p.titre,p.idprojet,p.dateprojet,co.datestatutrefuser,
s.libellestatutprojet,p.idprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire,p.refinterneprojet ,c.libellecentrale
FROM projet p,utilisateur u,creer cr,concerne co,typeprojet t,statutprojet s,centrale c
WHERE p.idtypeprojet_typeprojet = t.idtypeprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = p.idprojet 
AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet AND c.idcentrale=co.idcentrale_centrale
AND p.idprojet  IN (SELECT idprojet FROM projet,concerne WHERE idprojet_projet=idprojet AND idstatutprojet_statutprojet = ? ) AND idstatutprojet_statutprojet !=?", array(REFUSE,REFUSE));

$fpprojetAll = fopen('../tmp/projectRefusedAll.json', 'w');
fwrite($fpprojetAll, '{"items": [');
for ($i = 0; $i < count($rowprojetrefuseAll); $i++) {
$centraleAffectation = $manager->getSinglebyArray("SELECT libellecentrale FROM centrale, concerne WHERE idcentrale_centrale =idcentrale AND idstatutprojet_statutprojet != 4 "
        . " AND idprojet_projet=?",array($rowprojetrefuseAll[$i]['idprojet']));
$centraleDepot = $manager->getSinglebyArray("SELECT libellecentrale FROM centrale, concerne WHERE idcentrale_centrale =idcentrale AND idstatutprojet_statutprojet = 4 "
        . " AND idprojet_projet=?",array($rowprojetrefuseAll[$i]['idprojet']));
    $dataprojet = "" .
            '{"numero":' . '"' . $rowprojetrefuseAll[$i]['numero'] . '"'
                . "," . '"idprojet":' . '"' . $rowprojetrefuseAll[$i]['idprojet'] . '"'
                . "," . '"dateprojet":' . '"' . $rowprojetrefuseAll[$i]['dateprojet'] . '"'
                . "," . '"titre":' . '"' . filtredonnee(strip_tags($rowprojetrefuseAll[$i]['titre'])) . '"'
                . "," . '"libellecentraledepot":' . '"' . $centraleDepot . '"'
                . "," . '"libellecentralefinal":' . '"' . $centraleAffectation . '"'
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
