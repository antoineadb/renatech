<?php
/*/*Créer API REST JSON en PHP
header('content-type: application/json');
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);



if(!empty($_POST['centrale'])){
    $result = $manager->getList2("select * from centrale WHERE libellecentrale=?",$_POST['centrale']);
}else{
    $result = $manager->getList("select * from centrale");
}


echo json_encode($result);

$db = BD::deconnecter();
 * 
 */

$json = file_get_contents("https://www.renatech.org/projet-dev/webService/client.php");
$parsee= json_decode($json,true);
echo ($json);

foreach ($parsee['content'] as $result){
    echo '<div class="centrale">';    
    echo '<div class="idcentrale">';
    echo '<p>'.$result['idcentrale'].'</p></div>';
    echo '<div class="nomcentrale">';
    echo '<p>'.$result['libellecentrale'].'</p></div>';
    echo '<div class="mailcentrale1">';
    echo '<p>'.$result['email1'].'</p></div>';
    echo '<div class="mailcentrale2">';
    echo '<p>'.$result['email2'].'</p></div>';
    echo '<div class="mailcentrale3">';
    echo '<p>'.$result['email3'].'</p></div>';
    echo '<div class="mailcentrale4">';
    echo '<p>'.$result['email4'].'</p></div>';
    
    
    
    
}