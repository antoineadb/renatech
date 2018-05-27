<?php
include_once '../outils/constantes.php';
define('MAXSIZE', 512000);
define('REPERTOIREIMG', '../styles/img/logoCentrales/');
$extensions = array('.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
if (!empty($_FILES)) {
    $dossier = REPERTOIREIMG;
    $extensionlogocentrale = strrchr(nomFichierValidesansAccent($_FILES['file']['name']), '.');
    if (filesize($_FILES['file']['tmp_name']) > MAXSIZE) {
        echo '<div id="errSizeFigure" style="  color: red;font-weight: bold;width: 360px;margin-left:20px">Error the file size is above 2Mo</b></div>';
        exit();
    } elseif (!in_array($extensionlogocentrale, $extensions)) {
        echo '<div id="errExtensionFigure" style="  color: red;font-weight: bold;width: 360px;margin-left:20px">Error!The file must be jpg, jpeg or png</b></div>';
        exit();
    }
    $folder = REPERTOIREIMG . nomFichierValidesansAccent($_FILES['file']['name']);
    $my_img = $_FILES['file']['tmp_name'];
    $filelogo = basename($my_img);
    if (strrchr($_FILES['file']['name'], '.') == '.jpg' || strrchr($_FILES['file']['name'], '.') == '.JPG' || strrchr($_FILES['file']['name'], '.') == '.jpeg' || strrchr($_FILES['file']['name'], '.') == '.JPEG') {
        $src_im = imagecreatefromjpeg($my_img);
        $size = GetImageSize($my_img);
        $src_w = $size[0];
        $src_h = $size[1];
        
        $fichierlogo = basename(nomFichierValidesansAccent($_FILES['file']['name']));
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichierlogo)) {
            chmod($dossier . $fichierlogo, 0777);
        }
        $arrayInfoImg = getimagesize($folder);
        $width = sizeLogo($arrayInfoImg, 75)[0];
        $height = sizeLogo($arrayInfoImg, 75)[1];
        echo $image = "<img src=" . '/' . REPERTOIRE . '/styles/img/logoCentrales/' . nomFichierValidesansAccent($_FILES['file']['name']) . " width='$width' height='$height' name='figure'  id='figure' >"
        . "<input type='text' value='figure' id='inputFigure' style='display:none'>";

        if (move_uploaded_file($my_img, $dossier . $filelogo)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné        
            chmod($dossier . $filelogo, 0777);
            unlink($dossier . basename($my_img));
        }
    } elseif (strrchr($_FILES['file']['name'], '.') == '.png' || strrchr($_FILES['file']['name'], '.') == '.PNG') {        
        $fichierlogo = basename(nomFichierValidesansAccent($_FILES['file']['name']));
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichierlogo)) {
            chmod($dossier . $fichierlogo, 0777);
        }
        $arrayInfoImg = getimagesize($folder);
        $width = sizeLogo($arrayInfoImg, 75)[0];
        $height = sizeLogo($arrayInfoImg, 75)[1];
        echo $image = "<img src=" . '/' . REPERTOIRE . '/styles/img/logoCentrales/' . nomFichierValidesansAccent($_FILES['file']['name']) . " width='$width' height='$height' name='figure'  id='figure' >"
        . "<input type='text' value='figure' id='inputFigure' style='display:none'>";

    }    
    
    if (move_uploaded_file($my_img, $dossier . $filelogo)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné        
        chmod($dossier . $filelogo, 0777);
        unlink($dossier . basename($my_img));
    }
}