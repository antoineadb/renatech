<?php
//CALCUL DES DIMENSIONS DE LA FIGURE
$arrayInfoImg = getimagesize($folder);
$width = sizeLogo($arrayInfoImg,160)[0];
$height = sizeLogo($arrayInfoImg,160)[1]; 
echo $image = "<img src=" . '/' . REPERTOIRE . '/uploadlogo/' . nomFichierValidesansAccent($_FILES['file']['name']) . " width='$width' height='$height' name='figure'  id='figure' >"
        . "<input type='text' value='figure' id='inputFigure' style='display:none'>"
         . "<div id='ratio".rand(0,10000)."' style='margin-top:10px'>".'ratio: 1 : '.round($height/$width,2)."</div>";
