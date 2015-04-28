<?php
include_once '../outils/toolBox.php';

$str='Le nom des étoîles.jpg';
echo $str.'<br>';
echo nomFichierValidesansAccent($str);