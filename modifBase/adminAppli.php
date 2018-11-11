<?php
session_start();
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
include_once '../class/secure/Cryptage.php';
//------------------------------------------------------------------------------------------------------------------------------------------------
//SAUVEGARDE DE SECOURS DES INFOS EN BASE DE DONNEES
//------------------------------------------------------------------------------------------------------------------------------------------------

$login = Cryptage::crypt($_POST['login']);
$password =Cryptage::crypt($_POST['password']);
$port = Cryptage::crypt($_POST['port']);
$host = Cryptage::crypt($_POST['host']);
$bdd = $_POST['bdd'];

//------------------------------------------------------------------------------------------------------------------------------------------------
// Création du fichier de configuration
//------------------------------------------------------------------------------------------------------------------------------------------------

try{
    switch ($bdd){
        case 'renatech_test':
        $crnl  = "\r\n" ;
        $data  = "<?php".$crnl ;
        $data .= "define('TXT_LOGIN_BD_0','".$login."');".$crnl;
        $data .= "define('TXT_MDP_0','".$password."');".$crnl;
        $data .= "define('TXT_PORT_0','".$port."');".$crnl;
        $data .= "define('TXT_HOST_0','".$host."');".$crnl;
        $data .= "define('TXT_BDD_0','".Cryptage::crypt($bdd)."');".$crnl;
        $data .= '?>'.$crnl ;    
        $handle =fopen('../class/secure/config_0.php','w+');
        break;
        case 'renatech_preprod':
        $crnl  = "\r\n" ;
        $data  = "<?php".$crnl ;
        $data .= "define('TXT_LOGIN_BD_1','".$login."');".$crnl;
        $data .= "define('TXT_MDP_1','".$password."');".$crnl;
        $data .= "define('TXT_PORT_1','".$port."');".$crnl;
        $data .= "define('TXT_HOST_1','".$host."');".$crnl;
        $data .= "define('TXT_BDD_1','".Cryptage::crypt($bdd)."');".$crnl;
        $data .= '?>'.$crnl ;    
        $handle =fopen('../class/secure/config_1.php','w+');     
        break;
        case 'renatech':
        $crnl  = "\r\n" ;
        $data  = "<?php".$crnl ;
        $data .= "define('TXT_LOGIN_BD_2','".$_POST['login']."');".$crnl;
        $data .= "define('TXT_MDP_2','".$_POST['password']."');".$crnl;
        $data .= "define('TXT_PORT_2','".$port."');".$crnl;
        $data .= "define('TXT_HOST_2','".$host."');".$crnl;
        $data .= "define('TXT_BDD_2','".Cryptage::crypt($bdd)."');".$crnl;
        $data .= '?>'.$crnl ;    
        $handle =fopen('../class/secure/config_2.php','w+');   
        break;
    }
    
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