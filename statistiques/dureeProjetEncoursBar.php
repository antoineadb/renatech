<?php
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'class/Manager.php';
$db = BD::connecter();
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE  order by idcentrale asc ", IDAUTRECENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL) {
    ?>
    <table>
        <tr>
            <td>
                <select  id="anneeDureeeProjet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeDureeeProjet',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statDureeProjetEnCours/<?php echo $lang . '/'; ?>' + this.value)" >
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
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                           CONSTRUCTION DE LA TABLE
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

/* < 1 an */
$donneeProjetInfUnAn = $manager->getListbyArray("select dureeestime,datedebutprojet,idprojet from projet,concerne where idprojet=idprojet_projet and idstatutprojet_statutprojet=8 and periodestime =? "
        . "and trashed != ?", array(1, TRUE));
$nbdonneeProjetInfUnAn = count($donneeProjetInfUnAn);
$donneeProjetInfTroisAns = $manager->getListbyArray("select dureeestime,datedebutprojet,idprojet from projet,concerne where idprojet=idprojet_projet and idstatutprojet_statutprojet=8 and periodestime =? "
        . "and trashed != ?", array(2, TRUE));
$nbdonneeProjetInfTroisAns = count($donneeProjetInfTroisAns);
$donneeProjetSupTroisAns = $manager->getListbyArray("select dureeestime,datedebutprojet,idprojet from projet,concerne where idprojet=idprojet_projet and idstatutprojet_statutprojet=8 and periodestime =? "
        . "and trashed != ?", array(3, TRUE));
$nbdonneeProjetSupTroisAns = count($donneeProjetSupTroisAns);
$manager->exeRequete("drop table if exists tmpprojet;");
$manager->exeRequete("create table tmpprojet as (select idprojet,dureeestime,datedebutprojet,periodestime,idcentrale_centrale  from projet,concerne where idprojet=idprojet_projet and idstatutprojet_statutprojet=8"
        . "and periodestime is not null and periodestime!=0)");
