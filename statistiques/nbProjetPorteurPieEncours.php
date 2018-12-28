<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' and masquecentrale!=TRUE  order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$string0 = '';
$curentYear=date('Y');
$arraystatutprojet = $manager->getList2("select libellestatutprojet,libellestatutprojeten,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet asc", TRANSFERERCENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL) {      
    $nbtotalprojet=0;
    for ($i = 0; $i < count($arraylibellecentrale); $i++) {
        $nbByYearByStatut=$manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND "
                    . "idutilisateur = idutilisateur_utilisateur AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? "
                    . "and EXTRACT(YEAR from datecreation)<=?  and  trashed != ?", array( $arraylibellecentrale[$i]['idcentrale'],ENCOURSREALISATION,$curentYear,TRUE));
        $nbtotalprojet+=$nbByYearByStatut;
        if ($nbtotalprojet != 0) {
            $string0.='["' . $arraylibellecentrale[$i]['libellecentrale'] . '",' . $nbByYearByStatut . '],';
        }
    }
    $title = TXT_NBPORTEURPROJETENCOURSPOURANNEE.' '.(date('Y')-1);
    $subtitle = TXT_NBPROJET . ' <b>' . $nbtotalprojet . '</b>';
}


if (IDTYPEUSER == ADMINLOCAL) {   
$nbtotalprojet=0;
    foreach ($years as $key => $year) {
        $nbByYearByStatut=$manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND "
                    . "idutilisateur = idutilisateur_utilisateur AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? "
                    . "and EXTRACT(YEAR from datecreation)<=?  and  trashed != ?", array(IDCENTRALEUSER,ENCOURSREALISATION,$year[0],TRUE));
        $nbtotalprojet+=$nbByYearByStatut;
        if ($nbtotalprojet != 0) {
            $string0.='["' . $year[0] . '",' . $nbByYearByStatut . '],';
        }
    }
    $title = TXT_REPARTITIONPORTEURPROJETENCOURS;
    $subtitle = TXT_NBTTOTALPORTEUR . ' <b>' . $nbtotalprojet . '</b>';
}

$string = substr($string0, 0, -1);

include_once 'commun/scriptPie.php';

