<?php
session_start();
include_once '../outils/constantes.php';
$extensions = array('.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
if (!empty($_FILES)) {
    if (!empty($_FILES['filelogocentrale']['name'])) {
        $extensionlogocentrale = strrchr($_FILES['filelogocentrale']['name'], '.');
    } else {
        $extensionlogocentrale = strrchr($_FILES['filelogo']['name'], '.');
    }
    if (!empty($_FILES['filelogocentrale']['tmp_name'])) {
        $poid = filesize($_FILES['filelogocentrale']['tmp_name']);
    } else {
        $poid = filesize($_FILES['filelogo']['tmp_name']);
    }

    if ($poid > 2097152) {//SI >2MO ON SORT        
        echo '<div id="errSize" style="  color: red;font-weight: bold;width: 360px;">Error the file size is above 2Mo</b></div>';
        exit();
    } elseif (!in_array($extensionlogocentrale, $extensions)) {
        echo '<div id="errExtension" style="  color: red;font-weight: bold;width: 360px;">Error!The file must be jpg, jpeg or png</b></div>';
        exit();
    }
    if (!empty($_FILES['filelogocentrale']['tmp_name'])) {
        $nomLogocentrale  = nomFichierValidesansAccent($_FILES['filelogocentrale']['name']);
        $folderlogo = '../uploadlogo/' . $nomLogocentrale;
    } else {
        $nomLogo  = nomFichierValidesansAccent($_FILES['filelogo']['name']);
        $folderlogo = '../uploadlogo/' .$nomLogo;
    }

    if (!empty($_FILES['filelogocentrale']['name'])) {
        $ext = strrchr($_FILES['filelogocentrale']['name'], '.');
    } else {
        $ext = strrchr($_FILES['filelogo']['name'], '.');
    }

    if ($ext == '.jpg' || $ext == '.JPG' || $ext == '.jpeg' || $ext == '.JPEG') {
        if (!empty($_FILES['filelogocentrale']['tmp_name'])) {
            $my_img = nomFichierValidesansAccent($_FILES['filelogocentrale']['tmp_name']);
        } else {
            $my_img = nomFichierValidesansAccent($_FILES['filelogo']['tmp_name']);
        }
        $src_im = imagecreatefromjpeg($my_img);
        $size = GetImageSize($my_img);
        $src_w = $size[0];
        $src_h = $size[1];
        $dossier = '../uploadlogo/';
        $filelogo = basename($my_img);
        //$filelogo = basename($my_img).'_'.time();
        $dst_w = 2700;
        $dst_h = round(($dst_w / $src_w) * $src_h);
        $dst_im = imagecreatetruecolor($dst_w, $dst_h);
        imagecopyresampled($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        if (imagejpeg($dst_im, $folderlogo)) {
            imagedestroy($dst_im);
            imagedestroy($src_im);
        } else {
            imagedestroy($dst_im);
            imagedestroy($src_im);
        }
        if (move_uploaded_file($my_img, $folderlogo)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné            
            chmod( $folderlogo, 0777);
            //unlink($dossier . basename($my_img));
        }
        
        include_once 'sizeLogo.php';
        exit();
    } elseif ($ext == '.png' || $ext == '.PNG') {
        if (!empty($_FILES['filelogocentrale']['tmp_name'])) {
            $my_img =  nomFichierValidesansAccent($_FILES['filelogocentrale']['tmp_name']);
        } else {
            $my_img = nomFichierValidesansAccent($_FILES['filelogo']['tmp_name']);
        }
        $src_im = imagecreatefrompng($my_img);
        $size = GetImageSize($my_img);
        $dossier = '../uploadlogo/';
        $filelogo = basename($my_img);
        $src_w = $size[0];
        $src_h = $size[1];
        $dst_w = 440; //$dst_w = 629;
        $dst_h = round(($dst_w / $src_w) * $src_h);
        $dst_im = imagecreatetruecolor($dst_w, $dst_h);
        imagecopyresampled($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        if (imagepng($dst_im, $folderlogo)) {
            imagedestroy($dst_im);
            imagedestroy($src_im);
        } else {
            imagedestroy($dst_im);
            imagedestroy($src_im);
        }
        if (move_uploaded_file($my_img, $dossier . $filelogo)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné            
            chmod($dossier . $filelogo, 0777);
            unlink($dossier . basename($my_img));
        }
        include_once 'sizeLogo.php';
        exit();
    }     
} else {
    $idprojet=$manager->getSingle2('select idprojet from projet where numero=?',$_SESSION['numProjet']);
    $folderlogo ='../'.$manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $idprojet);
}
include_once 'sizeLogo.php';
