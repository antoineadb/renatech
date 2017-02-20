<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$string0 = '';

$arraystatutprojet = $manager->getList2("select libellestatutprojet,libellestatutprojeten,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet asc", TRANSFERERCENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL && ! isset($_GET['anneeNouveauProjet'])) {
    $nb=0;
    for ($i = 0; $i < count($arraylibellecentrale); $i++) {
        $donneeProjet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=?  and extract(year from dateprojet)<=? and trashed !=?"
                . "and idcentrale_centrale!=?",array($arraylibellecentrale[$i]['idcentrale'],(date('Y')-1),TRUE,IDCENTRALEAUTRE));
        $nb +=$donneeProjet; 
        if ($donneeProjet != 0) {            
            $string0.='["' . $arraylibellecentrale[$i]['libellecentrale'] . '",' . $donneeProjet . '],';            
        }
    }    
    $title = TXT_PROJETDATESTATUTANNEE.(date('Y')-1);
    $subtitle = TXT_NBPROJET . ' <b>' . $nb . '</b>';
}elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeNouveauProjet'])) {
    $nb=0;
    for ($i = 0; $i < count($arraylibellecentrale); $i++) {
        $donneeProjet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=?  and extract(year from dateprojet)<=? and trashed !=?"
                . "and idcentrale_centrale!=?",array($arraylibellecentrale[$i]['idcentrale'],$_GET['anneeNouveauProjet'],TRUE,IDCENTRALEAUTRE));
        $nb +=$donneeProjet; 
        if ($donneeProjet != 0) {            
            $string0.='["' . $arraylibellecentrale[$i]['libellecentrale'] . '",' . $donneeProjet . '],';
        }
    }    
    $title = TXT_PROJETDATESTATUTANNEE.$_GET['anneeNouveauProjet'];
    $subtitle = TXT_NBPROJET . ' <b>' . $nb . '</b>';
}


if (IDTYPEUSER == ADMINLOCAL) {
    $nb = 0;
    for ($i = 0; $i < count($arraystatutprojet); $i++) {
        $donneeProjet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale_centrale=? 
            and idstatutprojet_statutprojet=? and extract(year from dateprojet)<=? and trashed !=?", array(IDCENTRALEUSER, $arraystatutprojet[$i]['idstatutprojet'],(date('Y')-1),TRUE));
        if ($donneeProjet != 0) {
            $nb +=$donneeProjet; 
            if ($lang == 'fr') {
                $string0.='["' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '",' . $donneeProjet  . '],';
            } elseif ($lang == 'en') {
                $string0.='["' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojeten'])) . '",' . $donneeProjet . '],';
            }
        }
    }    
    $title = TXT_PROJETDATESTATUTANNEE.(date('Y')-1);
    $subtitle = TXT_NBPROJET . ' <b>' . $nb . '</b>';
}
$string = substr($string0, 0, -1);
include_once 'commun/scriptPie.php';

