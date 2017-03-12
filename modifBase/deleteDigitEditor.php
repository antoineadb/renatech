<?php

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if(isset($_GET['digit'])&&!empty($_GET['digit'])){        
    //todo
    
    echo  'TRUE';
}else {
    echo 'FALSE';    
}
