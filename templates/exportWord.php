<?php

include_once '../class/Manager.php';
include_once '../outils/toolBox.php';
include_once '../outils/constantes.php';

$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$filename = "../templates/template.html";
$idprojet = 852;
$arrayrapport = $manager->getList2("select * from rapport where idprojet=?", $idprojet);
if (file_exists($filename)) {
    //On ouvre le modele
    $fp = fopen($filename, 'r');
    $content = fread($fp, filesize($filename));
    fclose($fp);
    if (!empty($arrayrapport[0]['collaborator'])) {
        $collaborators = $arrayrapport[0]['collaborator'];
    } else {
        $collaborators = '';
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------    
//                  TRAITEMENT DU LOGO DE LA CENTRALE, IMOSSIBLE DE LE CHANGER DANS LE RAPPORT
//---------------------------------------------------------------------------------------------------------------------------------------------------------    
    if (!empty($arrayrapport[0]['logocentrale'])) {
        $logocentrale = '/uploadlogo/' . $arrayrapport[0]['logocentrale'];
    } else {
        $logocentrale = '/' . $manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $idprojet);
    }
    $logcentrale = strip_tags($arrayrapport[0]['logocentrale']);
    if (isset($logcentrale) && !empty($logcentrale)) {
        $arrayInfoImgLogoCentrale = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/uploadlogo/" . $arrayrapport[0]['logocentrale']);
    } else {
        $arrayInfoImgLogoCentrale = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/" . $logocentrale);
    }

    if ($arrayInfoImgLogoCentrale[0] > 100) {    //CALCUL DES DIMENSIONS DU LOGO DU LABORATOIRE
        $ratio = $arrayInfoImgLogoCentrale[1] / $arrayInfoImgLogoCentrale[0];
        $facteur = 100 / $arrayInfoImgLogoCentrale[0];
        $widthLogoCentrale = $facteur * $arrayInfoImgLogoCentrale[0];
        $heightLogoCentrale = $facteur * $arrayInfoImgLogoCentrale[1];
    } else {
        $widthLogoCentrale = $arrayInfoImgLogoCentrale[0];
        $heightLogoCentrale = $arrayInfoImgLogoCentrale[1];
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//                  TRAITEMENT DES FIGURES
//---------------------------------------------------------------------------------------------------------------------------------------------------------
    if (!empty($arrayrapport[0]['figure'])) {
        $figure = '/uploadlogo/' . $arrayrapport[0]['figure'];
    } else {
        $figure = '';
    }

    if (isset($arrayrapport[0]['figure'])) {
        //CALCUL DES DIMENSIONS DE LA FIGURE
        $arrayInfoImg = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/uploadlogo/" . $arrayrapport[0]['figure'] . "");

        if ($arrayInfoImg[1] > 200) {//si Height >200px
            $facteur = 200 / $arrayInfoImg[1];
            $heightfigure = $facteur * $arrayInfoImg[1];
        } else {
            $heightfigure = $arrayInfoImg[1];
        }
        if ($arrayInfoImg[0] > 1500) {//si Width >700px
            $facteurW = 1500 / $arrayInfoImg[0];
            $widthfigure = $facteurW * $arrayInfoImg[0];
        } else {
            $widthfigure = $arrayInfoImg[0];
        }
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//                  TRAITEMENT DU LOGO DU LABORATOIRE
//---------------------------------------------------------------------------------------------------------------------------------------------------------    
    if (!empty($arrayrapport[0]['logo'])) {
        $logolabo = '/uploadlogo/' . $arrayrapport[0]['logo'];
    } else {
        $logolabo = '/' . $manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $idprojet);
    }
    $logLabo = strip_tags($arrayrapport[0]['logo']);
    if (isset($logLabo) && !empty($logLabo)) {
        $arrayInfoImgLogoLabo = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/uploadlogo/" . $arrayrapport[0]['logo']);
    } else {
        $arrayInfoImgLogoLabo = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/" . $logolabo);
    }

    if ($arrayInfoImgLogoLabo[0] > 100) {    //CALCUL DES DIMENSIONS DU LOGO DU LABORATOIRE
        $ratio = $arrayInfoImgLogoLabo[1] / $arrayInfoImgLogoLabo[0];
        $facteur = 100 / $arrayInfoImgLogoLabo[0];
        $widthLogoLab = $facteur * $arrayInfoImgLogoLabo[0];
        $heightLogoLab = $facteur * $arrayInfoImgLogoLabo[1];
    } else {
        $widthLogoLab = $arrayInfoImgLogoLabo[0];
        $heightLogoLab = $arrayInfoImgLogoLabo[1];
    }




    $logorenatech = 'https://' . $_SERVER['HTTP_HOST'] . '/' . REPERTOIRE . '/' . 'styles/img/logo-renatech.jpg';
    $logoCentrale = 'https://' . $_SERVER['HTTP_HOST'] . '/' . REPERTOIRE . $logocentrale . '';
    $figure = 'https://' . $_SERVER['HTTP_HOST'] . '/' . REPERTOIRE . $figure . '';
    $logolabo = 'https://' . $_SERVER['HTTP_HOST'] . '/' . REPERTOIRE . $logolabo . '';
    $content = str_replace('##widthfigure##', $widthfigure, $content);
    $content = str_replace('##heightfigure##', $heightfigure, $content);
    $content = str_replace('##widthLogoLab##', $widthLogoLab, $content);
    $content = str_replace('##heightLogoLab##', $heightLogoLab, $content);    
    $content = str_replace('##widthLogoCentrale##', $widthLogoCentrale, $content);
    $content = str_replace('##heightLogoCentrale##', $heightLogoCentrale, $content);    
    $content = str_replace('##logorenatech##', $logorenatech, $content);
    $content = str_replace('##LOGOCENTRALE##', $logoCentrale, $content);
    $content = str_replace('##LOGOLABO##', $logolabo, $content);
    $content = str_replace('##Authors##', $arrayrapport[0]['author'], $content);
    $content = str_replace('##Entity##', $arrayrapport[0]['entity'], $content);
    $content = str_replace('##TownCountry##', $arrayrapport[0]['villepays'], $content);
    $content = str_replace('##CNRSinstituteInterest##', $arrayrapport[0]['instituteinterest'], $content);
    $content = str_replace('##FundingSource##', $arrayrapport[0]['fundingsource'], $content);
    $content = str_replace('##Collaborators##', $collaborators, $content);
    $content = str_replace('##Title##', removeDoubleQuote($arrayrapport[0]['title']), $content);
    $content = str_replace('##thematics##', $arrayrapport[0]['thematics'], $content);
    $content = str_replace('##startingdate##', $arrayrapport[0]['startingdate'], $content);
    $content = str_replace('##objectif##', $arrayrapport[0]['objectif'], $content);
    $content = str_replace('##Results##', $arrayrapport[0]['results'], $content);
    $content = str_replace('##FIGURES##', $figure, $content);
    $content = str_replace('##valorization##', $arrayrapport[0]['valorization'], $content);
    $content = str_replace('##technologicalwc##', $arrayrapport[0]['technologicalwc'], $content);
    $content = str_replace('##repertoire##', '/' . REPERTOIRE . '/', $content);



    header("Content-Type: application/msword");
    header('Content-Disposition: attachment; filename=rapport.doc');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

    echo utf8_decode($content);
} else {
    echo "Undefined file";
}

    