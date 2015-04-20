<?php
//CALCUL DES DIMENSIONS DE LA FIGURE
$arrayInfoImg = getimagesize($folder);
 if ($arrayInfoImg[1] > 225) {//si Height >230px
            $facteurW = 225 / $arrayInfoImg[1];
            $width = $facteurW * $arrayInfoImg[0].'px';
            $height = $facteurW * $arrayInfoImg[1].'px';
        if ($width > 420) {
            $facteurW = 420 / $width;
            $height = $facteurW * $height.'px';
            $width = '420px';
        }
    } elseif ($arrayInfoImg[0] > 420) {//si Width >700px
        $facteurW = 420 / $arrayInfoImg[0];
        $width = $facteurW * $arrayInfoImg[0].'px';
        $height = $facteurW * $arrayInfoImg[1].'px';
        if ($height > 225) {
            $facteurW = 225 / $height;
            $width = $facteurW * $width.'px';
            $height = '225px';
        }
    } else {
        $width = $arrayInfoImg[0];
        $height = $arrayInfoImg[1];
    }

echo $image = "<img src=" . '/' . REPERTOIRE . '/uploadlogo/' . nomFichierValide($_FILES['file']['name']) . " width='$width' height='$height' name='figure'  id='figure'><input type='text' value='figure' id='inputFigure' style='display:none'>";
