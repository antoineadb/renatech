<?php
include_once '../outils/toolBox.php';

$arrayInfoImg =getimagesize("../uploadlogo/invivo.jpg");
echo '<pre>';print_r($arrayInfoImg);
$var = sizeLogo($arrayInfoImg, 80);
echo $var[0].'<br>'.$var[1];