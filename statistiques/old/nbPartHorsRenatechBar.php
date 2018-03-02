<?php
include_once 'class/Manager.php';
$db = BD::connecter();

$manager = new Manager($db);
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneeuserdate from utilisateur order by anneeuserdate asc");
$arrayNbUser = array();
$xaxis = '';
$yaxis = '';
$years = $manager->getList("select distinct EXTRACT(YEAR from datecreation)as year from utilisateur order by year asc");
$centrale = $manager->getList2("select libellecentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL) {
    $manager->exeRequete("drop table if exists tmpUserpartenairePorteurDate");
    $manager->getRequete("create table tmpUserpartenairePorteurDate as (SELECT c.idcentrale,p.datedebutprojet,c.libellecentrale,count(up.idutilisateur_utilisateur) as nb FROM utilisateur u,utilisateurporteurprojet up,centrale c,
        concerne co,projet p WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = c.idcentrale AND co.idprojet_projet = p.idprojet 
        AND u.idcentrale_centrale is null group by c.idcentrale,p.datedebutprojet,c.libellecentrale 
        union 
        SELECT idcentrale,pr.datedebutprojet,libellecentrale,count(distinct(pap.nompartenaire))as nb FROM projetpartenaire ppr,partenaireprojet pap,projet pr,concerne co , centrale ce WHERE  ppr.idprojet_projet = pr.idprojet 
        AND pap.idpartenaire = ppr.idpartenaire_partenaireprojet AND co.idprojet_projet = pr.idprojet and co.idcentrale_centrale = ce.idcentrale and idcentrale_centrale !=? and pr.datedebutprojet is not null 
         and co.idstatutprojet_statutprojet=? group by idcentrale,pr.datedebutprojet,ce.libellecentrale )", array(IDCENTRALEAUTRE,ENCOURSREALISATION));
    ?>
    <table>
        <tr>
            <td>
                <select  id="anneePartenaireHorsRenatech" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneePartenaireHorsRenatech',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statPartenaireHorsRenatech/<?php echo $lang . '/'; ?>' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($years); $i++) {
                                 echo '<option value="' . $years[$i]['year'] . '">' . $years[$i]['year'] . '</option>';
                             }
                             ?>
                </select>
            </td>
        </tr>
    </table>
