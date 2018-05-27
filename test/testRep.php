<?php

$repertoire = explode("/", $_SERVER["PHP_SELF"]);

echo  '/var/www/rtb/html/' . $repertoire[1] . '/class/Manager.php<br><br>';

echo $_SERVER['DOCUMENT_ROOT'].'/'.$repertoire[1]. '/class/Manager.php<br><br>';
echo '<pre>';print_r($_SERVER);echo '</pre>';