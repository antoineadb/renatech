<?php

include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db);


if (!empty($_FILES)) {
    if (filesize($_FILES['file']['tmp_name']) > 4194304) {
        echo 'fichier > 4Mo ';
        exit();
    }
    $folder = '../uploadlogo/' . $_FILES['file']['name'];
    if (strrchr($_FILES['file']['name'], '.') == '.jpg' || strrchr($_FILES['file']['name'], '.') == '.JPG' || strrchr($_FILES['file']['name'], '.') == '.jpeg' || strrchr($_FILES['file']['name'], '.') == '.JPEG') {
        $my_img = $_FILES['file']['tmp_name'];
        $src_im = imagecreatefromjpeg($my_img);
        $size = GetImageSize($my_img);
        $src_w = $size[0];
        $src_h = $size[1];
        if (filesize($_FILES['file']['tmp_name']) > 819200 && filesize($_FILES['file']['tmp_name']) <= 2097152) { // fichier entre 801ko et 2 Mo
            $dossier = '../uploadlogo/';
            $filelogo = basename($my_img);
            $dst_w = 2700;
            $dst_h = round(($dst_w / $src_w) * $src_h);
            $dst_im = imagecreatetruecolor($dst_w, $dst_h);
            imagecopyresampled($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
            if (imagejpeg($dst_im, $folder)) {
                imagedestroy($dst_im);
                imagedestroy($src_im);
            } else {
                imagedestroy($dst_im);
                imagedestroy($src_im);
            }
        } elseif (filesize($_FILES['file']['tmp_name']) > 2097152 && filesize($_FILES['file']['tmp_name']) <= 4194304) { // fichier entre 2.0 1Mo et 4 Mo
            $dossier = '../uploadlogo/';
            $filelogo = basename($my_img);
            $dst_w = 2110;
            $dst_h = round(($dst_w / $src_w) * $src_h);
            $dst_im = imagecreatetruecolor($dst_w, $dst_h);
            imagecopyresampled($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
            if (imagejpeg($dst_im, $folder)) {
                imagedestroy($dst_im);
                imagedestroy($src_im);
            } else {
                imagedestroy($dst_im);
                imagedestroy($src_im);
            }
        } else {
            $dossier = '../uploadlogo/';
            $fichierlogo = basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichierlogo)) {
                chmod($dossier . $fichierlogo, 0777);
            }echo "<img src=" . $folder . " width='530' height='250'>";
            exit();
        }


        if (move_uploaded_file($my_img, $dossier . $filelogo)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné        
            chmod($dossier . $filelogo, 0777);
            unlink($dossier . basename($my_img));
        }
    } elseif (strrchr($_FILES['file']['name'], '.') == '.png' || strrchr($_FILES['file']['name'], '.') == '.PNG') {
        if (filesize($_FILES['file']['tmp_name']) > 819200 && filesize($_FILES['file']['tmp_name']) <= 2097152) { // fichier entre 801ko et 2 Mo
            $my_img = $_FILES['file']['tmp_name'];
            $src_im = imagecreatefrompng($my_img);
            $size = GetImageSize($my_img);
            $src_w = $size[0];
            $src_h = $size[1];
            $dst_w = 440; //$dst_w = 629;
            $dst_h = round(($dst_w / $src_w) * $src_h);
            $dst_im = imagecreatetruecolor($dst_w, $dst_h);
            imagecopyresampled($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
            if (imagepng($dst_im, $folder)) {
                imagedestroy($dst_im);
                imagedestroy($src_im);
            } else {
                imagedestroy($dst_im);
                imagedestroy($src_im);
            }
        } elseif (filesize($_FILES['file']['tmp_name']) > 2097152 && filesize($_FILES['file']['tmp_name']) <= 4194304) { // fichier entre 2.0 1Mo et 4 Mo
            $my_img = $_FILES['file']['tmp_name'];
            $src_im = imagecreatefrompng($my_img);
            $size = GetImageSize($my_img);
            $src_w = $size[0];
            $src_h = $size[1];
            $dst_w = 461; //$dst_w = 629;
            $dst_h = round(($dst_w / $src_w) * $src_h);
            $dst_im = imagecreatetruecolor($dst_w, $dst_h);
            imagecopyresampled($dst_im, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
            if (imagepng($dst_im, $folder)) {
                imagedestroy($dst_im);
                imagedestroy($src_im);
            } else {
                imagedestroy($dst_im);
                imagedestroy($src_im);
            }
        } else {
            $dossier = '../uploadlogo/';
            $fichierlogo = basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichierlogo)) {
                chmod($dossier . $fichierlogo, 0777);
            }echo "<img src=" . $folder . " width='530' height='250'>";
            exit();
        }
        $dossier = '../uploadlogo/';
        $filelogo = basename($my_img);
        if (move_uploaded_file($my_img, $dossier . $filelogo)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné        
            chmod($dossier . $filelogo, 0777);
            unlink($dossier . basename($my_img));
        }
    }
} echo "<img src=" . $folder . " width='530' height='250'>";
