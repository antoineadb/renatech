<?php

include_once '../class/Manager.php';
include_once '../class/secure/Cryptage.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

$login = Cryptage::crypt('renatech');
$passwd =Cryptage::crypt('R3natech#');
$port = Cryptage::crypt('5432');
$host = Cryptage::crypt('postgresql.lan');
$bdd = Cryptage::crypt('renatech_test');

try{
        $crnl  = "\r\n" ;
        $data  = "<?php".$crnl ;
        $data .= "define('TXT_LOGIN_BD_0','".$login."');".$crnl;
        $data .= "define('TXT_MDP_0','".$passwd."');".$crnl;
        $data .= "define('TXT_PORT_0','".$port."');".$crnl;
        $data .= "define('TXT_HOST_0','".$host."');".$crnl;
        $data .= "define('TXT_BDD_0','".$bdd."');".$crnl;
        $data .= '?>'.$crnl ;    
        $handle =fopen('../test/config_test.php','w+');
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