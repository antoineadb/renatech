<?php
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../decide-lang.php';
if(isset($_GET['centrale'])&&!empty($_GET['centrale'])){        
    echo TXT_QUESTIONCPARTCONNU.$_GET['centrale'];
}else {
    echo 'FALSE';    
}

