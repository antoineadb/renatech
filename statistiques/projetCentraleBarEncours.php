<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$statutProjets = $manager->getList2("select libellestatutprojet,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet  asc",TRANSFERERCENTRALE);
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneedatecreation from utilisateur order by anneedatecreation asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeProjetEncours'])) {  ?>
    <table>
        <tr>
            <td>
                <select  id="anneeProjetEncours" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeProjetEncours',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php  echo "/" . REPERTOIRE; ?>/statistiqueProjetEnCours/<?php echo $lang.'/'; ?>' + this.value)" >
                                 <?php
                                    for ($i = 0; $i < count($arraydate); $i++) {
                                       echo '<option value="' . ($arraydate[$i]['anneedatecreation']) . '">' . $arraydate[$i]['anneedatecreation'] . '</option>';
                                    }
                                 ?>
                </select>
            </td>
        </tr>
    </table>
<table>
    <tr>
        <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE;?>/chxStatistique/<?php echo $lang.'/'.IDNBRUNNINGPROJECT; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
        </td>
    </tr>
</table>
 <?php 
    
    $serie = "";
    foreach ($centrales as $key => $centrale) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? and  trashed != ?"
                    . "and idstatutprojet_statutprojet=? and EXTRACT(YEAR from dateprojet)<=? and EXTRACT(YEAR from dateprojet)>2012 AND idcentrale!=?",array($centrale[1],TRUE,ENCOURSREALISATION,$_GET['anneeProjetEncours'],IDCENTRALEAUTRE));
            $serie .= '{name: "' . $centrale[0].' '.$_GET['anneeProjetEncours'] . '", data: [{name: "' . TXT_DETAILS .
                    '",y: ' . $nbProjet . ',drilldown: "' . $centrale[0].$_GET['anneeProjetEncours'] . '"}]},';

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
$serie3 = '';
foreach ($centrales as $key => $centrale) { 
    $serie3.="{id: '" . $centrale[0].$_GET['anneeProjetEncours'] . "',name: '" .$centrale[0].' '. $_GET['anneeProjetEncours'] . "'" . ',data: [';
    for ($mois = 1; $mois < 13; $mois++) {
        $nbByYearByMonth = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,statutprojet WHERE idprojet_projet = idprojet AND idstatutprojet_statutprojet = idstatutprojet "
                . "AND  extract(year from dateprojet)=? and idstatutprojet=? and  trashed != ? and extract(month from dateprojet)=? AND idcentrale_centrale=? AND idcentrale_centrale!=?", 
                array($_GET['anneeProjetEncours'],ENCOURSREALISATION,TRUE, $mois,$centrale[1],IDCENTRALEAUTRE));
        if (empty($nbByYearByMonth)) {$nbByYearByMonth = 0;}
        $serie3.= '["' . showMonth($mois, $lang) . '",' . $nbByYearByMonth . '],';
    }$serie3.=']},';
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    $serie03 = substr($serie3 , 0, -1);
    $seriey = str_replace("],]}", "]]}", $serie03);
    $serieY = str_replace("},]}", "}]}", $seriey);
    $subtitle = TXT_CLICDETAIL;
    $title = TXT_NBRUNNINGPROJETFORTHEYEAR.' '.$_GET['anneeProjetEncours'];
    $xasisTitle = TXT_NOMBREOCCURRENCE;

}elseif (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeProjetEncours'])) {
?><table>
<tr>
    <td>
        <select  id="anneeProjetEncours" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeProjetEncours',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                 style="width: 250px;margin-left:35px" 
                 onchange="window.location.replace('<?php  echo "/" . REPERTOIRE; ?>/statistiqueProjetEnCours/<?php echo $lang.'/'; ?>' + this.value)" >
                         <?php
                            for ($i = 0; $i < count($arraydate); $i++) {
                               echo '<option value="' . ($arraydate[$i]['anneedatecreation']) . '">' . $arraydate[$i]['anneedatecreation'] . '</option>';
                            }
                         ?>
        </select>
    </td>
</tr>
</table>
    <?php if (isset($_GET['anneeProjetEncours'])){?>
<table>
    <tr>
        <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE;?>/chxStatistique/<?php echo $lang.'/'.IDNBRUNNINGPROJECT; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
        </td>
    </tr>
</table>
<?php     }
    $serie = "";
    foreach ($centrales as $key => $centrale) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? and  trashed != ?"
                    . "and idstatutprojet_statutprojet=? and EXTRACT(YEAR from dateprojet)>2012 ",array($centrale[1],TRUE,ENCOURSREALISATION));
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
        $nbByYear = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? "
                . "and extract(year from dateprojet)<=? and  trashed != ? and idstatutprojet_statutprojet=? and EXTRACT(YEAR from dateprojet)>2012 ", array($centrale[1],$year[0],TRUE,ENCOURSREALISATION));
        if (empty($nbByYear)) {$nbByYear = 0;}
        $serie02 .="{name: '" . $year[0] . "', y: " . $nbByYear . " , drilldown: '" . $centrale[0] . $year[0] . "'},";
    }$serie02 .="]},";        
}
$serie3 = '';
foreach ($centrales as $key => $centrale) { 
    foreach ($years as $key => $year) {
            $nbByYear = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet "
                    . "and extract(year from dateprojet)<=? and  trashed != ? and idstatutprojet_statutprojet=? and EXTRACT(YEAR from dateprojet>2012", array($year[0],TRUE,ENCOURSREALISATION));
            if (empty($nbByYear)) {$nbByYear = 0;}
            $serie3.="{id: '" . $centrale[0].$year[0] . "',name: '" .$centrale[0]. $year[0] . "'" . ',data: [';
            for ($mois = 1; $mois < 13; $mois++) {
                $nbByYearByMonth = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,statutprojet WHERE idprojet_projet = idprojet AND idstatutprojet_statutprojet = idstatutprojet "
                        . "AND  extract(year from dateprojet)=? and idstatutprojet=? and  trashed != ? and extract(month from dateprojet)=? AND idcentrale_centrale=? ", 
                        array($year[0],ENCOURSREALISATION,TRUE, $mois,$centrale[1]));
                if (empty($nbByYearByMonth)) {$nbByYearByMonth = 0;}
                $serie3.= '["' . showMonth($mois, $lang) . '",' . $nbByYearByMonth . '],';
            }$serie3.=']},';       
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $serie03 = substr($serie02.$serie3 , 0, -1);
    $seriey = str_replace("],]}", "]]}", $serie03);
    $serieY = str_replace("},]}", "}]}", $seriey);
    $subtitle = TXT_CLICDETAIL;
    $title = TXT_NBRUNNINGPROJET;
    $xasisTitle = TXT_NOMBREOCCURRENCE;
} 

