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
$rowprojetrefuse = $manager->getList2("SELECT ce.libellecentrale, s.libellestatutprojet,p.refinterneprojet, p.titre, p.idprojet, p.numero, p.dateprojet,   c.commentaireprojet FROM concerne c, projet p, centrale ce, statutprojet s 
WHERE c.idprojet_projet = p.idprojet AND  ce.idcentrale = c.idcentrale_centrale AND   s.idstatutprojet = c.idstatutprojet_statutprojet   AND s.idstatutprojet =? ", REFUSE);

if (count($rowprojetrefuse) != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < count($rowprojetrefuse); $i++) {
        $idprojet = $rowprojetrefuse[$i]['idprojet'];

        if (!empty($rowprojetrefuse[$i]['refinterneprojet'])) {
            $refinterne = $rowprojetrefuse[$i]['refinterneprojet'];
        } else {
            $refinterne = "";
        }
//numéro;Date de la demande;Titre du projet;Mise à jour;référence interne;statut du projet;demandeur du projet;Porteur du projet;Acronyme;Date de fin;Date de fin proche   

        $originalDate = date('d-m-Y');

        $data .= "" .
                $rowprojetrefuse[$i]['dateprojet'] . ";" .
                $rowprojetrefuse[$i]['numero'] . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($rowprojetrefuse[$i]['titre'])))) . ";" .
                $rowprojetrefuse[$i]['libellecentrale'] . ";" .
                strip_tags(removeDoubleQuote(stripslashes(utf8_decode(trim($rowprojetrefuse[$i]['commentaireprojet']))))) . ";" .
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
$rowprojetrefuseAll = $manager->getList2("SELECT idprojet,libellecentrale,refinterneprojet,titre,dateprojet,numero,commentaireprojet,idcentrale_centrale FROM   projet,concerne,centrale WHERE idprojet_projet = idprojet and idcentrale_centrale = idcentrale
and idprojet not in (select idprojet from projet,concerne WHERE idprojet_projet = idprojet and idstatutprojet_statutprojet !=? ) order by idprojet asc ", REFUSE);

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