$manager->exeRequete("ALTER TABLE tmpprojet ADD COLUMN rang varchar(10);");
for ($i = 0; $i < $nbdonneeProjetInfUnAn; $i++) {
    if ($donneeProjetInfUnAn[$i]['dureeestime'] < 365) {//<1an
        $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(1, $donneeProjetInfUnAn[$i]['idprojet']));
    } elseif ($donneeProjetInfUnAn[$i]['dureeestime'] > 365 && $donneeProjetInfUnAn[$i]['dureeestime'] < 1094) {//>1an <3 ans
        $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(2, $donneeProjetInfUnAn[$i]['idprojet']));
    } else {//>3ans
        $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(3, $donneeProjetInfUnAn[$i]['idprojet']));
    }
}
/* > 1 an < 3 ans */
for ($i = 0; $i < $nbdonneeProjetInfTroisAns; $i++) {
    if ($donneeProjetInfTroisAns[$i]['dureeestime'] < 12) {//<1an
        $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(1, $donneeProjetInfTroisAns[$i]['idprojet']));
    } elseif ($donneeProjetInfTroisAns[$i]['dureeestime'] > 12 && $donneeProjetInfTroisAns[$i]['dureeestime'] < 35) {//>1an <3 ans
        $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(2, $donneeProjetInfTroisAns[$i]['idprojet']));
    } else {//>3ans
        $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(3, $donneeProjetInfTroisAns[$i]['idprojet']));
    }
}
/* > 3 ans */
for ($i = 0; $i < $nbdonneeProjetSupTroisAns; $i++) {
    if ($donneeProjetSupTroisAns[$i]['dureeestime'] < 1) {//<1an
        $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(1, $donneeProjetSupTroisAns[$i]['idprojet']));
    } elseif ($donneeProjetSupTroisAns[$i]['dureeestime'] > 1 && $donneeProjetSupTroisAns[$i]['dureeestime'] < 3) {//>1an <3 ans
        $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(2, $donneeProjetSupTroisAns[$i]['idprojet']));
    } else {//>3ans
        $manager->getRequete("update tmpprojet set rang = ? where idprojet=?", array(3, $donneeProjetSupTroisAns[$i]['idprojet']));
    }
}
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeDureeeProjet'])) {
    $title = TXT_DUREEPROJETENCOURS;
    $subtitle = "";
    $xasisTitle = "";

    $nbProjet1 = $manager->getSingle2("select count(idprojet) from tmpprojet where rang=?", 1);
    $nbProjet2 = $manager->getSingle2("select count(idprojet) from tmpprojet where rang=?", 2);
    $nbProjet3 = $manager->getSingle2("select count(idprojet) from tmpprojet where rang=?", 3);
    
    
    $serie1 = '{name: "' . TXT_1AN . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet1 . ',drilldown: "' . 'rang 1' . '"}]},';
    $serie2 = '{name: "' . TXT1_3ANS . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet2 . ',drilldown: "' . 'rang 2' . '"}]},';
    $serie3 = '{name: "' . TXT3ANS . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet3 . ',drilldown: "' . 'rang 3' . '"}]},';

    $serie01 = str_replace("},]}", "}]}", $serie1);
    $serie02 = str_replace("},]}", "}]}", $serie2);
    $serie03 = str_replace("},]}", "}]}", $serie3);
    $serie001 = str_replace("},]", "}]", $serie01);
    $serie002 = str_replace("},]", "}]", $serie02);
    $serie003 = str_replace("},]", "}]", $serie03);
    $serieX = substr($serie001 . $serie002 . $serie003, 0, -1);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                              FIN  DE LA DE LA'ABCISSE DES X
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    

    $serieRang1 = "{id: '" . 'rang 1' . "',name: '" . ' < 1 an' . "',data: [";
    $serieRang2 = "{id: '" . 'rang 2' . "',name: '" . ' >= 1 an et < 3 ans' . "',data: [";
    $serieRang3 = "{id: '" . 'rang 3' . "',name: '" . ' > 3 ans' . "',data: [";
    foreach ($years as $key => $year) {
        $nbProjet1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? AND extract(year from datedebutprojet)<=?", array(1, $year[0]));
        $nbProjet2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? AND extract(year from datedebutprojet)<=?", array(2, $year[0]));
        $nbProjet3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? AND extract(year from datedebutprojet)<=?", array(3, $year[0]));
        if($year[0]==2013){
            $serieRang1 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbProjet1 . " , drilldown: '" . 'rang 1' . $year[0] . "'},";
            $serieRang2 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbProjet2 . " , drilldown: '" . 'rang 2' . $year[0] . "'},";
            $serieRang3 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbProjet3 . " , drilldown: '" . 'rang 3' . $year[0] . "'},";
        }else{
            $serieRang1 .="{name: '" . $year[0] . "', y: " . $nbProjet1 . " , drilldown: '" . 'rang 1' . $year[0] . "'},";
            $serieRang2 .="{name: '" . $year[0] . "', y: " . $nbProjet2 . " , drilldown: '" . 'rang 2' . $year[0] . "'},";
            $serieRang3 .="{name: '" . $year[0] . "', y: " . $nbProjet3 . " , drilldown: '" . 'rang 3' . $year[0] . "'},";
        }
    }
    $serieRang1 .= "]},";
    $serieRang2 .= "]},";
    $serieRang3 .= "]},";



    foreach ($years as $key => $year) {
        if($year[0]==2013){
            $serieRang1 .= "{id: '" . 'rang 1' . $year[0] . "',name: '" . TXT_INFERIEUR2013 . ' < 1 an' . "',data: [";
            $serieRang2 .= "{id: '" . 'rang 2' . $year[0] . "',name: '" . TXT_INFERIEUR2013 . ' >= 1 an et < 3 ans' . "',data: [";
            $serieRang3 .= "{id: '" . 'rang 3' . $year[0] . "',name: '" . TXT_INFERIEUR2013 . ' > 3 ans' . "',data: [";
        }else{
               $serieRang1 .= "{id: '" . 'rang 1' . $year[0] . "',name: '" . $year[0] . ' < 1 an' . "',data: [";
            $serieRang2 .= "{id: '" . 'rang 2' . $year[0] . "',name: '" . $year[0] . ' >= 1 an et < 3 ans' . "',data: [";
            $serieRang3 .= "{id: '" . 'rang 3' . $year[0] . "',name: '" . $year[0] . ' > 3 ans' . "',data: [";
        }

        foreach ($centrales as $key => $centrale) {
            $nbprojetRang1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=? and extract(year from datedebutprojet)<=?", array(1, $centrale[1], $year[0]));
            $nbprojetRang2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=? and extract(year from datedebutprojet)<=?", array(2, $centrale[1], $year[0]));
            $nbprojetRang3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=? and extract(year from datedebutprojet)<=?", array(3, $centrale[1], $year[0]));
            $serieRang1 .="{name: '" . $centrale[0] . "', y: " . $nbprojetRang1 . " , drilldown: '" . 'rang 1' . $centrale[0] . $year[0] . "'},";
            $serieRang2 .="{name: '" . $centrale[0] . "', y: " . $nbprojetRang2 . " , drilldown: '" . 'rang 2' . $centrale[0] . $year[0] . "'},";
            $serieRang3 .="{name: '" . $centrale[0] . "', y: " . $nbprojetRang3 . " , drilldown: '" . 'rang 3' . $centrale[0] . $year[0] . "'},";
        }
        $serieRang1 .="]},";
        $serieRang2 .="]},";
        $serieRang3 .="]},";
    }
    $serie0 = $serieRang1 . $serieRang2 . $serieRang3;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeDureeeProjet'])) {
    ?>
    <table>
        <tr>
            <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDDUREEPROJETENCOURS; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
            </td>
        </tr>
    </table> 
    <?php
    if($_GET['anneeDureeeProjet']==2013){
        $title = TXT_DUREEPROJETENCOURSDATE . TXT_INFERIEUR2013;
    }else{
        $title = TXT_DUREEPROJETENCOURSDATE . $_GET['anneeDureeeProjet'];
    }
    $subtitle = "";
    $xasisTitle = "";

    $nbProjet1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and extract(year from datedebutprojet)<=?", array(1, $_GET['anneeDureeeProjet']));
    $nbProjet2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and extract(year from datedebutprojet)<=?", array(2, $_GET['anneeDureeeProjet']));
    $nbProjet3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and extract(year from datedebutprojet)<=?", array(3, $_GET['anneeDureeeProjet']));
    $serie1 = '{name: "' . '< 1 an' . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet1 . ',drilldown: "' . 'rang 1' . '"}]},';
    $serie2 = '{name: "' . '>=  1 an et < 3 ans' . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet2 . ',drilldown: "' . 'rang 2' . '"}]},';
    $serie3 = '{name: "' . '> 3 ans' . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet3 . ',drilldown: "' . 'rang 3' . '"}]},';

    $serie01 = str_replace("},]}", "}]}", $serie1);
    $serie02 = str_replace("},]}", "}]}", $serie2);
    $serie03 = str_replace("},]}", "}]}", $serie3);
    $serie001 = str_replace("},]", "}]", $serie01);
    $serie002 = str_replace("},]", "}]", $serie02);
    $serie003 = str_replace("},]", "}]", $serie03);
    $serieX = substr($serie001 . $serie002 . $serie003, 0, -1);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                              FIN  DE LA DE LA'ABCISSE DES X
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    if($_GET['anneeDureeeProjet']==2013){
        $serieRang1 = "{id: '" . 'rang 1' . "',name: '" . TXT_INFERIEUR2013 . ' < 1 an' . "',data: [";
        $serieRang2 = "{id: '" . 'rang 2' . "',name: '" . TXT_INFERIEUR2013 . ' >= 1 an et < 3 ans' . "',data: [";
        $serieRang3 = "{id: '" . 'rang 3' . "',name: '" . TXT_INFERIEUR2013 . ' > 3 ans' . "',data: [";
    }else{
        $serieRang1 = "{id: '" . 'rang 1' . "',name: '" . $_GET['anneeDureeeProjet'] . ' < 1 an' . "',data: [";
        $serieRang2 = "{id: '" . 'rang 2' . "',name: '" . $_GET['anneeDureeeProjet'] . ' >= 1 an et < 3 ans' . "',data: [";
        $serieRang3 = "{id: '" . 'rang 3' . "',name: '" . $_GET['anneeDureeeProjet'] . ' > 3 ans' . "',data: [";
    }

    foreach ($centrales as $key => $centrale) {
        $nbprojetRang1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=? and extract(year from datedebutprojet)<=?", array(1, $centrale[1], $_GET['anneeDureeeProjet']));
        $nbprojetRang2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=? and extract(year from datedebutprojet)<=?", array(2, $centrale[1], $_GET['anneeDureeeProjet']));
        $nbprojetRang3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=? and extract(year from datedebutprojet)<=?", array(3, $centrale[1], $_GET['anneeDureeeProjet']));
        $serieRang1 .="{name: '" . $centrale[0] . "', y: " . $nbprojetRang1 . " , drilldown: '" . 'rang 1' . $centrale[0] . $_GET['anneeDureeeProjet'] . "'},";
        $serieRang2 .="{name: '" . $centrale[0] . "', y: " . $nbprojetRang2 . " , drilldown: '" . 'rang 2' . $centrale[0] . $_GET['anneeDureeeProjet'] . "'},";
        $serieRang3 .="{name: '" . $centrale[0] . "', y: " . $nbprojetRang3 . " , drilldown: '" . 'rang 3' . $centrale[0] . $_GET['anneeDureeeProjet'] . "'},";
    }
    $serieRang1 .="]},";
    $serieRang2 .="]},";
    $serieRang3 .="]},";

    $serie0 = $serieRang1 . $serieRang2 . $serieRang3;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
}

