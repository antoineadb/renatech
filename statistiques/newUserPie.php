<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$string0 = '';
$currentyear = date('Y');
$arraylibellecentrale=$manager->getList2("select libellecentrale from centrale where idcentrale!=? and masquecentrale!=true order by idcentrale asc", IDCENTRALEAUTRE);
$donneeUser='';
if (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeNewUserHolder'])) {
    $donneeuserindustriel = $manager->getSingle("SELECT count(distinct id) as nb FROM tmpnbnewUserIndust");
    $donneeuseracaexterne = $manager->getSingle("SELECT count(distinct id) FROM tmpnbnewUserExterne");    
    array_push($arraylibellecentrale, array("libellecentrale" => TXT_ACADEMIQUEEXTERNE));
    array_push($arraylibellecentrale, array("libellecentrale" => TXT_INDUSTRIEL));
    $nbInterne= $manager->getSingle("select count(distinct idutilisateur) from tmpnbnewUser");
    $nbtotaluser = $nbInterne + $donneeuserindustriel + $donneeuseracaexterne;
    for ($i = 0; $i < count($arraylibellecentrale); $i++) {
        if (isset($arraylibellecentrale[$i]['libellecentrale'])) {
            $donneeUser = $manager->getSinglebyArray("select count(idutilisateur) from tmpnbnewUser  WHERE libellecentrale=? and datecreation=?", array($arraylibellecentrale[$i]['libellecentrale'],  $_GET['anneeNewUserHolder']));
            if ($arraylibellecentrale[$i]['libellecentrale'] == TXT_ACADEMIQUEEXTERNE) {
                $donneeUser = $donneeuseracaexterne;
            } 
            if ($arraylibellecentrale[$i]['libellecentrale'] == TXT_INDUSTRIEL) {
                $donneeUser = $donneeuserindustriel;
            }
        }
        if ($donneeUser != 0) {
            $libelle = $arraylibellecentrale[$i]['libellecentrale'];
            $string0.='["' . $libelle . '",' . $donneeUser . '],';
        }
    }
    $title = TXT_NEWUSERBYDATEYEAR . $_GET['anneeNewUserHolder'];
    $string = substr($string0, 0, -1);
} elseif (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeNewUserHolder'])) {
    $donneeuserindustriel = $manager->getSingle2("SELECT count(distinct id) as nb FROM tmpnbnewUserIndust where datecreation=?",$currentyear);
    $donneeuseracaexterne = $manager->getSingle2("SELECT count(distinct id) FROM tmpnbnewUserExterne where datecreation=?",$currentyear);    
    array_push($arraylibellecentrale, array("libellecentrale" => TXT_INDUSTRIEL));
    array_push($arraylibellecentrale, array("libellecentrale" => TXT_ACADEMIQUEEXTERNE));
    $nbInterne= $manager->getSingle2("select count(distinct idutilisateur) from tmpnbnewUserinterne where  datecreation=? and masquecentrale !=TRUE",$currentyear);
    $nbtotaluser = $nbInterne + $donneeuserindustriel + $donneeuseracaexterne;
    for ($i = 0; $i < count($arraylibellecentrale); $i++) {
        if (isset($arraylibellecentrale[$i]['libellecentrale'])) {
            $donneeUser = $manager->getSinglebyArray("select count(idutilisateur) from tmpnbnewUserinterne  WHERE libellecentrale=? and datecreation=? and masquecentrale !=TRUE", array($arraylibellecentrale[$i]['libellecentrale'], $currentyear));
            
            if ($arraylibellecentrale[$i]['libellecentrale'] == TXT_INDUSTRIEL) {
                $donneeUser = $donneeuserindustriel;
            }
            if ($arraylibellecentrale[$i]['libellecentrale'] == ''.TXT_ACADEMIQUEEXTERNE.'') {
                $donneeUser = $donneeuseracaexterne;
            }
        }       
           $string0.='["' .$arraylibellecentrale[$i]['libellecentrale'] . '",' . $donneeUser . '],';
        
    }
    $title = TXT_NEWUSERBYDATEYEAR . $currentyear;
    $string = substr($string0, 0, -1);
}
if (IDTYPEUSER == ADMINLOCAL) {
    $title = TXT_NEWUSERBYDATEYEAR .    $currentyear ;    
    $donneeuserindustriel = $manager->getSingle2("SELECT count(distinct id) as nb FROM tmpnbnewUserIndust where datecreation=?",$currentyear);
    $donneeuseracaexterne = $manager->getSinglebyArray("SELECT count(distinct id) FROM tmpnbnewUserExterne where datecreation=? and libellecentrale=?",array($currentyear,LIBELLECENTRALEUSER));    
    $nbInterne= $manager->getSinglebyArray("select count(distinct idutilisateur) from tmpnbnewUserInterne where libellecentrale=? and datecreation=?",array(LIBELLECENTRALEUSER,$currentyear));
    $nbtotaluser = $donneeuseracaexterne + $donneeuserindustriel + $nbInterne;
    $string0.='["' . TXT_ACADEMIQUEINTERNE . '",' . $nbInterne . '],';
    $string3 = '["' . TXT_ACADEMIQUEEXTERNE . '",' . $donneeuseracaexterne . '],';
    $string4 = '["' . TXT_INDUSTRIEL . '",' . $donneeuserindustriel . '],';
    $string = substr($string0 . $string4 . $string3, 0, -1);
}
$subtitle = TXT_NOMBREBUSER . '  <b>' . $nbtotaluser . '</b>';
//Construction de la courbe
include_once 'commun/scriptPie.php';
