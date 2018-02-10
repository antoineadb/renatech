<?php
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$sourcefinancements = $manager->getList("select libellesourcefinancement,idsourcefinancement,libellesourcefinancementen from sourcefinancement");
$nbsf = count($sourcefinancements);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
if (IDTYPEUSER == ADMINNATIONNAL) {
    ?>
    <table>
        <tr>
            <td>
                <select  id="anneeSF" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeSF',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueSFProjetEnCours/<?php echo $lang . '/'; ?>' + this.value)" >
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
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeSF'])) {
    for ($i = 0; $i < $nbsf; $i++) {
        $centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
        $serie = "";
        $nbtotal = 0;
        foreach ($centrales as $key => $centrale) {
            $nbsource = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co WHERE "
                    . "ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                    . "AND co.idcentrale_centrale=? AND co.idstatutprojet_statutprojet=?", array($centrale[1], ENCOURSREALISATION));
            $nbtotal+=$nbsource;
            $serie .= '{name: "' . $centrale[0] . '",color:"'. couleurGraph($centrale['idcentrale']).'", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbsource . ',drilldown: "' . $centrale[0] . '"},]},';           
        }
        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie02 = '';
    foreach ($centrales as $key => $centrale) {
        $serie02 .="{id: '" . $centrale[0] . "',name: '" . $centrale[0] . "',data: [";
        foreach ($years as $key => $year) {
            $nbByYear = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                    . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                    . "AND co.idcentrale_centrale=? AND EXTRACT(YEAR from p.dateprojet)<=?  AND co.idstatutprojet_statutprojet=?", array($centrale[1], $year[0], ENCOURSREALISATION));
            if (empty($nbByYear)) {
                $nbByYear = 0;
            }
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
            $nbByYear = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                    . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                    . "AND co.idcentrale_centrale=? AND EXTRACT(YEAR from p.dateprojet)=? AND co.idstatutprojet_statutprojet=?", array($centrale[1], $year[0], ENCOURSREALISATION));
            
            if($year[0]==2013){
                $serie3.="{id: '" . $centrale[0] . $year[0] . "',name: '".$centrale[0] .' '.  ' ' .TXT_INFERIEUR2013. "'" . ',data: [';
            }else{
                $serie3.="{id: '" . $centrale[0] . $year[0] . "',name: '".$centrale[0] .' '.  $year[0] . "'" . ',data: [';
                
            }
            
            foreach ($sourcefinancements as $key => $sourcefinancement) {
                $nbByYearBySF = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                        . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                        . "AND co.idcentrale_centrale=? AND EXTRACT(YEAR from p.dateprojet)<=? and s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=?", array($centrale[1], $year[0], $sourcefinancement[1], ENCOURSREALISATION));
                if (empty($nbByYearBySF)) {
                    $nbByYearBySF = 0;
                }
                if ($lang == 'fr') {
                    $serie3.= "['" . $sourcefinancement[0] . "'," . $nbByYearBySF . "],";
                } else {
                    $serie3.= "['" . $sourcefinancement[2] . "'," . $nbByYearBySF . "],";
                }
            }
            $serie3.=']},';
        }
    }

    $serie03 = substr($serie2 . $serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_NBSF . ': <b>' . $nbtotal . '</b>';
    $title = TXT_ORIGINESOURCEFINANCEMENT;
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeSF'])) {
    ?>
    <table>
        <tr>
            <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDSTATSF; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
            </td>
        </tr>
    </table> 
    <?php
    for ($i = 0; $i < $nbsf; $i++) {
        $centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
        $serie = "";
        $nbtotal = 0;
        foreach ($centrales as $key => $centrale) {
            $nbsource = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co WHERE "
                    . "ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                    . "AND co.idcentrale_centrale=? AND co.idstatutprojet_statutprojet=?  AND EXTRACT(YEAR from p.dateprojet)<=?", array($centrale[1], ENCOURSREALISATION, $_GET['anneeSF']));
            $nbtotal+=$nbsource;
            $serie .= '{name: "' . $centrale[0] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbsource . ',drilldown: "' . $centrale[0] . $_GET['anneeSF'] . '"}]},';
        }
        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
    $serie3 = "";
    foreach ($centrales as $key => $centrale) {
        $nbByYear = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                . "AND co.idcentrale_centrale=? AND EXTRACT(YEAR from p.dateprojet)=? AND co.idstatutprojet_statutprojet=?", array($centrale[1], $_GET['anneeSF'], ENCOURSREALISATION));
        if($_GET['anneeSF']==2013){
            $serie3.="{id: '" . $centrale[0] . $_GET['anneeSF'] . "',name: '" .$centrale[0] .' '.  TXT_INFERIEUR2013 . "'" . ',data: [';
        }else{
            $serie3.="{id: '" . $centrale[0] . $_GET['anneeSF'] . "',name: '" .$centrale[0] .' '.  $_GET['anneeSF'] . "'" . ',data: [';
        }
        
        foreach ($sourcefinancements as $key => $sourcefinancement) {
            $nbByYearBySF = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                    . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                    . "AND co.idcentrale_centrale=? AND EXTRACT(YEAR from p.dateprojet)<=? and s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=?", array($centrale[1], $_GET['anneeSF'], $sourcefinancement[1], ENCOURSREALISATION));
            if (empty($nbByYearBySF)) {
                $nbByYearBySF = 0;
            }
            if ($lang == 'fr') {
                $serie3.= "['" . $sourcefinancement[0] . "'," . $nbByYearBySF . "],";
            } else {
                $serie3.= "['" . $sourcefinancement[2] . "'," . $nbByYearBySF . "],";
            }
        }
        $serie3.=']},';
    }
    $serie03 = substr($serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_NBSF . ': <b>' . $nbtotal . '</b>';
    if($_GET['anneeSF']==2013){
        $title = TXT_ORIGINESOURCEFINANCEMENTANNEE . TXT_INFERIEUR2013;
    }else{
        $title = TXT_ORIGINESOURCEFINANCEMENTANNEE . $_GET['anneeSF'];
    }
}
if (IDTYPEUSER == ADMINLOCAL) {
    $serie = "";
    foreach ($sourcefinancements as $key => $sourcefinancement) {
        $nbsource = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co WHERE "
                . "ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                . "AND co.idcentrale_centrale=? AND s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=?", array(IDCENTRALEUSER, $sourcefinancement[1], ENCOURSREALISATION));
        if ($lang == 'fr') {
            $sf = $sourcefinancement[0];
        } else {
            $sf = $sourcefinancement[2];
        }
        $serie .= '{name: "' . $sf . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbsource . ',drilldown: "' . $sf . '"}]},';
    }
    $serie1 = str_replace("},]}", "}]}", $serie);
    $serie01 = str_replace("},]", "}]", $serie1);
    $serieX = substr($serie01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie02 = '';
    foreach ($sourcefinancements as $key => $sourcefinancement) {
        if ($lang == 'fr') {
            $sf = $sourcefinancement[0];
        } else {
            $sf = $sourcefinancement[2];
        }
        $serie02 .="{id: '" . $sf . "',name: '" . $sf . "',data: [";
        foreach ($years as $key => $year) {
            $nbByYear = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                    . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                    . "AND co.idcentrale_centrale=? AND EXTRACT(YEAR from p.dateprojet)<=? AND s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=?", array(IDCENTRALEUSER, $year[0], $sourcefinancement[1], ENCOURSREALISATION));
            if (empty($nbByYear)) {
                $nbByYear = 0;
            }
            if($year[0]==2013){
                $serie02 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYear . " , drilldown: '" . $sf . $year[0] . "'},";
            }else{
                $serie02 .="{name: '" . $year[0] . "', y: " . $nbByYear . " , drilldown: '" . $sf . $year[0] . "'},";
            }
            
        }$serie02 .="]},";
    }$serie2 = str_replace("},]}", "}]}", $serie02);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $serie3 = "";
    $nbSf=0;
    foreach ($years as $key => $year) {
        foreach ($sourcefinancements as $key => $sourcefinancement) {
            if ($lang == 'fr') {
                $sf = $sourcefinancement[0];
            } else {
                $sf = $sourcefinancement[2];
            }
            $nbByYear = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                    . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                    . "AND co.idcentrale_centrale=? AND EXTRACT(YEAR from p.dateprojet)<=? AND s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=?", array(IDCENTRALEUSER, $year[0], $sourcefinancement[1], ENCOURSREALISATION));
            $serie3.="{id: '" . $sf . $year[0] . "',name: '" .$sf.' '. $year[0] . "'" . ',data: [';            
            for ($mois = 1; $mois < 13; $mois++) {
                $nbByYearMois = $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                        . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                        . "AND co.idcentrale_centrale=? AND EXTRACT(YEAR from p.dateprojet)<=? AND EXTRACT(MONTH from p.dateprojet)=? AND s.idsourcefinancement=? AND co.idstatutprojet_statutprojet=?", array(IDCENTRALEUSER, $year[0], $mois, $sourcefinancement[1], ENCOURSREALISATION));
                if (empty($nbByYearMois)) {
                    $nbByYearMois = 0;
                }
                $serie3.= "['" . showMonth($mois,$lang) . "'," . $nbByYearMois . "],";
            }
            $serie3.=']},';
        }
    }
    $serie03 = substr($serie2 . $serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $subtitle = TXT_NBSF .$nbSf.': <b>' . $manager->getSinglebyArray("SELECT count(s.idsourcefinancement) FROM projet p,sourcefinancement s,projetsourcefinancement ps,concerne co "
                    . "WHERE ps.idsourcefinancement_sourcefinancement = s.idsourcefinancement AND ps.idprojet_projet = p.idprojet AND co.idprojet_projet = p.idprojet  "
                    . "and co.idcentrale_centrale=?  AND co.idstatutprojet_statutprojet=?", array(IDCENTRALEUSER,ENCOURSREALISATION)) . '</b>';
    $title = TXT_ORIGINESOURCEFINANCEMENT;
}

$xaxis = '[' . substr($string0, 0, -1) . ']';
$yaxis = '[' . substr($string1, 0, -1) . ']';

$xasisTitle = TXT_NOMBREOCCURRENCE;
include_once 'commun/scriptBar.php';
