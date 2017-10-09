<?php

session_start();
include 'decide-lang.php';
include 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    $pseudo = $_SESSION['pseudo'];
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
//------------------------------------------------------------------------
//-----RECUPERATION DU LIBELLE DE LA CENTRALE DU RESPONSABLE CENTRALE-----
//------------------------------------------------------------------------
$libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale , utilisateur ,loginpassword  WHERE
idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);

$data = utf8_decode('Projets Refusés mais acceptés dans une centrale');
$data .= "\n";
$data .= "------------------------";
$data .= "\n";
$data .= utf8_decode("Date de la demande;numéro;Titre du projet;Centrale;Commentaire;référence interne");
$data .= "\n";
/*$rowprojetrefuse = $manager->getList2("SELECT ce.libellecentrale, s.libellestatutprojet,p.refinterneprojet, p.titre, p.idprojet, p.numero, p.dateprojet,   c.commentaireprojet FROM concerne c, projet p, centrale ce, statutprojet s 
WHERE c.idprojet_projet = p.idprojet AND  ce.idcentrale = c.idcentrale_centrale AND   s.idstatutprojet = c.idstatutprojet_statutprojet   AND s.idstatutprojet =? ", REFUSE);
*/
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
/*echo '<pre>';
print_r($rowprojetrefuse);die;*/
if (count($rowprojetrefuse) != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < count($rowprojetrefuse); $i++) {
        $idprojet = $rowprojetrefuse[$i][0]['idprojet'];

        if (!empty($rowprojetrefuse[$i][0]['refinterneprojet'])) {
            $refinterne = $rowprojetrefuse[$i][0]['refinterneprojet'];
        } else {
            $refinterne = "";
        }
//numéro;Date de la demande;Titre du projet;Mise à jour;référence interne;statut du projet;demandeur du projet;Porteur du projet;Acronyme;Date de fin;Date de fin proche   

        $originalDate = date('d-m-Y');

        $data .= "" .
                $rowprojetrefuse[$i][0]['dateprojet'] . ";" .
                $rowprojetrefuse[$i][0]['numero'] . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($rowprojetrefuse[$i][0]['titre'])))) . ";" .
                $rowprojetrefuse[$i][0]['libellecentrale'] . ";" .
                strip_tags(removeDoubleQuote(stripslashes(utf8_decode(trim($rowprojetrefuse[$i][0]['commentaireprojet']))))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($refinterne)))) . "\n";
    }
}
$data .= "\n";
$data .= utf8_decode('Projets Refusés dans toutes les centrales');
$data .= "\n";
$data .= "------------------------";
$data .= "\n";
$data .= utf8_decode("Date de la demande;numéro;Titre du projet;Centrale;Commentaire;référence interne");
$data .= "\n";

$rowprojetrefuseAll = $manager->getListbyArray("SELECT distinct idprojet,p.numero ,co.commentaireprojet,p.acronyme,p.titre,p.idprojet,p.dateprojet,co.datestatutrefuser,
s.libellestatutprojet,p.idprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire,p.refinterneprojet ,c.libellecentrale
FROM projet p,utilisateur u,creer cr,concerne co,typeprojet t,statutprojet s,centrale c
WHERE p.idtypeprojet_typeprojet = t.idtypeprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = p.idprojet 
AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet AND c.idcentrale=co.idcentrale_centrale
AND p.idprojet  IN (SELECT idprojet FROM projet,concerne WHERE idprojet_projet=idprojet AND idstatutprojet_statutprojet = ? ) AND idstatutprojet_statutprojet !=?", array(REFUSE,REFUSE));

if (count($rowprojetrefuseAll) != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < count($rowprojetrefuseAll); $i++) {
        $idprojet = $rowprojetrefuseAll[$i]['idprojet'];
        if (!empty($rowprojetrefuseAll[$i]['refinterneprojet'])) {
            $refinterne = $rowprojetrefuseAll[$i]['refinterneprojet'];
        } else {
            $refinterne = "";
        }
//numéro;Date de la demande;Titre du projet;Mise à jour;référence interne;statut du projet;demandeur du projet;Porteur du projet;Acronyme;Date de fin;Date de fin proche
        $originalDate = date('d-m-Y');
        $data .= "" .
                $rowprojetrefuseAll[$i]['dateprojet'] . ";" .
                $rowprojetrefuseAll[$i]['numero'] . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($rowprojetrefuseAll[$i]['titre'])))) . ";" .
                $rowprojetrefuseAll[$i]['libellecentrale'] . ";" .
                strip_tags(removeDoubleQuote(stripslashes(utf8_decode(trim($rowprojetrefuseAll[$i]['commentaireprojet']))))) . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($refinterne)))) . "\n";
    }
}
// Déclaration du type de contenu    
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=exportprojetrefusecentrale_" . time() . '_' . $originalDate . ".csv");
    print $data;
    exit;
/*if ($bool && $bool1) {
    echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/projet_centrale/' . $lang . '/' . $libellecentrale . '")</script>';
}*/
BD::deconnecter();
