<?php 
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneedateprojet from utilisateur order by anneedateprojet asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
?>
<?php if (IDTYPEUSER == ADMINNATIONNAL){ ?>
<table>
    <tr>
        <td>
            <select  id="anneePorteurProjetEncours" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneePorteurProjetEncours',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                     style="width: 250px;margin-left:35px" 
                     onchange="window.location.replace('<?php  echo "/" . REPERTOIRE; ?>/statistiquePorteurProjetEnCours/<?php echo $lang.'/'; ?>' + this.value)" >
                             <?php
                                for ($i = 0; $i < count($arraydate); $i++) {
                                   echo '<option value="' . ($arraydate[$i]['anneedateprojet']) . '">' . $arraydate[$i]['anneedateprojet'] . '</option>';
                                }
                             ?>
            </select>
        </td>
    </tr>
</table>
<?php } ?>
<?php if (IDTYPEUSER == ADMINNATIONNAL &&  isset($_GET['anneePorteurProjetEncours'])) {?>
<table>
    <tr>
        <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE;?>/chxStatistique/<?php echo $lang.'/'.IDNBPORTEURRUNNINGPROJECT; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
        </td>
    </tr>
</table>    
<?php    
    $serie = "";
    foreach ($centrales as $key => $centrale) {
            $nbUser = $manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND idutilisateur = idutilisateur_utilisateur 
            AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? and  trashed != ? and EXTRACT(YEAR from datecreation)<=?",
                    array($centrale[1],ENCOURSREALISATION,TRUE,$_GET['anneePorteurProjetEncours']));
            $serie .= '{name: "' . $centrale[0]. '", data: [{name: "' . TXT_DETAILS .
                    '",y: ' . $nbUser . ',drilldown: "' . $centrale[0].$_GET['anneePorteurProjetEncours'] . '"}]},';

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$serie3 = "";

foreach ($centrales as $key => $centrale) {
        $serie3.="{id: '" . $centrale[0] . $_GET['anneePorteurProjetEncours'] . "',name: '" .$centrale[0] ."'" . ',data: [';
        $nbByYearByMonth=$manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND idutilisateur = idutilisateur_utilisateur 
            AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? and EXTRACT(YEAR from datecreation)<=? and  trashed != ? ",
                array($centrale[1],ENCOURSREALISATION,$_GET['anneePorteurProjetEncours']-1,TRUE));
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearByMonth =(int) $manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword 
            AND idutilisateur = idutilisateur_utilisateur AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? 
            and EXTRACT(YEAR from datecreation)=? and extract(month from datecreation)=? and  trashed != ?", array( $centrale[1],ENCOURSREALISATION,$_GET['anneePorteurProjetEncours'], $mois,TRUE));
            if (empty($nbByYearByMonth)) {$nbByYearByMonth = 0;}
            $serie3.= '["' . removeDoubleQuote(showMonth($mois,$lang)) . '",' . $nbByYearByMonth . '],';
        }
        $serie3.=']},';
}

   
    $serie03 = substr($serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;
    if($_GET['anneePorteurProjetEncours']==2013){
        $title = TXT_NBPORTEURPROJETENCOURSPOURANNEE.' '.TXT_INFERIEUR2013;
    }else{
        $title = TXT_NBPORTEURPROJETENCOURSPOURANNEE.' '.$_GET['anneePorteurProjetEncours'];
    }
    $xasisTitle = TXT_NOMBREOCCURRENCE;
    
    
} elseif (IDTYPEUSER == ADMINNATIONNAL &&  !isset($_GET['anneePorteurProjetEncours'])) {?>
<?php    
    $serie = "";
    foreach ($centrales as $key => $centrale) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND idutilisateur = idutilisateur_utilisateur 
            AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? and  trashed != ?",
                    array($centrale[1],ENCOURSREALISATION,TRUE));
            $serie .= '{name: "' . $centrale[0] . '", data: [{name: "' . TXT_DETAILS .
                    '",y: ' . $nbProjet . ',drilldown: "' . $centrale[0] . '"}]},';

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
        
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
$serie02 = '';    
foreach ($centrales as $key => $centrale) {    
    $serie02 .="{id: '" . $centrale[0] . "',name: '" . $centrale[0] . "',data: [";
    foreach ($years as $key => $year) {  
        $nbByYear = $manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND idutilisateur = idutilisateur_utilisateur 
            AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? and EXTRACT(YEAR from datecreation)<=? and  trashed != ?", 
                array($centrale[1],ENCOURSREALISATION,$year[0],TRUE));
        if (empty($nbByYear)) {$nbByYear = 0;}
        if($year[0]==2013){
            $serie02 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYear . " , drilldown: '" . $centrale[0] . $year[0] . "'},";
        }else{
            $serie02 .="{name: '" . $year[0] . "', y: " . $nbByYear . " , drilldown: '" . $centrale[0] . $year[0] . "'},";
        }
    }$serie02 .="]},";        
}
$serie2 = str_replace("},]}", "}]}", $serie02);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$serie3 = "";

foreach ($centrales as $key => $centrale) {
    foreach ($years as $key => $year) {
        if($year[0]==2013){
            $serie3.="{id: '" . $centrale[0] . $year[0] . "',name: '" .$centrale[0] .' '. TXT_INFERIEUR2013 . "'" . ',data: [';
        }else{
            $serie3.="{id: '" . $centrale[0] . $year[0] . "',name: '" .$centrale[0] .' '. $year[0] . "'" . ',data: [';
        }
        
        $nbByYearByStatut=$manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND "
                    . "idutilisateur = idutilisateur_utilisateur AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? "
                    . "and EXTRACT(YEAR from datecreation)<=?  and  trashed != ?", array( $centrale[1],ENCOURSREALISATION,$year[0]-1,TRUE));; 
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearByStatut = $manager->getSinglebyArray("SELECT count(distinct idutilisateur) FROM concerne co,loginpassword,utilisateur,creer cr,projet WHERE idlogin = idlogin_loginpassword AND "
                    . "idutilisateur = idutilisateur_utilisateur AND cr.idprojet_projet = idprojet   AND cr.idprojet_projet = co.idprojet_projet AND  co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=? "
                    . "and EXTRACT(YEAR from datecreation)=?  and extract(month from datecreation)=? and  trashed != ?", array( $centrale[1],ENCOURSREALISATION,$year[0],$mois,TRUE));
            if (empty($nbByYearByStatut)) {$nbByYearByStatut = 0;}
            $serie3.= '["' . removeDoubleQuote(showMonth($mois, $lang)) . '",' . $nbByYearByStatut . '],';
        }
        $serie3.=']},';
    }
}
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