<?php 
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$statutProjets = $manager->getList2("select libellestatutprojet,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet  asc",TRANSFERERCENTRALE);
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneedatecreation from utilisateur order by anneedatecreation asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!= TRUE order by idcentrale asc", IDAUTRECENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL &&  isset($_GET['anneeNouveauProjet'])) {?>
    <table>
        <tr>
            <td>
                <select  id="anneeNouveauProjet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeNouveauProjet',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php  echo "/" . REPERTOIRE; ?>/statistiqueNouveauProjet/<?php echo $lang.'/'; ?>' + this.value)" >
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
        <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE;?>/chxStatistique/<?php echo $lang.'/'.IDSTATNOUVEAUPROJET; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
        </td>
    </tr>
</table>
    
<?php    
    $serie = "";
    foreach ($centrales as $key => $centrale) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? and  trashed != ? "
                    . "and EXTRACT(YEAR from dateprojet)<=?",array($centrale[1],TRUE,$_GET['anneeNouveauProjet']));
            $serie .= '{name: "' . $centrale[0] . '", data: [{name: "' . TXT_DETAILS .
                    '",y: ' . $nbProjet . ',drilldown: "' . $centrale[0].$_GET['anneeNouveauProjet'] . '"}]},';

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$serie3 = "";

foreach ($centrales as $key => $centrale) {
        $nbByYear = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                . "and extract(year from dateprojet)<=? and  trashed != ?", array($centrale[1],$_GET['anneeNouveauProjet'],TRUE));
        if (empty($nbByYear)) {$nbByYear = 0;}
        if($_GET['anneeNouveauProjet'] ==2013){
               $serie3.="{id: '" . $centrale[0] . $_GET['anneeNouveauProjet'] . "',name: '" .$centrale[0] .' '.  TXT_INFERIEUR2013 . "'" . ',data: [';
        }else{
            $serie3.="{id: '" . $centrale[0] . $_GET['anneeNouveauProjet'] . "',name: '" .$centrale[0] .' '.  $_GET['anneeNouveauProjet'] . "'" . ',data: [';
        }
        for ($i = 0; $i < count($statutProjets); $i++) {
            $nbByYearByMonth = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet WHERE idprojet_projet = idprojet AND idcentrale_centrale = ? "
                    . "AND  extract(year from dateprojet)<=? and idstatutprojet_statutprojet=? and  trashed != ?", array( $centrale[1],$_GET['anneeNouveauProjet'], $statutProjets[$i]['idstatutprojet'],TRUE));
            if (empty($nbByYearByMonth)) {$nbByYearByMonth = 0;}
            $serie3.= '["' . $statutProjets[$i]['libellestatutprojet'] . '",' . $nbByYearByMonth . '],';
        }
        $serie3.=']},';
}

   
    $serie03 = substr($serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;
    if($_GET['anneeNouveauProjet']==2013){
        $title = TXT_PROJETDATESTATUTANNEE2013;
    }else{
        $title = TXT_PROJETDATESTATUTANNEE.' '.$_GET['anneeNouveauProjet'];
    }
    
    $xasisTitle = TXT_NOMBREOCCURRENCE;
    
    
} elseif (IDTYPEUSER == ADMINNATIONNAL &&  !isset($_GET['anneeNouveauProjet'])) {?>
    <table>
        <tr>
            <td>
                <select  id="anneeNouveauProjet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeNouveauProjet',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php  echo "/" . REPERTOIRE; ?>/statistiqueNouveauProjet/<?php echo $lang.'/'; ?>' + this.value)" >
                                 <?php
                                    for ($i = 0; $i < count($arraydate); $i++) {
                                       echo '<option value="' . ($arraydate[$i]['anneedatecreation']) . '">' . $arraydate[$i]['anneedatecreation'] . '</option>';
                                    }
                                 ?>
                </select>
            </td>
        </tr>
    </table>
<?php    
    $serie = "";
    foreach ($centrales as $key => $centrale) {            
            $nbProjet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? and  trashed != ?",array($centrale[1],TRUE));
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
        $nbByYear = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                . "and extract(year from dateprojet)<=? and  trashed != ?", array($centrale[1],$year[0],TRUE));
        if (empty($nbByYear)) {$nbByYear = 0;}
        if($year[0]==2013){
            $serie02 .="{name: '" .TXT_INFERIEUR2013 ."', y: " . $nbByYear . " , drilldown: '" . $centrale[0] . $year[0] . "'},";
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
        if (empty($nbByYear)) {$nbByYear = 0;}
        if($year[0]=='2013'){
            $nbByYear = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                    . "and extract(year from dateprojet)<=2013 and  trashed != ?", array($centrale[1],TRUE));            
            $serie3.="{id: '" . $centrale[0] . $year[0]."',name: '" .$centrale[0] . $year[0]. "'" . ',data: [';
        }else{
            $nbByYear = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                    . "and extract(year from dateprojet)<=? and  trashed != ?", array($centrale[1],$year[0],TRUE));            
            $serie3.="{id: '" . $centrale[0] . $year[0] . "',name: '" .$centrale[0] .' '. $year[0] . "'" . ',data: [';
        }
        for ($i = 0; $i < count($statutProjets); $i++) {
            $nbByYearByStatut = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet WHERE idprojet_projet = idprojet  AND idcentrale_centrale = ? AND  extract(year from dateprojet)<=? "
                    . "and idstatutprojet_statutprojet=? and  trashed != ?", array( $centrale[1],$year[0], $statutProjets[$i]['idstatutprojet'],TRUE));
            if (empty($nbByYearByStatut)) {$nbByYearByStatut = 0;}
            $serie3.= '["' . $statutProjets[$i]['libellestatutprojet'] . '",' . $nbByYearByStatut . '],';
        }
        $serie3.=']},';
    }
}

    $serie03 = substr($serie2 . $serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;
    $title = TXT_PROJETDATESTATUT;
    $xasisTitle = TXT_NOMBREOCCURRENCE;
    
}if (IDTYPEUSER == ADMINLOCAL) {
    $serie = "";
    foreach ($years as $key => $year) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                    . "and extract(year from dateprojet)<=? and  trashed != ?", array(IDCENTRALEUSER,$year[0],TRUE));            
            if($nbProjet==0){$nbProjet=0;}
            if($year[0]=='2013'){
                   $serie .= '{name: "' .TXT_INFERIEUR2013.'", data: [{name: "' . TXT_DETAILS .'",y: ' . $nbProjet . ',drilldown: "' . $year[0] . '"}]},';
            }else{
                $serie .= '{name: "' . $year[0] . '", data: [{name: "' . TXT_DETAILS .'",y: ' . $nbProjet . ',drilldown: "' . $year[0] . '"}]},';
            }

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
$serie3 = '';    
foreach ($years as $key => $year) {
        $nbByYear = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                . "and extract(year from dateprojet)<=? and  trashed != ?", array(IDCENTRALEUSER,$year[0],TRUE));
        if (empty($nbByYear)) {$nbByYear = 0;}
            if($year[0]=='2013'){
                $serie3.="{id: '" . $year[0] . "',name: '" . TXT_INFERIEUR2013 . "'" . ',data: [';
            }else{
                $serie3.="{id: '" . $year[0] . "',name: '" . $year[0] . "'" . ',data: [';
            }
         for ($i = 0; $i < count($statutProjets); $i++) {
            $nbByYearByMonth = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet WHERE idprojet_projet = idprojet  AND idcentrale_centrale = ? "
                    . "AND  extract(year from dateprojet)<=? AND idstatutprojet_statutprojet=? and  trashed != ?", array(IDCENTRALEUSER,$year[0],  $statutProjets[$i]['idstatutprojet'],TRUE));
            if (empty($nbByYearByMonth)) {$nbByYearByMonth = 0;}
            $serie3.= '["' .  $statutProjets[$i]['libellestatutprojet'] . '",' . $nbByYearByMonth . '],';
        }$serie3.=']},';       
    } 
$serieY0 = str_replace("},]}", "}]}", $serie3);
$serieY1 =  str_replace("],]", "]]",$serieY0);
$serieY=  substr($serieY1, 0,-1);
$subtitle = TXT_CLICDETAIL;
$title = TXT_PROJETDATESTATUT; 
$xasisTitle = TXT_PROJETDATESTATUT;
}
include_once 'commun/scriptBar.php';
        