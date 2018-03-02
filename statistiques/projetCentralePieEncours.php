<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc",IDAUTRECENTRALE);
$datay = array();
$arraylibelle = array();
$string0 = '';

$arraystatutprojet = $manager->getList2("select libellestatutprojet,libellestatutprojeten,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet asc", TRANSFERERCENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL) {  
    $nbtotalprojet = $manager->getSinglebyArray("select count(idprojet) from  projet,concerne where idprojet_projet=idprojet AND idcentrale_centrale is not null "
            . "and  trashed != ?  and idstatutprojet_statutprojet=? and idcentrale_centrale!=?",array(TRUE,ENCOURSREALISATION,IDCENTRALEAUTRE));
    for ($i = 0; $i < count($arraylibellecentrale); $i++) {
        $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                . " and  trashed != ?  and idstatutprojet_statutprojet=? and idcentrale_centrale!=?",array($arraylibellecentrale[$i]['idcentrale'],TRUE,ENCOURSREALISATION,IDCENTRALEAUTRE));
        if ($nbtotalprojet != 0) {
            $string0.='["' . $arraylibellecentrale[$i]['libellecentrale'] . '",' . $donneeProjet . '],';
            
        }
    }
    $title = TXT_NBRUNNINGPROJET;
    $subtitle = TXT_NBPROJET . ' <b>' . $nbtotalprojet . '</b>';
}
if (IDTYPEUSER == ADMINLOCAL) {
    
    $serie = "";
    $nbtotalprojet=0;
    
            $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? "
                    . " and  trashed != ? and idstatutprojet_statutprojet=? ", array(IDCENTRALEUSER,TRUE,ENCOURSREALISATION));                        
            if($nbProjet==0){$nbProjet=0;}            
            $string0.='["' . $year[0] . '",' . $nbProjet. '],';
           $nbtotalprojet+=$nbProjet;

       
    $title = TXT_NBRUNNINGPROJET;
    $subtitle = TXT_NBPROJET . ' <b>' . $nbtotalprojet . '</b>';
}

$string = substr($string0, 0, -1);
include_once 'commun/scriptPie.php';