<?php }
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneePartenaireHorsRenatech'])) {
    $title= TXT_PARTHORSRENATECH;
    $_S_serie = '';
    $totalUser = $manager->getList("select sum(nb) as nb,libellecentrale from tmpUserpartenairePorteurDate group by idcentrale,libellecentrale order by idcentrale");
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        $_S_serie .= '{name: "' . $totalUser[$i]['libellecentrale'] . '", data: [{name: "' . TXT_DETAILS .
                '",y: ' . $totalUser[$i]['nb'] . ',drilldown: "' . $totalUser[$i]['libellecentrale'] . '"}]},';
    }
    $serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              PARTENAIRE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
    
    $_S_serie2 = '';
    foreach ($centrale as $key => $cent) {
        $_S_serie2 .="{id: '" . $cent[0] . "',name: '" . $cent[0] . "',data: [";
        foreach ($years as $key => $value) {
            //PARTENAIRE
            if($value[0]==2013){
                $nbByYear = $manager->getSinglebyArray("select sum(nb) from tmpUserpartenairePorteurDate where libellecentrale=? AND EXTRACT(YEAR from datedebutprojet)<=?", array($cent[0], 2013));
            }else{
                   $nbByYear = $manager->getSinglebyArray("select sum(nb) from tmpUserpartenairePorteurDate where libellecentrale=? AND EXTRACT(YEAR from datedebutprojet)<=?", array($cent[0], $value[0]));
            }
            if (empty($nbByYear)) {
                $nbByYear = 0;
            }
            if($value[0]==2013){
                $_S_serie2 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYear . " , drilldown: '" . $cent[0] . $value[0] . "'},";
            }else{
                $_S_serie2 .="{name: '" . $value[0] . "', y: " . $nbByYear . " , drilldown: '" . $cent[0] . $value[0] . "'},";
            }
        }$_S_serie2 .="]},";
    }
    $serie0 = str_replace("},]}", "}]}", $_S_serie2);
    $_S_serie3 = "";
    foreach ($centrale as $key => $cent) {
        foreach ($years as $key => $value) {
            if($value[0]==2013){
                $nbByYear = $manager->getSinglebyArray("select sum(nb) from tmpUserpartenairePorteurDate where libellecentrale=? AND EXTRACT(YEAR from datedebutprojet)<=?", array($cent[0], 2013));
            }else{
                $nbByYear = $manager->getSinglebyArray("select sum(nb) from tmpUserpartenairePorteurDate where libellecentrale=? AND EXTRACT(YEAR from datedebutprojet)<=?", array($cent[0], $value[0]));
            }
            if($value[0]==2013){
                $_S_serie3.="{id: '" . $cent[0] . $value[0] . "',name: '" .$cent[0] .' '. TXT_INFERIEUR2013 . "'" . ',data: [';
            }else{
                $_S_serie3.="{id: '" . $cent[0] . $value[0] . "',name: '" .$cent[0] .' '. $value[0] . "'" . ',data: [';
            }
            
            for ($mois = 1; $mois < 13; $mois++) {
                $nbUsercentrale = $manager->getSinglebyArray("select sum(nb) from tmpUserpartenairePorteurDate where libellecentrale=? AND EXTRACT(YEAR from datedebutprojet)=? AND EXTRACT(MONTH from datedebutprojet)=?", 
                        array($cent[0], $value[0], $mois));
                if (empty($nbUsercentrale)) {
                    $nbUsercentrale = 0;
                }
                $_S_serie3.= "['" . showMonth($mois,$lang) . "'," . $nbUsercentrale . "],";
            }
            $_S_serie3.=']},';
        }
    }
    $serie_0 = $serie0 . $_S_serie3;
    $serieY = substr($serie_0, 0, -1);
} elseif (IDTYPEUSER == ADMINNATIONNAL&& isset($_GET['anneePartenaireHorsRenatech'])) {
    if($_GET['anneePartenaireHorsRenatech']==2013){
        $title= TXT_PARTHORSRENATECHDATE.' '.TXT_INFERIEUR2013;
    }else{
        $title= TXT_PARTHORSRENATECHDATE.$_GET['anneePartenaireHorsRenatech'];
    }
       ?>
    <table>
        <tr>
            <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDPARTHORSRENATECH; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
            </td>
        </tr>
    </table> 
    <?php
     $_S_serie = '';
    $totalUser = $manager->getList2("select sum(nb) as nb,libellecentrale,idcentrale from tmpUserpartenairePorteurDate where extract(YEAR from datedebutprojet)<=? group by idcentrale,libellecentrale order by idcentrale asc",$_GET['anneePartenaireHorsRenatech']);
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        $_S_serie .= '{name: "' . $totalUser[$i]['libellecentrale'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $totalUser[$i]['nb'] . ',drilldown: "' . $totalUser[$i]['libellecentrale'] . '"}]},';
    }
    $serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PARTENAIRE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $_S_serie3 = "";
    foreach ($centrale as $key => $cent) {
            if($_GET['anneePartenaireHorsRenatech']==2013){
                $nbByYear = $manager->getSinglebyArray("select sum(nb) from tmpUserpartenairePorteurDate where libellecentrale=? AND EXTRACT(YEAR from datedebutprojet)<=?", array($cent[0], 2013));
            }else{
                $nbByYear = $manager->getSinglebyArray("select sum(nb) from tmpUserpartenairePorteurDate where libellecentrale=? AND EXTRACT(YEAR from datedebutprojet)<=?", array($cent[0], $_GET['anneePartenaireHorsRenatech']));
            }
            if($_GET['anneePartenaireHorsRenatech']==2013){
                $_S_serie3.="{id: '" . $cent[0]. "',name: '" .$cent[0] .' '. TXT_INFERIEUR2013 . "'" . ',data: [';
            }else{
                $_S_serie3.="{id: '" . $cent[0]. "',name: '" .$cent[0] .' '. $_GET['anneePartenaireHorsRenatech'] . "'" . ',data: [';
            }
            for ($mois = 1; $mois < 13; $mois++) {
                $nbUsercentrale = $manager->getSinglebyArray("select sum(nb) from tmpUserpartenairePorteurDate where libellecentrale=? AND EXTRACT(YEAR from datedebutprojet)=? "
                        . "AND EXTRACT(MONTH from datedebutprojet)=?", array($cent[0], $_GET['anneePartenaireHorsRenatech'], $mois));
                if (empty($nbUsercentrale)) {
                    $nbUsercentrale = 0;
                }
                $_S_serie3.= "['" . showMonth($mois,$lang) . "'," . $nbUsercentrale . "],";
            }
            $_S_serie3.=']},';
    }$serieY = substr($_S_serie3, 0, -1);     
}    
    
