<?php
session_start();
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
include_once '../class/Manager.php';
include_once '../class/secure/Cryptage.php';
//------------------------------------------------------------------------------------------------------------------------------------------------
//SAUVEGARDE DE SECOURS DES INFOS EN BASE DE DONNEES
//------------------------------------------------------------------------------------------------------------------------------------------------
$login = Cryptage::crypt($_POST['login']);
$mdp = Cryptage::crypt($_POST['password']);
$host = Cryptage::crypt($_POST['host']);
$port = Cryptage::crypt($_POST['port']);
$db = BD::connecter();
$manager = new Manager($db);
$datetime =  Date('Y-m-d');
$param = new Params("Paramètre de configuration de la base de donnée", $login, $mdp, $host, $port,$datetime);
$manager->addParams($param);
$repertoire = explode('/', $_SERVER['PHP_SELF']);
BD::deconnecter();
//------------------------------------------------------------------------------------------------------------------------------------------------
// Création du fichier de configuration
//------------------------------------------------------------------------------------------------------------------------------------------------
$bdd = $repertoire[1];
$crnl  = "\r\n" ;
$data  = "<?php".$crnl ;
$data .= "define('TXT_LOGIN_BD','".$_POST['login']."');".$crnl;
$data .= "define('TXT_MDP','".$_POST['password']."');".$crnl;
$data .= "define('TXT_PORT','".$_POST['port']."');".$crnl;
$data .= "define('TXT_HOST','".$_POST['host']."');".$crnl;
$data .= '?>'.$crnl ;
try{
    $handle =fopen('../class/secure/config.php','w+');     
} catch (Exception $ex) {
    echo $ex->getCode().'<br>'.$ex->getMessage();
    die;
}
try{
    fwrite($handle,$data);
    echo true;
} catch (Exception $ex) {
    echo false;
    echo $ex->getCode().'<br>'.$ex->getMessage();
}
fclose($handle);
//------------------------------------------------------------------------------------------------------------------------------------------------
// Création d'un log
//------------------------------------------------------------------------------------------------------------------------------------------------
//$dateHeure, $infos, $nomPrenom, $statutProjet, $manager, $idcentrale
$infos ="Modification des paramètres de configuration de la base de données et sauvegarde des paramètres en BD ";
createLogInfo(NOW, $infos, NOMUSER.' '.PRENOMUSER, "", $manager, null);