if (IDTYPEUSER == ADMINLOCAL) {
    $title = TXT_DUREEPROJETENCOURS;
    $subtitle = "";
    $xasisTitle = "";

    $nbProjet1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=?",array(1,IDCENTRALEUSER));
    $nbProjet2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=?",array(2,IDCENTRALEUSER));
    $nbProjet3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=?",array(3,IDCENTRALEUSER));    
    $serie1 = '{name: "' . TXT_1AN . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet1 . ',drilldown: "' . 'rang 1' . '"}]},';
    $serie2 = '{name: "' . TXT1_3ANS . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet2 . ',drilldown: "' . 'rang 2' . '"}]},';
    $serie3 = '{name: "' . TXT3ANS  . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet3 . ',drilldown: "' . 'rang 3' . '"}]},';

    $serie01 = str_replace("},]}", "}]}", $serie1);
    $serie02 = str_replace("},]}", "}]}", $serie2);
    $serie03 = str_replace("},]}", "}]}", $serie3);
    $serie001 = str_replace("},]", "}]", $serie01);
    $serie002 = str_replace("},]", "}]", $serie02);
    $serie003 = str_replace("},]", "}]", $serie03);
    $serieX = substr($serie001 . $serie002 . $serie003, 0, -1);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                              FIN  DE LA DE LA'ABCISSE DES X
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    

    $serieRang1 = "{id: '" . 'rang 1' . "',name: '" . ' < 1 an' . "',data: [";
    $serieRang2 = "{id: '" . 'rang 2' . "',name: '" . ' >= 1 an et < 3 ans' . "',data: [";
    $serieRang3 = "{id: '" . 'rang 3' . "',name: '" . ' > 3 ans' . "',data: [";
    foreach ($years as $key => $year) {
        $nbProjet1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? AND extract(year from datedebutprojet)<=? and idcentrale_centrale=?", array(1, $year[0],IDCENTRALEUSER));
        $nbProjet2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? AND extract(year from datedebutprojet)<=? and idcentrale_centrale=?", array(2, $year[0],IDCENTRALEUSER));
        $nbProjet3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? AND extract(year from datedebutprojet)<=? and idcentrale_centrale=?", array(3, $year[0],IDCENTRALEUSER));
        if($year[0]==2013){
            $serieRang1 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbProjet1 . " , drilldown: '" . 'rang 1' . $year[0] . "'},";
            $serieRang2 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbProjet2 . " , drilldown: '" . 'rang 2' . $year[0] . "'},";
            $serieRang3 .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbProjet3 . " , drilldown: '" . 'rang 3' . $year[0] . "'},";
        }else{
            $serieRang1 .="{name: '" . $year[0] . "', y: " . $nbProjet1 . " , drilldown: '" . 'rang 1' . $year[0] . "'},";
            $serieRang2 .="{name: '" . $year[0] . "', y: " . $nbProjet2 . " , drilldown: '" . 'rang 2' . $year[0] . "'},";
            $serieRang3 .="{name: '" . $year[0] . "', y: " . $nbProjet3 . " , drilldown: '" . 'rang 3' . $year[0] . "'},";
        }
    }
    $serieRang1 .= "]},";
    $serieRang2 .= "]},";
    $serieRang3 .= "]},";



    foreach ($years as $key => $year) {
        $serieRang1 .= "{id: '" . 'rang 1' . $year[0] . "',name: '" . $year[0] . ' < 1 an' . "',data: [";
        $serieRang2 .= "{id: '" . 'rang 2' . $year[0] . "',name: '" . $year[0] . ' >= 1 an et < 3 ans' . "',data: [";
        $serieRang3 .= "{id: '" . 'rang 3' . $year[0] . "',name: '" . $year[0] . ' > 3 ans' . "',data: [";

        for ($mois = 1; $mois < 13; $mois++) {
            $nbprojetRang1 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=? and extract(year from datedebutprojet)=? "
                    . "and extract(month from datedebutprojet)=?", array(1, IDCENTRALEUSER, $year[0],$mois));
            $nbprojetRang2 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=? and extract(year from datedebutprojet)=?  "
                    . "and extract(month from datedebutprojet)=?",array(2, IDCENTRALEUSER, $year[0],$mois));
            $nbprojetRang3 = $manager->getSinglebyArray("select count(idprojet) from tmpprojet where rang=? and idcentrale_centrale=? and extract(year from datedebutprojet)=?  "
                    . "and extract(month from datedebutprojet)=?",array(3, IDCENTRALEUSER, $year[0],$mois));
            $serieRang1 .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbprojetRang1 . " , drilldown: '" . 'rang 1' . LIBELLECENTRALEUSER . $year[0] . "'},";
            $serieRang2 .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbprojetRang2 . " , drilldown: '" . 'rang 2' . LIBELLECENTRALEUSER . $year[0] . "'},";
            $serieRang3 .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbprojetRang3 . " , drilldown: '" . 'rang 3' . LIBELLECENTRALEUSER . $year[0] . "'},";
        }
        $serieRang1 .="]},";
        $serieRang2 .="]},";
        $serieRang3 .="]},";
    }
    $serie0 = $serieRang1 . $serieRang2 . $serieRang3;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
}
include_once 'commun/scriptBar.php';
BD::deconnecter();