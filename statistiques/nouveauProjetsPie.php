<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$string0 = '';

$arraystatutprojet = $manager->getList2("select libellestatutprojet,libellestatutprojeten,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet asc", TRANSFERERCENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL) {
    $nb=0;
    for ($i = 0; $i < count($arraylibellecentrale); $i++) {
        $donneeProjet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=?  "
                . "and EXTRACT(YEAR from dateprojet)<=? and EXTRACT(YEAR from dateprojet)>=? and trashed !=?"
                . "and idcentrale_centrale!=?",array($arraylibellecentrale[$i]['idcentrale'],$anneeFin,$anneeDepart,TRUE,IDCENTRALEAUTRE));
        $nb +=$donneeProjet; 
        if ($donneeProjet != 0) {            
            $string0.='["' . $arraylibellecentrale[$i]['libellecentrale'] . '",' . $donneeProjet . '],';            
        }
    }    
    if($lang=='fr'){
           if($anneeDepart==$anneeFin){
                $title = TXT_PROJETDATESTATUTANNEE.' '.$anneeDepart;
           }else{
                $title = TXT_PROJETDATESTATUTANNEE.' '.$anneeDepart. " à ".$anneeFin;
           }
    }else{
        if($anneeDepart==$anneeFin){
            $title = TXT_PROJETDATESTATUTANNEE.' '.$anneeDepart;
        }else{
            $title = TXT_PROJETDATESTATUTANNEE.' '.$anneeDepart. " to ".$anneeFin;
        }
    }
    $subtitle = TXT_NBPROJET . ' <b>' . $nb . '</b>';
}

if (IDTYPEUSER == ADMINLOCAL) {
    
    $nb = 0;
    
    foreach ($annee as $key => $year) {
        $donneeProjet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale_centrale=? 
            and EXTRACT(YEAR from dateprojet)<=? and trashed !=?", array(IDCENTRALEUSER, $year,TRUE));
        if ($donneeProjet != 0) {
            $nb +=$donneeProjet;            
                $string0.='["' .$year . '",' . $donneeProjet  . '],';
        }
    }
        
    if($lang=='fr'){
           if($anneeDepart==$anneeFin){
                $title = TXT_PROJETDATESTATUTANNEE.' '.$anneeDepart;
           }else{
                $title = TXT_PROJETDATESTATUTANNEE.' '.$anneeDepart. " à ".$anneeFin;
           }
    }else{
        if($anneeDepart==$anneeFin){
            $title = TXT_PROJETDATESTATUTANNEE.' '.$anneeDepart;
        }else{
            $title = TXT_PROJETDATESTATUTANNEE.' '.$anneeDepart. " to ".$anneeFin;
        }
    }
    $subtitle = TXT_NBPROJET . ' <b>' . $nb . '</b>';
}
$string = substr($string0, 0, -1);
include_once 'commun/scriptPie.php';

