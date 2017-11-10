<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$ressources = $manager->getList("select libelleressource,idressource,libelleressourceen from ressource");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
if (IDTYPEUSER == ADMINNATIONNAL) {
    ?>
    <table>
        <tr>
            <td>
                <select  id="anneeRessources" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeRessources',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueRessourcesProjetEnCours/<?php echo $lang . '/'; ?>' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($years); $i++) {
                                 echo '<option value="' . $years[$i]['year'] . '">' . $years[$i]['year'] . '</option>';
                             }
                             ?>
                </select>
            </td>
        </tr>
    </table>
<?php } ?>
<?php
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeRessources'])) {
    $serie = "";
    foreach ($centrales as $key => $centrale) {
        $nbressource = $manager->getSinglebyArray("SELECT count(rp.idressource_ressource) FROM ressourceprojet rp,projet p,concerne c,ressource r WHERE rp.idprojet_projet = p.idprojet "
                . "AND rp.idressource_ressource = r.idressource AND c.idprojet_projet = p.idprojet  AND c.idcentrale_centrale=? and idstatutprojet_statutprojet=? AND c.idcentrale_centrale!=? and p.trashed !=?"
                , array($centrale[1], ENCOURSREALISATION,IDCENTRALEAUTRE,TRUE));
        $serie .= '{name: "' . $centrale[0] . '", data: [{name: "' . TXT_DETAILS .
                '",y: ' . $nbressource . ',drilldown: "' . $centrale[0] . '"}]},';
    }

    $serie1 = str_replace("},]}", "}]}", $serie);
    $serie01 = str_replace("},]", "}]", $serie1);
    $serieX = substr($serie01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie02 = '';
    foreach ($centrales as $key => $centrale) {
        $serie02 .="{id: '" . $centrale[0] . "',name: '" . $centrale[0] . "',data: [";
        foreach ($years as $key => $year) {
            $nbByYear = $manager->getSinglebyArray("SELECT  count(r.idressource) FROM concerne co,projet p,ressourceprojet rp,ressource r WHERE co.idprojet_projet = p.idprojet AND rp.idprojet_projet = p.idprojet "
                    . "AND rp.idressource_ressource = r.idressource AND extract(year from p.dateprojet)<=? and co.idcentrale_centrale=?  and idstatutprojet_statutprojet=? and co.idcentrale_centrale!=? and p.trashed!=? ",
                    array($year[0], $centrale[1], ENCOURSREALISATION,IDCENTRALEAUTRE,TRUE));
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
            $nbByYear = $manager->getSinglebyArray("SELECT  count(r.idressource) FROM concerne co,projet p,ressourceprojet rp,ressource r WHERE co.idprojet_projet = p.idprojet AND rp.idprojet_projet = p.idprojet 
                AND rp.idressource_ressource = r.idressource AND extract(year from p.dateprojet)=? and co.idcentrale_centrale=?  and idstatutprojet_statutprojet=? and p.trashed!=?", array($year[0], $centrale[1], ENCOURSREALISATION,TRUE));
            if($year[0]==2013){
                   $serie3.="{id: '" . $centrale[0] . $year[0] . "',name: '" . $centrale[0] . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
            }else{
                $serie3.="{id: '" . $centrale[0] . $year[0] . "',name: '" . $centrale[0] . ' ' . $year[0] . "'" . ',data: [';
            }
            foreach ($ressources as $key => $ressource) {
                $nbByYearMois = $manager->getSinglebyArray("SELECT  count(r.idressource) FROM concerne co,projet p,ressourceprojet rp,ressource r WHERE co.idprojet_projet = p.idprojet AND rp.idprojet_projet = p.idprojet 
                AND rp.idressource_ressource = r.idressource AND  extract(year from p.dateprojet)<=? and r.idressource=?  and co.idcentrale_centrale=? and idstatutprojet_statutprojet=? and p.trashed!=?", 
                        array($year[0], $ressource[1], $centrale[1], ENCOURSREALISATION,TRUE));
                if (empty($nbByYearMois)) {
                    $nbByYearMois = 0;
                }
                if ($lang == 'fr') {
                    $serie3.= "['" . $ressource[0] . "'," . $nbByYearMois . "],";
                } else {
                    $serie3.= "['" . $ressource[2] . "'," . $nbByYearMois . "],";
                }
            }
            $serie3.=']},';
        }
    }
    $serie03 = substr($serie2 . $serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;
    $title = TXT_REPARTITIONRESSOURCEPROJETENCOURS;
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeRessources'])) {
    ?>
    <table>
        <tr>
            <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDSTATRESSOURCE; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
            </td>
        </tr>
    </table> 
    <?php
    $serie = "";
    foreach ($centrales as $key => $centrale) {
        $nbressource = $manager->getSinglebyArray("SELECT count(rp.idressource_ressource) FROM ressourceprojet rp,projet p,concerne c,ressource r WHERE rp.idprojet_projet = p.idprojet "
                . "AND rp.idressource_ressource = r.idressource AND c.idprojet_projet = p.idprojet AND c.idcentrale_centrale=? and idstatutprojet_statutprojet=?"
                . "AND extract(year from p.dateprojet)<=? and p.trashed !=?", array($centrale[1], ENCOURSREALISATION, $_GET['anneeRessources'],TRUE));
        $serie .= '{name: "' . $centrale[0] . '", data: [{name: "' . TXT_DETAILS .
                '",y: ' . $nbressource . ',drilldown: "' . $centrale[0] . $_GET['anneeRessources'] . '"}]},';
    }

    $serie1 = str_replace("},]}", "}]}", $serie);
    $serie01 = str_replace("},]", "}]", $serie1);
    $serieX = substr($serie01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $serie3 = "";
    foreach ($centrales as $key => $centrale) {
        if($_GET['anneeRessources']==2013){
            $serie3.="{id: '" . $centrale[0] . $_GET['anneeRessources'] . "',name: '" .TXT_INFERIEUR2013 . "'" . ',data: [';
        }else{
            $serie3.="{id: '" . $centrale[0] . $_GET['anneeRessources'] . "',name: '" . $_GET['anneeRessources'] . "'" . ',data: [';
        }
        foreach ($ressources as $key => $ressource) {
            $nbByYearMois = $manager->getSinglebyArray("SELECT  count(r.idressource) FROM concerne co,projet p,ressourceprojet rp,ressource r WHERE co.idprojet_projet = p.idprojet AND rp.idprojet_projet = p.idprojet 
                AND rp.idressource_ressource = r.idressource AND  extract(year from p.dateprojet)<=? and r.idressource=?  and co.idcentrale_centrale=? and idstatutprojet_statutprojet=? and p.trashed!=?
                ", array($_GET['anneeRessources'], $ressource[1], $centrale[1], ENCOURSREALISATION,TRUE));
            if (empty($nbByYearMois)) {
                $nbByYearMois = 0;
            }
            $serie3.= "['" . $ressource[0] . "'," . $nbByYearMois . "],";
        }
        $serie3.=']},';
    }

    $serie03 = substr($serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;
    $title = TXT_REPARTITIONRESSOURCEPROJETENCOURSANNEE . $_GET['anneeRessources'];
}
if (IDTYPEUSER == ADMINLOCAL) {

    $serie = "";
    foreach ($years as $key => $year) {
        $nbressource = $manager->getSinglebyArray("SELECT count(rp.idressource_ressource) FROM ressourceprojet rp,projet p,concerne c,ressource r WHERE rp.idprojet_projet = p.idprojet "
                . "AND rp.idressource_ressource = r.idressource AND c.idprojet_projet = p.idprojet AND c.idcentrale_centrale=? and idstatutprojet_statutprojet=?  "
                . "AND extract(year from p.dateprojet)<=?", array(IDCENTRALEUSER, ENCOURSREALISATION, $year[0]));
        if ($nbressource == 0) {
            $nbressource = 0;
        }
        if($year[0]==2013){
               $serie .= '{name: "' . TXT_INFERIEUR2013 . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbressource . ',drilldown: "' . $year[0] . '"}]},';
        }else{
            $serie .= '{name: "' . $year[0] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbressource . ',drilldown: "' . $year[0] . '"}]},';
        }
        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie3 = '';
    foreach ($years as $key => $year) {
        $nbressource = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? "
                . "and extract(year from dateprojet)<=? and  trashed != ? and idstatutprojet_statutprojet=? and EXTRACT(YEAR from dateprojet)>2012", array(IDCENTRALEUSER, $year[0], TRUE, ENCOURSREALISATION));
        if (empty($nbressource)) {
            $nbressource = 0;
        }
        if($year[0]==2013){
            $serie3.="{id: '" . $year[0] . "',name: '" . TXT_INFERIEUR2013 . "'" . ',data: [';
        }else{
            $serie3.="{id: '" . $year[0] . "',name: '" . $year[0] . "'" . ',data: [';
        }
        foreach ($ressources as $key => $ressource) {
            $nbByYearMois = $manager->getSinglebyArray("SELECT  count(r.idressource) FROM concerne co,projet p,ressourceprojet rp,ressource r WHERE co.idprojet_projet = p.idprojet AND rp.idprojet_projet = p.idprojet 
                AND rp.idressource_ressource = r.idressource AND  extract(year from p.dateprojet)<=? and r.idressource=?  and co.idcentrale_centrale=? and idstatutprojet_statutprojet=?  
                ", array($year[0], $ressource[1], IDCENTRALEUSER, ENCOURSREALISATION));
            if (empty($nbByYearMois)) {
                $nbByYearMois = 0;
            }
            if ($lang == 'fr') {
                $serie3.= "['" . $ressource[0] . "'," . $nbByYearMois . "],";
            } else {
                $serie3.= "['" . $ressource[2] . "'," . $nbByYearMois . "],";
            }
        }$serie3.=']},';
    }
    $serieY0 = str_replace("},]}", "}]}", $serie3);
    $serieY1 = str_replace("],]", "]]", $serieY0);
    $serieY = substr($serieY1, 0, -1);

    $subtitle = TXT_CLICDETAIL;
    $title = TXT_REPARTITIONRESSOURCEPROJETENCOURS;
}$xasisTitle = TXT_NOMBREOCCURRENCE;
include_once 'commun/scriptBar.php';
