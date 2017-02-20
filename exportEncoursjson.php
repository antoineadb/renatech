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
    $pseudo=$_SESSION['pseudo'];
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
//------------------------------------------------------------------------
//-----RECUPERATION DU LIBELLE DE LA CENTRALE DU RESPONSABLE CENTRALE-----
//------------------------------------------------------------------------
$libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale , utilisateur ,loginpassword  WHERE
idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);


$data = utf8_decode("numéro;Date de début de projet;Titre du projet;Mise à jour;référence interne;E-mail;Nom prénom du demandeur;Date de fin du projet");
$data .= "\n";
//SUPPRESSION DE LA TABLE TEMPORAIRE SI ELLE EXISTE
if (is_file('tmp/projetarelancer.json')) {
    $json = file_get_contents("tmp/projetarelancer.json");
    $row = json_decode($json, true);
    $nblignes = count($row['items']);
if (count($nblignes) != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < $nblignes; $i++) {
        $idprojet = $row['items'][$i]['idprojet'];
                
        if (!empty($row['items'][$i]['refinterneprojet'])) {
            $refinterne = $row['items'][$i]['refinterneprojet'];
        } else {
            $refinterne = "";
        }
//numéro;Date de la demande;Titre du projet;Mise à jour;référence interne;statut du projet;demandeur du projet;Porteur du projet;Acronyme;Date de fin;Date de fin proche   
        
        $originalDate = date('d-m-Y');
        $data .= "" .
                $row['items'][$i]['numero'] . ";" .
                $row['items'][$i]['datedebutprojet'] . ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($row['items'][$i]['titre'])))) . ";" .
                $row['items'][$i]['datemaj'] . ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($refinterne)))) . ";" .
                $row['items'][$i]['mail'] . ";" .
                $row['items'][$i]['nom'] . ";" .                
                $row['items'][$i]['datefin'] . "\n";
                
    }
    $libcentrale= $manager->getSingle2("SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale AND pseudo=?", $pseudo);
// Déclaration du type de contenu    
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=exportprojetencours_" . $libcentrale . '_' . $originalDate . ".csv");
    print $data;
    exit;
} else {    
    echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/relance/' . $lang.'" )</script>';
    exit();
}
}
BD::deconnecter();