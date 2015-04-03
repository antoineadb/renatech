<?php
//CALCUL DES DIMENSIONS DE LA FIGURE
$arrayInfoImg = getimagesize($folder);
if ($arrayInfoImg[1] > 225) {//si Height >450px
    $facteur = 225 / $arrayInfoImg[1];
    $height = $facteur * $arrayInfoImg[1] . 'px';
    $width = $facteur * $arrayInfoImg[0] . 'px';
    if ($arrayInfoImg[0] > 420) {//si width >920px
        $facteur = 420 / $arrayInfoImg[0];
        $height = $facteur * $arrayInfoImg[1] . 'px';
        $width = $facteur * $arrayInfoImg[0] . 'px';
    }
} elseif ($arrayInfoImg[0] > 420) {//si width >920px
    $facteur = 420 / $arrayInfoImg[0];
    $height = $facteur * $arrayInfoImg[1] . 'px';
    $width = $facteur * $arrayInfoImg[0] . 'px';
    if ($arrayInfoImg[1] > 225) {//si Height >450px
        $facteur = 225 / $arrayInfoImg[1];
        $height = $facteur * $arrayInfoImg[1] . 'px';
        $width = $facteur * $arrayInfoImg[0] . 'px';
    }
} else {
    $height = $arrayInfoImg[1] . 'px';
    $width = $arrayInfoImg[0] . 'px';
}
echo $image = "<img src=" . '/' . REPERTOIRE . '/uploadlogo/' . $_FILES['file']['name'] . " width='$width' height='$height' name='figure'  id='figure'><input type='text' value='figure' id='inputFigure' style='display:none'>";
