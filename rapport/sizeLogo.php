<?php
//CALCUL DES DIMENSIONS DE LA FIGURE
$arrayInfoImg = getimagesize($folderlogo);
$width = sizeLogo($arrayInfoImg,80)[0];
$height = sizeLogo($arrayInfoImg,80)[1];
if(!empty($_FILES['filelogocentrale'])){
echo $image = "<img src=" . '/' . REPERTOIRE . '/uploadlogo/' . nomFichierValidesansAccent($_FILES['filelogocentrale']['name']) . " width='$width' height='$height' name='logocentrale'  id='logocentrale' style='float:left'> "
 . "<input type='text' value='logocentrale' id='inputLogoCentrale' style='display:none'>"
        ."<div id='ratio".rand(0,10000)."' style='margin-left:200px;'>".'ratio: 1 : '.round($height/$width,2)."</div>";
echo '<script>deleteSource();</script>';
}elseif(!empty($_FILES['filelogo'])){
echo $image = "<img src=" . '/' . REPERTOIRE . '/uploadlogo/' . $nomLogo . " width='$width' height='$height' name='logo'  id='logo' style='float:left'> "
 . "<input type='text' value='logo' id='inputLogo' style='display:none'>"
        . "<div id='ratio".rand(0,10000)."' style='margin-left:200px'>".'ratio: 1 : '.round($height/$width,2)."</div>";
echo '<script>deleteSource1();</script>';
}else{
    $logo =$manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $idprojet);
    echo $image = "<img src=" . '/' . REPERTOIRE .'/'. $logo  . " width='$width' height='$height' name='logocentrale'  id='logocentrale' style='float:left'> "
 . "<input type='text' value='logocentrale' id='inputLogoCentrale' style='display:none'>"
        . "<div id='ratio".rand(0,10000)."' style='margin-left:200px'>".'ratio: 1 : '.round($height/$width,2)."</div>";
    echo '<script>deleteSource();</script>';
    echo '<script>deleteSource1();</script>';
}