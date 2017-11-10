<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc",IDAUTRECENTRALE);
$datay = array();
$arraylibelle = array();
$string0 = '';

$arraystatutprojet = $manager->getList2("select libellestatutprojet,libellestatutprojeten,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet asc", TRANSFERERCENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeProjetEncours'])) {  
    $nbtotalprojet = $manager->getSinglebyArray("select count(idprojet) from  projet,concerne where idprojet_projet=idprojet and extract(year from dateprojet)<=? AND idcentrale_centrale is not null "
            . "and  trashed != ?  and idstatutprojet_statutprojet=? and idcentrale_centrale !=?",array($_GET['anneeProjetEncours'],TRUE,ENCOURSREALISATION,IDCENTRALEAUTRE));
    for ($i = 0; $i < count($arraylibellecentrale); $i++) {
        $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=?  and extract(year from dateprojet)<=? and  trashed != ?  "
                . "and idstatutprojet_statutprojet=? and idcentrale_centrale !=?",array($arraylibellecentrale[$i]['idcentrale'],$_GET['anneeProjetEncours'],TRUE,ENCOURSREALISATION,IDCENTRALEAUTRE));        
        if ($nbtotalprojet != 0) {
            $string0.='["' . $arraylibellecentrale[$i]['libellecentrale'] . '",' . $donneeProjet . '],';
            
        }
    }$title = TXT_NBRUNNINGPROJETFORTHEYEAR.' '.$_GET['anneeProjetEncours'];
    $subtitle = TXT_NBPROJET . ' <b>' . $nbtotalprojet . '</b>';
}elseif (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeProjetEncours'])) {  
    $nbtotalprojet = $manager->getSinglebyArray("select count(idprojet) from  projet,concerne where idprojet_projet=idprojet and extract(year from dateprojet)<=? AND idcentrale_centrale is not null "
            . "and  trashed != ?  and idstatutprojet_statutprojet=? and idcentrale_centrale!=?",array(date('Y')-1,TRUE,ENCOURSREALISATION,IDCENTRALEAUTRE));
    for ($i = 0; $i < count($arraylibellecentrale); $i++) {
        $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                . "and extract(year from dateprojet)<=? and  trashed != ?  and idstatutprojet_statutprojet=? and idcentrale_centrale!=?",array($arraylibellecentrale[$i]['idcentrale'],(date('Y')-1),TRUE,ENCOURSREALISATION,IDCENTRALEAUTRE));
        if ($nbtotalprojet != 0) {
            $string0.='["' . $arraylibellecentrale[$i]['libellecentrale'] . '",' . $donneeProjet . '],';
            
        }
    }
    $title = TXT_NBRUNNINGPROJETFORTHEYEAR.' '.(date('Y')-1);
    $subtitle = TXT_NBPROJET . ' <b>' . $nbtotalprojet . '</b>';
}
if (IDTYPEUSER == ADMINLOCAL) {   
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet=idprojet and idcentrale_centrale=? and extract(year from dateprojet)<=? AND trashed !=?"
            ,array(IDCENTRALEUSER,(date('Y')-1),TRUE));
    for ($i = 0; $i < count($arraystatutprojet); $i++) {
        $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale_centrale=? 
            and idstatutprojet_statutprojet=? and extract(year from dateprojet)<=? and trashed !=?", array(IDCENTRALEUSER, $arraystatutprojet[$i]['idstatutprojet'],(date('Y')-1), TRUE));
        if ($nbtotalprojet != 0) {
            if ($lang == 'fr') {
                if($donneeProjet!=0){
                    $string0.='["' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '",' . $donneeProjet  . '],';
                }
            } elseif ($lang == 'en') {
                if($donneeProjet!=0){
                    $string0.='["' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojeten'])) . '",' . round((($donneeProjet / $nbtotalprojet) * 100), 1) . '],';
                }
            }
        }
    }    
    $title = TXTPROJETDELANNEE.(date('Y')-1);
    $subtitle = TXT_NBPROJET . ' <b>' . $nbtotalprojet . '</b>';
}

$string = substr($string0, 0, -1);
include_once 'commun/scriptPie.php';