if (IDTYPEUSER == ADMINLOCAL) {//ADMINISTRATEUR LOCAL
    $title=TXT_PARTHORSRENATECH;
    $_S_serie = '';
    $manager->exeRequete("drop table if exists tmpUserpartenairePorteurDate");
    $manager->getRequete("create table tmpUserpartenairePorteurDate as (SELECT p.datedebutprojet,c.libellecentrale,count(up.idutilisateur_utilisateur) as nb FROM utilisateur u,utilisateurporteurprojet up,centrale c,
        concerne co,projet p WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = c.idcentrale AND co.idprojet_projet = p.idprojet 
        AND u.idcentrale_centrale is null AND co.idcentrale_centrale=? group by p.datedebutprojet,c.libellecentrale 
        union 
        SELECT pr.datedebutprojet,libellecentrale,count(distinct(pap.nompartenaire))as nb FROM projetpartenaire ppr,partenaireprojet pap,projet pr,concerne co , centrale ce WHERE  ppr.idprojet_projet = pr.idprojet 
        AND pap.idpartenaire = ppr.idpartenaire_partenaireprojet AND co.idprojet_projet = pr.idprojet and co.idcentrale_centrale = ce.idcentrale and idcentrale_centrale !=? and pr.datedebutprojet is not null 
         and co.idstatutprojet_statutprojet=? AND co.idcentrale_centrale=? group by pr.datedebutprojet,ce.libellecentrale)"
            , array(IDCENTRALEUSER,IDCENTRALEAUTRE,ENCOURSREALISATION,IDCENTRALEUSER));
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", IDCENTRALEUSER);
    $years = $manager->getList("select distinct EXTRACT(YEAR from datecreation)as year from utilisateur order by year asc");    
    foreach ($years as $key => $year) {
        if($year[0]==2013){
            $nbByYear2013 = $manager->getSingle("select sum(nb) from tmpUserpartenairePorteurDate where  EXTRACT(YEAR from datedebutprojet)<=2013");
        }else{
            $nbByYear = $manager->getSingle2("select sum(nb) from tmpUserpartenairePorteurDate where  EXTRACT(YEAR from datedebutprojet)<=?", $year[0]);
        }
         if(empty($nbByYear)){$nbByYear=0;}
         if($year[0]==2013){
            $_S_serie .= '{name: "' .TXT_INFERIEUR2013. '", data: [{name: "' . TXT_DETAILS .'",y: ' . $nbByYear2013 . ',drilldown: "' .$libellecentrale.$year[0] . '"}]},';
         }else{
             $_S_serie .= '{name: "' .$year[0]. '", data: [{name: "' . TXT_DETAILS .'",y: ' . $nbByYear . ',drilldown: "' .$libellecentrale.$year[0] . '"}]},';
         }
    }
    $serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
    $_S_serie3 = "";    
        foreach ($years as $key => $year) {
            $nbByYear = $manager->getSingle2("select sum(nb) from tmpUserpartenairePorteurDate where EXTRACT(YEAR from datedebutprojet)=?", $year[0]);
            if(empty($nbByYear)){
                $nbByYear=0;
            }
            if($year[0]==2013){
                $_S_serie3.="{id: '" . $libellecentrale. $year[0] . "',name: '" . TXT_INFERIEUR2013 . "'" . ',data: [';
            }else{
                $_S_serie3.="{id: '" . $libellecentrale. $year[0] . "',name: '" . $year[0] . "'" . ',data: [';
            }
            for ($mois = 1; $mois < 13; $mois++) {
                $nbUsercentrale = $manager->getSinglebyArray("select sum(nb) from tmpUserpartenairePorteurDate where EXTRACT(YEAR from datedebutprojet)=?  AND EXTRACT(MONTH from datedebutprojet)=?", array($year[0], $mois));
                if (empty($nbUsercentrale)) {$nbUsercentrale = 0;}
                $_S_serie3.= "['" . showMonth($mois,$lang) . "'," . $nbUsercentrale . "],";
            }
            $_S_serie3.=']},';
        }
    $serie_0 =  $_S_serie3;
    $serieY = substr($serie_0, 0, -1);
}

$subtitle = TXT_CLICDETAIL;
$xasisTitle = TXT_NOMBREOCCURRENCE;
include_once 'commun/scriptBar.php';