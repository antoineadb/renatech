<?php

include_once '../class/Manager.php';
include_once '../outils/constantes.php';

$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$idprojet = 1435;
$arrayrapport = $manager->getList2("select * from rapport where idprojet=?", $idprojet);
$filename = "templates/template.html";
$fichier = "rapportAnnuel.doc";

if(!empty($arrayrapport[0]['collaborator'])){
    $collaborators=$arrayrapport[0]['collaborator'];
}else{
    $collaborators='';
}
if (file_exists($filename)) {
    $fp = fopen($filename, 'r');
    $content = fread($fp, filesize($filename));
    fclose($fp);

    $logorenatech = "<img src='logo-renatech.jpg' alt='logorenatech'>";
    //$content0 = file_get_contents('template.html');
    $content = str_replace('##LOGOCENTRALE##', $arrayrapport[0]['logocentrale'], $content);
    $content = str_replace('##LOGO##', $arrayrapport[0]['logo'], $content);
    $content = str_replace('##Entity##', $arrayrapport[0]['entity'], $content);
    $content =  str_replace('##TownCountry##', $arrayrapport[0]['villepays'], $content);
    $content = str_replace('##CNRSinstituteInterest##', $arrayrapport[0]['instituteinterest'], $content);
    $content = str_replace('##FundingSource##', $arrayrapport[0]['fundingsource'], $content);
    $content = str_replace('##Collaborators##', $collaborators, $content);
    $content = str_replace('##Title##', $arrayrapport[0]['title'], $content);
    $content = str_replace('##thematics##', $arrayrapport[0]['thematics'], $content);
    $content = str_replace('##startingdate##', $arrayrapport[0]['startingdate'], $content);
    $content = str_replace('##objectif##', $arrayrapport[0]['objectif'], $content);
    $content = str_replace('##Results##', $arrayrapport[0]['results'], $content);
    $content = str_replace('##FIGURES##', $arrayrapport[0]['figure'], $content);
    $content = str_replace('##valorization##', $arrayrapport[0]['valorization'], $content);
    $content = str_replace('##technologicalwc##', $arrayrapport[0]['technologicalwc'], $content);
    $content = str_replace('##repertoire##', '/'.REPERTOIRE.'/', $content);
    $content = str_replace('##logorenatech##', $logorenatech, $content);
    

    header("Content-Type: application/msword");
    header('Content-Disposition: attachment; filename=rapportAnnuel.doc');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

    /*header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: must-revalidate, post-check=1, pre-check=0');*/
    echo $content;
}
BD::deconnecter();
