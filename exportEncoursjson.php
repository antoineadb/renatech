<?php
session_start();
include 'decide-lang.php';
include 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';   
include_once 'outils/requeteRelance.php';



$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_SESSION['pseudo'])) {    
    check_authent($_SESSION['pseudo']);
    $pseudo=$_SESSION['pseudo'];
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale , utilisateur ,loginpassword  WHERE idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);
$dateMoins3mois = date('Y-m-d', strtotime('-3 month'));

if (isset($_GET['chx']) && $_GET['chx'] == 1) {
    $sql =SQLINTERNE;
} elseif (isset($_GET['chx']) && $_GET['chx'] == 2) {
    $sql =SQLEXTERNE;
}else{
    $sql =SQLINTERNEEXTERNE;
}


$row = $manager->getListbyArray($sql, array(IDCENTRALEUSER, ENCOURSREALISATION,ENCOURSANALYSE, $dateMoins3mois));
$data = utf8_decode("numéro;Date de début de projet;Titre du projet;Mise à jour;référence interne;E-mail;Nom prénom du demandeur;Date de fin du projet");
$data .= "\n";
$originalDate = date('d-m-Y');
$nblignes = count($row);
//SUPPRESSION DE LA TABLE TEMPORAIRE SI ELLE EXISTE
$libcentrale= $manager->getSingle2("SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale AND pseudo=?", $pseudo);
if ($nblignes != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < $nblignes; $i++) {
        $idprojet = $row[$i]['idprojet'];
        if (!empty($row[$i]['refinterneprojet'])) {
            $refinterne = $row[$i]['refinterneprojet'];
        } else {
            $refinterne = "";
        }
//numéro;Date de la demande;Titre du projet;Mise à jour;référence interne;statut du projet;demandeur du projet;Porteur du projet;Acronyme;Date de fin;Date de fin proche   
            if ($row[$i]['idperiodicite_periodicite'] == JOUR) {
                $datedepart = strtotime($row[$i]['datedebutprojet']);
                $duree = ($row[$i]['dureeprojet']);
                $dateFin = date('Y-m-d', strtotime('+' . $duree . 'day', $datedepart));
            } elseif ($row[$i]['idperiodicite_periodicite'] == MOIS) {
                $datedepart = strtotime($row[$i]['datedebutprojet']);
                $duree = ($row[$i]['dureeprojet']);
                $dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
            } elseif ($row[$i]['idperiodicite_periodicite'] == ANNEE) {
                $datedepart = strtotime($row[$i]['datedebutprojet']);
                $duree = ($row[$i]['dureeprojet']);
                $dateFin = date('Y-m-d', strtotime('+' . $duree . 'year', $datedepart));
            }        
        $data .= "" .
                $row[$i]['numero'] . ";" .
                $row[$i]['datedebutprojet'] . ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($row[$i]['titre'])))) . ";" .
                $row[$i]['datemaj'] . ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($refinterne)))) . ";" .
                $row[$i]['mail'] . ";" .
                $row[$i]['nom'] . ";" .                
                $dateFin . "\n";               
    }
    header("Content-Type: application/csv");
    header("Content-disposition: attachment;filename=exportprojetencours_".$libcentrale.'_'.$originalDate.".csv");  
    print $data;
    exit;
} else {    
    echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/relance/' . $lang.'" )</script>';
    exit();
}
BD::deconnecter();