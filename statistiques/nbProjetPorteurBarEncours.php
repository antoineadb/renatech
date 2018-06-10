<?php 
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneedateprojet from utilisateur order by anneedateprojet asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
?>
<?php if (IDTYPEUSER == ADMINNATIONNAL){
    $serie = "";
    foreach ($centrales as $key => $centrale) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword 
                AND idutilisateur = idutilisateur_utilisateur AND cr.idprojet_projet = idprojet AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? 
                and co.idstatutprojet_statutprojet=? and  trashed != ?",
                    array($centrale[1],ENCOURSREALISATION,TRUE));
            $serie .= '{name: "' . $centrale[0] . '", data: [{name: "' . TXT_DETAILS .
                    '",y: ' . $nbProjet . ',drilldown: "' . $centrale[0] . '"}]},';

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
        
    }
$serie2="";
$serie3 = "";

    $serie03 = substr($serie2 . $serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;    
    $title = TXT_NBPORTEURPROJETENCOURS;
    $xasisTitle = TXT_NOMBREOCCURRENCE;    
}
if (IDTYPEUSER == ADMINLOCAL ) {
$serie02 = '';
$serie02 .="{id: '" . LIBELLECENTRALEUSER . "',name: '" . LIBELLECENTRALEUSER . "',data: [";
foreach ($years as $key => $year) {  
    $nbByYear = $manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND idutilisateur = idutilisateur_utilisateur 
        AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? and EXTRACT(YEAR from datecreation)<=? and  trashed != ?", 
            array(IDCENTRALEUSER,ENCOURSREALISATION,$year[0],TRUE));
    if (empty($nbByYear)) {$nbByYear = 0;}
    if($year[0]==2013){
        $serie02 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYear . " , drilldown: '" . IDCENTRALEUSER . $year[0] . "'},";
    }else{
        $serie02 .="{name: '" . $year[0] . "', y: " . $nbByYear . " , drilldown: '" . IDCENTRALEUSER . $year[0] . "'},";
    }
}$serie02 .="]},";        

$serieX = str_replace("},]}", "}]}", $serie02);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$serie3 = "";
foreach ($years as $key => $year) {
    if($year[0]==2013){
        $serie3.="{id: '" . IDCENTRALEUSER . $year[0] . "',name: '" . TXT_INFERIEUR2013 . "'" . ',data: [';
    }else{
        $serie3.="{id: '" . IDCENTRALEUSER . $year[0] . "',name: '" . $year[0] . "'" . ',data: [';
    }
    $nbByYearByStatut=$manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND "
                . "idutilisateur = idutilisateur_utilisateur AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? "
                . "and EXTRACT(YEAR from datecreation)<=?  and  trashed != ?", array(IDCENTRALEUSER,ENCOURSREALISATION,$year[0]-1,TRUE));
    for ($mois = 1; $mois < 13; $mois++) {
        $nbByYearByStatut = $manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND "
                . "idutilisateur = idutilisateur_utilisateur AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? "
                . "and EXTRACT(YEAR from datecreation)=?  and extract(month from datecreation)=? and  trashed != ?", array( IDCENTRALEUSER,ENCOURSREALISATION,$year[0],$mois,TRUE));
        if (empty($nbByYearByStatut)) {$nbByYearByStatut = 0;}
        $serie3.= '["' . removeDoubleQuote(showMonth($mois, $lang)) . '",' . $nbByYearByStatut . '],';
    }
    $serie3.=']},';
}
$serie03 = substr($serie3, 0, -1);
$serieY = str_replace("],]}", "]]}", $serie03);
$subtitle = TXT_CLICDETAIL;
$title = TXT_NBPORTEURPROJETENCOURS;
$xasisTitle = TXT_NOMBREOCCURRENCE;
}


include_once 'commun/scriptBar.php'; 