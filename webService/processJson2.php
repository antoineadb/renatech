<?php
var_dump($_POST);die;
header("Content-type: application/json");
$json = file_get_contents("php://input");
$obj = json_decode($json);
$response = array();
$response= $obj->field1;
$json_response = json_encode($response);
echo $json_response;

?>