if (IDTYPEUSER == ADMINLOCAL) {
    $serie = "";
    foreach ($years as $key => $year) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? "
                    . "and extract(year from dateprojet)<=? and  trashed != ? and idstatutprojet_statutprojet=? and EXTRACT(YEAR from dateprojet)>2012", array(IDCENTRALEUSER,$year[0],TRUE,ENCOURSREALISATION));            
            if($nbProjet==0){$nbProjet=0;}
            $serie .= '{name: "' . $year[0] . '", data: [{name: "' . TXT_DETAILS .
                    '",y: ' . $nbProjet . ',drilldown: "' . $year[0] . '"}]},';

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
$serie3 = '';
foreach ($years as $key => $year) {
        $nbByYear = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? "
                . "and extract(year from dateprojet)<=? and  trashed != ? and idstatutprojet_statutprojet=? and EXTRACT(YEAR from dateprojet)>2012", array(IDCENTRALEUSER,$year[0],TRUE,ENCOURSREALISATION));
        if (empty($nbByYear)) {$nbByYear = 0;}
        $serie3.="{id: '" . $year[0] . "',name: '" . $year[0] . "'" . ',data: [';
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearByMonth = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,statutprojet WHERE idprojet_projet = idprojet AND idstatutprojet_statutprojet = idstatutprojet "
                    . "AND idcentrale_centrale = ? AND  extract(year from dateprojet)=? and idstatutprojet=? and  trashed != ? and extract(month from dateprojet)=?", 
                    array(IDCENTRALEUSER,$year[0],ENCOURSREALISATION,TRUE, $mois));
            if (empty($nbByYearByMonth)) {$nbByYearByMonth = 0;}
            $serie3.= '["' . showMonth($mois, $lang) . '",' . $nbByYearByMonth . '],';
        }$serie3.=']},';       
    }
$serieY0 = str_replace("},]}", "}]}", $serie3);
$serieY1 =  str_replace("],]", "]]",$serieY0);
$serieY=  substr($serieY1, 0,-1);
$subtitle = TXT_CLICDETAIL;
$title = TXT_NBRUNNINGPROJET;
$xasisTitle = TXT_PROJETDATESTATUT;
}
include_once 'commun/scriptBar.php';
        