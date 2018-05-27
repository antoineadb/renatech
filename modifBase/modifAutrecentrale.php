<?php

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if(isset($_GET['idcentrale'])&&!empty($_GET['idcentrale'])){    
    $idcentrale = substr($_GET['idcentrale'],-1);
    echo  $idcentrale;
}else {
    echo 'FALSE';    
}
