<?php
session_start();
include_once '../outils/toolBox.php';
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../decide-lang.php';

/*if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {  
    header('Location: /' . REPERTOIRE . '/Login_Error/'.$lang);
}*/
$resultat =$manager->getListbyArray("SELECT * FROM PROJET WHERE datedebutprojet BETWEEN ? AND ?",array('2018-09-01','2018-10-10'));
$data = json_encode($resultat);
var_dump($manager->getListbyArray("SELECT * FROM loginpassword  order by idlogin asc limit ?",array(2)));die;

 $url="https://www.renatech.org/projet-dev/webService/server.php";
// 3.- fire
$result = sendRequest($data,$url);
 
// 4.- dump result
echo $result;
die();

function sendRequest($data,$url)
{
    $postdata = http_build_query(array('data'=>$data));
    $opts = array('http' =>
      array(
          'method'  => 'POST',
          'header'  => "Content-type: application/x-www-form-urlencoded \r\n",
          'content' => $postdata,
          'ignore_errors' => true,
          'timeout' =>  10,
      )
    );
    $context  = stream_context_create($opts);
    return file_get_contents($url, false, $context);
}

$manager->getListbyArray("SELECT * FROM loginpassword  order by idlogin asc limit ?",array(2));
