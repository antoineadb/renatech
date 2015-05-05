<?php
session_start();
include_once '../class/Manager.php';
include_once '../outils/toolBox.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$filename = "../templates/template.html";
$arraycentrale = $manager->getList2("SELECT libellecentrale,idcentrale FROM centrale,loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword AND  idcentrale_centrale = idcentrale AND pseudo=?", $_SESSION['pseudo']);
if(isset($_POST['annee'])&& !empty($_POST['annee'])){
    if($_POST['annee']==1){
        $annee = 2012;
    }else{
        $annee = (int) $_POST['annee'];
    }
}else{
    $annee = date('Y');
}
 if($_SESSION['idTypeUser']==ADMINNATIONNAL){
     $centrale =(int)$_POST['centrale'];
     $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $centrale);
 }else{
     $centrale =(int)$arraycentrale[0]['idcentrale'];
     $libellecentrale =$arraycentrale[0]['libellecentrale'];
 }

$arrayIdProjet = $manager->getListbyArray("SELECT r.idprojet  FROM rapport r, projet p, concerne WHERE r.idprojet = p.idprojet and idstatutprojet_statutprojet!=? AND idprojet_projet = p.idprojet "
            . "and idcentrale_centrale=? and EXTRACT(YEAR from datecreation)>=? order by thematics asc",array(REFUSE,$centrale,$annee));
if(isset($_POST['ext'])&& $_POST['ext']=='.doc'){
    header("Content-Type:application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    header('Content-Disposition: attachment; filename=rapport_' . time() . '_' . $libellecentrale . '.doc');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
}elseif(isset($_POST['ext'])&& $_POST['ext']=='.rtf'){
    header("Content-Type:application/rtf");
    header('Content-Disposition: attachment; filename=rapport_' . time() . '_' . $libellecentrale . '.rtf');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
}

for ($i = 0; $i < count($arrayIdProjet); $i++) {
    $arrayrapport = $manager->getList2("select * from rapport where idprojet=?", $arrayIdProjet[$i]['idprojet']);
    if (!file_exists($filename)) {
    echo "Undefined file";
    exit();
}
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
//                  TRAITEMENT DU LOGO DE LA CENTRALE
//---------------------------------------------------------------------------------------------------------------------------------------------------------    
    if (!empty($arrayrapport[0]['logocentrale'])) {
        $logocentrale = '/uploadlogo/' . $arrayrapport[0]['logocentrale'];
    } else {
        $logocentrale = '/' . nomFichierValidesansAccent($manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $arrayIdProjet[$i]['idprojet']));
    }
    $logcentrale = strip_tags($arrayrapport[0]['logocentrale']);
    if (isset($logcentrale) && !empty($logcentrale)) {
        $arrayInfoImgLogoCentrale = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/uploadlogo/" . nomFichierValidesansAccent($arrayrapport[0]['logocentrale']));
    } else {
        $arrayInfoImgLogoCentrale = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/" . $logocentrale);
    }

    $widthLogoCentrale = sizeLogo($arrayInfoImgLogoCentrale, 60)[0];
    $heightLogoCentrale = sizeLogo($arrayInfoImgLogoCentrale, 60)[1];
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//                  TRAITEMENT DES FIGURES
//---------------------------------------------------------------------------------------------------------------------------------------------------------
    if (!empty($arrayrapport[0]['figure'])) {
        $figure = '/uploadlogo/' . $arrayrapport[0]['figure'];
    } else {
        $figure = '';
    }
    if (!empty($arrayrapport[0]['legend'])) {
        $caption = $arrayrapport[0]['legend'];
    } else {
        $caption = '';
    }
    if (isset($arrayrapport[0]['figure']) && !empty(($arrayrapport[0]['figure']))) {
        //CALCUL DES DIMENSIONS DE LA FIGURE
        $arrayInfoImg = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/uploadlogo/" . nomFichierValidesansAccent($arrayrapport[0]['figure'] . ""));
        $widthfigure = sizeLogo($arrayInfoImg, 185)[0];
        $heightfigure = sizeLogo($arrayInfoImg, 185)[1];
    }else{
        $widthfigure =10;
        $heightfigure =10;
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//                  TRAITEMENT DU LOGO DU LABORATOIRE
//---------------------------------------------------------------------------------------------------------------------------------------------------------    
    if (!empty($arrayrapport[0]['logo'])) {
        $logolabo = '/uploadlogo/' . $arrayrapport[0]['logo'];
    } else {
        $logolabo = '/' . $manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $arrayIdProjet[$i]['idprojet']);
    }
    $logLabo = strip_tags($arrayrapport[0]['logo']);
    if (isset($logLabo) && !empty($logLabo)) {
        $arrayInfoImgLogoLabo = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/uploadlogo/" . $arrayrapport[0]['logo']);
    } else {
        $arrayInfoImgLogoLabo = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/" . REPERTOIRE . "/" . $logolabo);
    }
    $widthLogoLab = sizeLogo($arrayInfoImgLogoLabo, 60)[0];
    $heightLogoLab = sizeLogo($arrayInfoImgLogoLabo, 60)[1];
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
    $content = str_replace('##CAPTION##', $caption, $content);
    $content = str_replace('##FIGURES##', $figure, $content);
    $content = str_replace('##valorization##', $arrayrapport[0]['valorization'], $content);
    $content = str_replace('##technologicalwc##', $arrayrapport[0]['technologicalwc'], $content);
    $content = str_replace('##repertoire##', '/' . REPERTOIRE . '/', $content);
    
    echo utf8_decode($content);
}
