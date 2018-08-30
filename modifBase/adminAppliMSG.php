<?php
session_start();
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
include_once '../class/secure/Cryptage.php';
//------------------------------------------------------------------------------------------------------------------------------------------------
//SAUVEGARDE DE SECOURS DES INFOS EN BASE DE DONNEES
//------------------------------------------------------------------------------------------------------------------------------------------------
$login = Cryptage::crypt($_POST['login']);
$mdp = Cryptage::crypt($_POST['password']);
$host = Cryptage::crypt($_POST['host']);
$port = Cryptage::crypt($_POST['port']);
$chiffrement = Cryptage::crypt($_POST['chiffrement']);

//------------------------------------------------------------------------------------------------------------------------------------------------
// Création du fichier de configuration
//------------------------------------------------------------------------------------------------------------------------------------------------

try{    
    $crnl  = "\r\n" ;
    $data  = "<?php".$crnl ;
    $data .= "define('TXT_LOGIN_MSG','".$_POST['login']."');".$crnl;
    $data .= "define('TXT_MDP_MSG','".$_POST['password']."');".$crnl;
    $data .= "define('TXT_PORT_MSG','".$_POST['port']."');".$crnl;
    $data .= "define('TXT_HOST_MSG','".$_POST['host']."');".$crnl;
    $data .= "define('TXT_BDD_CHIFFREMENT_MSG','".strtolower($_POST['chiffrement'])."');".$crnl;
    $data .= '?>'.$crnl ;    
    $handle =fopen('../class/secure/config_email.php','w+');
    fwrite($handle,$data);
    fclose($handle);
    echo true;    
} catch (Exception $ex) {
    echo $ex->getCode().'<br>'.$ex->getMessage();
    echo false;
    die;
}



//------------------------------------------------------------------------------------------------------------------------------------------------
// Création d'un log
//------------------------------------------------------------------------------------------------------------------------------------------------
//$dateHeure, $infos, $nomPrenom, $statutProjet, $manager, $idcentrale
$infos ="Modification des paramètres de configuration de la messagerie et sauvegarde des paramètres en BD ";
createLogInfo(NOW, $infos, NOMUSER.' '.PRENOMUSER, "", $manager, null);