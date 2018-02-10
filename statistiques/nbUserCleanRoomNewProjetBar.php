<?php
include_once 'class/Manager.php';
$db = BD::connecter();

$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' and masquecentrale!=TRUE  order by idcentrale asc");
$nblibelle = count($arraylibellecentrale);
$datay = array();
$arraylibelle = array();
$string0 = '';
$string1 = '';

$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneeuserdate from utilisateur order by anneeuserdate asc");
$centrales = $manager->getList2("select libellecentrale from centrale where idcentrale!=? and masquecentrale!=TRUE  order by idcentrale asc", IDAUTRECENTRALE);
$arrayNbUser = array();
$xaxis = '';
$xasisTitle = TXT_NOMBREOCCURRENCE;
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$manager->exeRequete("drop table if exists tmpUserCleanRoom");
$manager->getRequete("create table tmpUserCleanRoom as (SELECT count(distinct pe.mailaccueilcentrale) as nb, ce.idcentrale, ce.libellecentrale,extract(year from datedebutprojet) as annee,extract(month from datedebutprojet) as mois  
FROM personneaccueilcentrale pe,projetpersonneaccueilcentrale pr,projet p,centrale ce,concerne co WHERE pr.idpersonneaccueilcentrale_personneaccueilcentrale = pe.idpersonneaccueilcentrale 
AND p.idprojet = pr.idprojet_projet AND co.idprojet_projet = p.idprojet AND co.idcentrale_centrale = ce.idcentrale and ce.idcentrale != ?  
group by ce.idcentrale,ce.libellecentrale,annee,mois order by ce.idcentrale asc)", array(IDCENTRALEAUTRE));
if (IDTYPEUSER == ADMINNATIONNAL) {
    ?>
    <table>
        <tr>
            <td>
                <select  id="anneeUserCleanRoom" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeUserCleanRoom',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statNbUserCleanRoom/<?php echo $lang . '/'; ?>' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($years); $i++) {
                                 echo '<option value="' . $years[$i]['year'] . '">' . $years[$i]['year'] . '</option>';
                             }
                             ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeUserCleanRoom'])) {
    $title = TXT_CLEANROOMUSERNEWPROJECT;
    $_S_serie = '';
    $totalUser = $manager->getList("select libellecentrale,idcentrale, sum(nb) as nb from tmpUserCleanRoom group by idcentrale,libellecentrale order by idcentrale asc");
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        $_S_serie .= '{name: "' . $totalUser[$i]['libellecentrale'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $totalUser[$i]['nb'] . ',drilldown: "' . $totalUser[$i]['libellecentrale'] . '"}]},';
    }$serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              AXE DES Y
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
    $_S_serie2 = '';
    foreach ($centrales as $key => $centrale) {
        $_S_serie2 .="{id: '" . $centrale[0] . "',name: '" . $centrale[0] . "',data: [";
        foreach ($years as $key => $year) {
            $nbByYear = $manager->getSinglebyArray("select sum(nb) as nb from tmpUsercleanroom where annee<=? and libellecentrale =?", array($year[0], $centrale[0]));
            if (empty($nbByYear)) {
                $nbByYear = 0;
            }
            if($year[0]==2013){
                $_S_serie2 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYear . " , drilldown: '" . $centrale[0] . $year[0] . "'},";
            }else{
                $_S_serie2 .="{name: '" . $year[0] . "', y: " . $nbByYear . " , drilldown: '" . $centrale[0] . $year[0] . "'},";
            }
        }$_S_serie2 .="]},";
    }$serie0 = str_replace("},]}", "}]}", $_S_serie2);
    $_S_serie3 = "";
    foreach ($centrales as $key => $centrale) {
        foreach ($years as $key => $year) {
            $_S_serie3.="{id: '" . $centrale[0] . $year[0] . "',name: '" . $centrale[0] . ' ' . $year[0] . "'" . ',data: [';
            for ($mois = 1; $mois < 13; $mois++) {
                $nbUsercentrale = $manager->getSinglebyArray("select sum(nb) from tmpUsercleanroom where annee=? and libellecentrale =? and mois=?", array($year[0], $centrale[0], $mois));
                if (empty($nbUsercentrale)) {
                    $nbUsercentrale = 0;
                }$_S_serie3.= "['" . showMonth($mois,$lang) . "'," . $nbUsercentrale . "],";
            }$_S_serie3.=']},';
        }
    }$serie_0 = $serie0 . $_S_serie3;
    $serieY = substr($serie_0, 0, -1);
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeUserCleanRoom'])) {
    ?>
    <table>
        <tr>
            <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDNBUSERCLEANROOMNEWPROJET; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
            </td>
        </tr>
    </table> 
    <?php
    if($_GET['anneeUserCleanRoom']==2013){
        $title = TXT_CLEANROOMUSERNEWPROJECTDATE . ' '.TXT_INFERIEUR2013;
    }else{
        $title = TXT_CLEANROOMUSERNEWPROJECTDATE . $_GET['anneeUserCleanRoom'];
    }
    
    $_S_serie = '';
    $totalUser = $manager->getList2("select idcentrale,libellecentrale, sum(nb)as nb from tmpUserCleanRoom where annee<=? group by idcentrale,libellecentrale order by idcentrale asc", $_GET['anneeUserCleanRoom']);
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        $_S_serie .= '{name: "' . $totalUser[$i]['libellecentrale'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $totalUser[$i]['nb'] . ',drilldown: "' . $totalUser[$i]['libellecentrale'] . '"}]},';
    }$serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              AXE DES Y
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $_S_serie3 = "";
    foreach ($centrales as $key => $centrale) {
        if($_GET['anneeUserCleanRoom']==2013){
            $_S_serie3.="{id: '" . $centrale[0] . "',name: '" . $centrale[0] . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
        }else{
            $_S_serie3.="{id: '" . $centrale[0] . "',name: '" . $centrale[0] . ' ' . $_GET['anneeUserCleanRoom'] . "'" . ',data: [';
        }
        for ($mois = 1; $mois < 13; $mois++) {
            $nbUsercentrale = $manager->getSinglebyArray("select sum(nb) from tmpUsercleanroom where annee=? and libellecentrale =? and mois=?", array($_GET['anneeUserCleanRoom'], $centrale[0], $mois));
            if (empty($nbUsercentrale)) {
                $nbUsercentrale = 0;
            }$_S_serie3.= "['" . showMonth($mois,$lang) . "'," . $nbUsercentrale . "],";
        }$_S_serie3.=']},';
    }
    $serieY = substr($_S_serie3, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              ADMINISTRATEUR LOCAL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
}
if (IDTYPEUSER == ADMINLOCAL) {
    $title = TXT_CLEANROOMUSERNEWPROJECT;
    $_S_serie = '';
    $totalUser = $manager->getList2("select annee,sum(nb) as nb from tmpUserCleanRoom where libellecentrale=? and annee>2012 group by annee order by annee asc", LIBELLECENTRALEUSER);    
    $nb=0;
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        if($totalUser[$i]['annee']==2013){
            $nb = $manager->getSingle2("select sum(nb) as nb from tmpUserCleanRoom where libellecentrale=? and annee<=2013", LIBELLECENTRALEUSER);
        }else{
            $nb+=$totalUser[$i]['nb'];
        }
        
        
        if($totalUser[$i]['annee']==2013){            
            $_S_serie .= '{name: "' . TXT_INFERIEUR2013 . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nb . ',drilldown: "' . LIBELLECENTRALEUSER . $totalUser[$i]['annee'] . '"}]},';
        }else{
            $_S_serie .= '{name: "' . $totalUser[$i]['annee'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nb . ',drilldown: "' . LIBELLECENTRALEUSER . $totalUser[$i]['annee'] . '"}]},';
        }
    }$serie_1 = str_replace("},]}", "}]}", $_S_serie);
    $serie_01 = str_replace("},]", "}]", $serie_1);
    $serieX = substr($serie_01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              AXE DES Y
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $nbUsercentraleYear = $manager->getList2("select annee,mois,sum(nb) as nb from tmpUserCleanRoom where libellecentrale=?  group by annee,mois order by annee asc", LIBELLECENTRALEUSER);
    $_S_serie3 = "";
    foreach ($nbUsercentraleYear as $key => $year) {        
            $_S_serie3 .="{id: '" . LIBELLECENTRALEUSER . $year[0] . "',name: '" . LIBELLECENTRALEUSER . ' ' . $year[0] . "'" . ',data: [';
        for ($mois = 1; $mois < 13; $mois++) {
            $nbUsercentraleYearMonth = $manager->getSinglebyArray("select sum(nb) as nb from tmpUserCleanRoom where annee=?  and mois=? and libellecentrale=?", array($year[0], $mois, LIBELLECENTRALEUSER));
            $_S_serie3.= "['" . showMonth($mois,$lang) . "'," . $nbUsercentraleYearMonth . "],";
        }$_S_serie3.=']},';
    }
    $serieY = substr($_S_serie3, 0, -1);
}

$subtitle = TXT_CLICDETAIL;
include_once 'commun/scriptBar.php';
BD::deconnecter();
