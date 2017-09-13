<?php

include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter();
unset($_SESSION['anneeprojettypeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$string0 = '';
$string1 = '';
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=?  and masquecentrale!=TRUE  order by idcentrale asc", IDAUTRECENTRALE);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$nbprojet = 0;

$uneDateUneCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=?"
        . "AND EXTRACT(YEAR from dateprojet)<=?  AND idstatutprojet_statutprojet=?";

$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? "
        . "AND idstatutprojet_statutprojet=?";
$toutesdateCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?  "
        . "AND idstatutprojet_statutprojet=?  and idcentrale_centrale=?";
$touscentraletoutesdate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?  "
        . "AND idstatutprojet_statutprojet=?";

if (IDTYPEUSER == ADMINNATIONNAL &&  isset($_GET['anneeTypoProjetEncours'])) {?>
<table>
    <tr>
        <td>
            <select  id="anneeTypoProjetEncours" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeTypoProjetEncours',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                     style="width: 250px;margin-left:35px" 
                     onchange="window.location.replace('<?php  echo "/" . REPERTOIRE; ?>/typoRunningProjet/<?php echo $lang.'/'; ?>' + this.value)" >
                             <?php
                                for ($i = 0; $i < count($years); $i++) {
                                   echo '<option value="' . ($years[$i]['year']) . '">' . $years[$i]['year'] . '</option>';
                                }
                             ?>
            </select>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE;?>/chxStatistique/<?php echo $lang.'/'.IDSTATTYPOLOGIEPROJETENCOURS; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
        </td>
    </tr>
</table>
<?php 
 $serieX = "";
    $nbprojetacademique = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
                . "and EXTRACT(YEAR from dateprojet)<=?  AND idstatutprojet_statutprojet=?", array(ACADEMIC, $_GET['anneeTypoProjetEncours'],ENCOURSREALISATION));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
            . "and EXTRACT(YEAR from dateprojet)<=? AND idstatutprojet_statutprojet=?", array(ACADEMICPARTENARIAT, $_GET['anneeTypoProjetEncours'],ENCOURSREALISATION));
    $nbprojetindustriel = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
            . "and EXTRACT(YEAR from dateprojet)<=? AND idstatutprojet_statutprojet=?", array(INDUSTRIEL, $_GET['anneeTypoProjetEncours'],ENCOURSREALISATION));
    $formation = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
            . "and EXTRACT(YEAR from dateprojet)<=? AND idstatutprojet_statutprojet=?", array(FORMATION, $_GET['anneeTypoProjetEncours'],ENCOURSREALISATION));
      
    $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetacademique . ',drilldown: "' . "academic".$_GET['anneeTypoProjetEncours'] . '"}]},';
    $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat'.$_GET['anneeTypoProjetEncours'] . '"}]},';
    $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel'.$_GET['anneeTypoProjetEncours'] . '"}]},';
    $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation'.$_GET['anneeTypoProjetEncours'] . '"}]}';

    $serieAcademique = "{id: '" . 'academic'.$_GET['anneeTypoProjetEncours'] . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
    $serieAcademiquePartenariat = "{id: '" . 'academicPartenariat'.$_GET['anneeTypoProjetEncours'] . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
    $serieIndustriel = "{id: '" . 'industriel'.$_GET['anneeTypoProjetEncours'] . "',name: '" . TXT_INDUSTRIEL . "',data: [";
    $serieFormation = "{id: '" . 'formation'.$_GET['anneeTypoProjetEncours'] . "',name: '" . TXT_FORMATION . "',data: [";
    
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypoProjetEncours'], ACADEMIC,ENCOURSREALISATION));
    $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypoProjetEncours'], ACADEMICPARTENARIAT,ENCOURSREALISATION));
    $nbprojetIndustriel = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypoProjetEncours'], INDUSTRIEL,ENCOURSREALISATION));
    $nbprojetFormation = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypoProjetEncours'], FORMATION,ENCOURSREALISATION));
    
    $serieAcademique .="{name: '" . $_GET['anneeTypoProjetEncours'] . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $_GET['anneeTypoProjetEncours'] . "'},";
    $serieAcademiquePartenariat .="{name: '" . $_GET['anneeTypoProjetEncours'] . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $_GET['anneeTypoProjetEncours'] . "'},";
    $serieIndustriel .="{name: '" . $_GET['anneeTypoProjetEncours'] . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $_GET['anneeTypoProjetEncours'] . "'},";
    $serieFormation .="{name: '" . $_GET['anneeTypoProjetEncours'] . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $_GET['anneeTypoProjetEncours'] . "'},";
    
    $serieAcademique .= "]},";
    $serieAcademiquePartenariat.= "]},";
    $serieIndustriel.= "]},";
    $serieFormation.= "]},";
    
    $serieAcademique .= "{id: '" . 'academic' . $_GET['anneeTypoProjetEncours'] . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
    $serieAcademiquePartenariat.= "{id: '" . 'academicPartenariat' . $_GET['anneeTypoProjetEncours'] . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
    $serieIndustriel.= "{id: '" . 'industriel' . $_GET['anneeTypoProjetEncours'] . "',name: '" . TXT_INDUSTRIEL . "',data: [";
    $serieFormation.= "{id: '" . 'formation' . $_GET['anneeTypoProjetEncours'] . "',name: '" . TXT_FORMATION . "',data: [";
    foreach ($centrales as $key => $centrale) {
            $nbprojetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC, $centrale[1],$_GET['anneeTypoProjetEncours'],ENCOURSREALISATION));
            $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT, $centrale[1],$_GET['anneeTypoProjetEncours'],ENCOURSREALISATION));
            $nbprojetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array( INDUSTRIEL, $centrale[1],$_GET['anneeTypoProjetEncours'],ENCOURSREALISATION));
            $nbprojetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION, $centrale[1],$_GET['anneeTypoProjetEncours'],ENCOURSREALISATION));         
            $serieAcademique .="{name: '" . $centrale[0] . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $centrale[0] . $_GET['anneeTypoProjetEncours'] . "'},";
            $serieAcademiquePartenariat .="{name: '" . $centrale[0] . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $centrale[0] . $_GET['anneeTypoProjetEncours'] . "'},";
            $serieIndustriel .="{name: '" . $centrale[0] . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $centrale[0] . $_GET['anneeTypoProjetEncours'] . "'},";
            $serieFormation .="{name: '" . $centrale[0] . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $centrale[0] . $_GET['anneeTypoProjetEncours'] . "'},";
    }
    $serieAcademique .="]},";
    $serieAcademiquePartenariat .="]},";
    $serieIndustriel .="]},";
    $serieFormation .="]},";
    
    $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
    if($_GET['anneeTypoProjetEncours']==2013){
        $title = TXT_TYPOLOGIEPROJETENCOURSPOURANNEE. TXT_INFERIEUR2013;
    }else{
        $title = TXT_TYPOLOGIEPROJETENCOURSPOURANNEE. $_GET['anneeTypoProjetEncours'];
    }
    $subtitle = TXT_CLICDETAIL;
    $xasisTitle = "";
    
}elseif (IDTYPEUSER == ADMINNATIONNAL &&  !isset($_GET['anneeTypoProjetEncours'])) {?>
<table>
    <tr>
        <td>
            <select  id="anneeTypoProjetEncours" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeTypoProjetEncours',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                     style="width: 250px;margin-left:35px" 
                     onchange="window.location.replace('<?php  echo "/" . REPERTOIRE; ?>/typoRunningProjet/<?php echo $lang.'/'; ?>' + this.value)" >
                             <?php
                                for ($i = 0; $i < count($years); $i++) {
                                   echo '<option value="' . ($years[$i]['year']) . '">' . $years[$i]['year'] . '</option>';
                                }
                             ?>
            </select>
        </td>
    </tr>
</table>
<?php
    $nbprojetacademique = $manager->getSinglebyArray($touscentraletoutesdate, array(ACADEMIC,ENCOURSREALISATION));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraletoutesdate, array(ACADEMICPARTENARIAT,ENCOURSREALISATION));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraletoutesdate, array(INDUSTRIEL,ENCOURSREALISATION));
    $formation = $manager->getSinglebyArray($touscentraletoutesdate, array(FORMATION,ENCOURSREALISATION));
    
    $serieX = "";
    $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetacademique . ',drilldown: "' . "academic" . '"}]},';
    $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat' . '"}]},';
    $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel' . '"}]},';
    $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation' . '"}]}';
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
    $serieAcademique = "{id: '" . 'academic' . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
    $serieAcademiquePartenariat = "{id: '" . 'academicPartenariat' . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
    $serieIndustriel = "{id: '" . 'industriel' . "',name: '" . TXT_INDUSTRIEL . "',data: [";
    $serieFormation = "{id: '" . 'formation' . "',name: '" . TXT_FORMATION . "',data: [";
    $nbprojetAcademique=0;
    $nbprojetAcademiquePartenariat=0;
    $nbprojetIndustriel=0;
    $nbprojetFormation=0;
    foreach ($years as $key => $year) {
        $nbprojetAcademique += $manager->getSinglebyArray($touscentraleunedate, array($year[0], ACADEMIC,ENCOURSREALISATION));
        $nbprojetAcademiquePartenariat += $manager->getSinglebyArray($touscentraleunedate, array($year[0], ACADEMICPARTENARIAT,ENCOURSREALISATION));
        $nbprojetIndustriel += $manager->getSinglebyArray($touscentraleunedate, array($year[0], INDUSTRIEL,ENCOURSREALISATION));
        $nbprojetFormation += $manager->getSinglebyArray($touscentraleunedate, array($year[0], FORMATION,ENCOURSREALISATION));
         if($year[0]==2013){
             $serieAcademique .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $centrale[0] .$year[0] . "'},";
            $serieAcademiquePartenariat .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $centrale[0] . $year[0] . "'},";
            $serieIndustriel .="{name: '" . TXT_INFERIEUR2013 . "', y: ". $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $centrale[0] . $year[0] . "'},";
            $serieFormation .="{name: '" . TXT_INFERIEUR2013 . "', y: ". $nbprojetFormation . " , drilldown: '" . 'formation' . $centrale[0] . $year[0] . "'},";
         }else{
            $serieAcademique .="{name: '" . $year[0] . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $year[0] . "'},";
            $serieAcademiquePartenariat .="{name: '" . $year[0] . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $year[0] . "'},";
            $serieIndustriel .="{name: '" . $year[0] . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $year[0] . "'},";
            $serieFormation .="{name: '" . $year[0] . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $year[0] . "'},";
         }
    }
    $serieAcademique .= "]},";
    $serieAcademiquePartenariat.= "]},";
    $serieIndustriel.= "]},";
    $serieFormation.= "]},";


    foreach ($years as $key => $year) {
        $serieAcademique .= "{id: '" . 'academic' . $year[0] . "',name: '" . ucfirst(TXT_ACADEMIQUE).' '. $year[0] . "',data: [";
        $serieAcademiquePartenariat.= "{id: '" . 'academicPartenariat' . $year[0] . "',name: '" . TXT_ACADEMICPARTENARIAT.' '. $year[0] . "',data: [";
        $serieIndustriel.= "{id: '" . 'industriel' . $year[0] . "',name: '" . TXT_INDUSTRIEL.' '. $year[0] . "',data: [";
        $serieFormation.= "{id: '" . 'formation' . $year[0] . "',name: '" . TXT_FORMATION.' '. $year[0] . "',data: [";
        
        foreach ($centrales as $key => $centrale) {
            $nbprojetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC, $centrale[1],$year[0],ENCOURSREALISATION));
            $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT, $centrale[1],$year[0],ENCOURSREALISATION));
            $nbprojetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array(INDUSTRIEL, $centrale[1],$year[0],ENCOURSREALISATION));
            $nbprojetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION, $centrale[1],$year[0],ENCOURSREALISATION));
            if($year[0]==2013){
                $serieAcademique .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $centrale[0] . $year[0] . "'},";
                $serieAcademiquePartenariat .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $centrale[0] . $year[0] . "'},";
                $serieIndustriel .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $centrale[0] . $year[0] . "'},";
                $serieFormation .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $centrale[0] . $year[0] . "'},";
            }else{
                $serieAcademique .="{name: '" . $centrale[0] . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $centrale[0] . $year[0] . "'},";
                $serieAcademiquePartenariat .="{name: '" . $centrale[0] . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $centrale[0] . $year[0] . "'},";
                $serieIndustriel .="{name: '" . $centrale[0] . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $centrale[0] . $year[0] . "'},";
                $serieFormation .="{name: '" . $centrale[0] . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $centrale[0] . $year[0] . "'},";
            }
        }
        $serieAcademique .="]},";
        $serieAcademiquePartenariat .="]},";
        $serieIndustriel .="]},";
        $serieFormation .="]},";
    }
//---------------------------------------------------------------------------------------   -----------------------------------------------------------------------------------------------------------------------------------        
    $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
    $title = TXT_TYPOLOGIEPROJETENCOURS;
    $subtitle = TXT_CLICDETAIL;
    $xasisTitle = "";
}
if (IDTYPEUSER == ADMINLOCAL) {    
    $nbprojetAcademique = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMIC,ENCOURSREALISATION,IDCENTRALEUSER));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMICPARTENARIAT,ENCOURSREALISATION,IDCENTRALEUSER));
    $nbprojetindustriel = $manager->getSinglebyArray($toutesdateCentrale, array(INDUSTRIEL,ENCOURSREALISATION,IDCENTRALEUSER));
    $formation = $manager->getSinglebyArray($toutesdateCentrale, array(FORMATION,ENCOURSREALISATION,IDCENTRALEUSER,));
    $serieX = "";
    $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademique . ',drilldown: "' . "academic" . '"}]},';
    $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat' . '"}]},';
    $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel' . '"}]},';
    $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation' . '"}]}';
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
    $serieAcademique = "{id: '" . 'academic' . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
    $serieAcademiquePartenariat = "{id: '" . 'academicPartenariat' . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
    $serieIndustriel = "{id: '" . 'industriel' . "',name: '" . TXT_INDUSTRIEL . "',data: [";
    $serieFormation = "{id: '" . 'formation' . "',name: '" . TXT_FORMATION . "',data: [";
    foreach ($years as $key => $year) {
        $nbprojetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC,IDCENTRALEUSER,$year[0],ENCOURSREALISATION));
        $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT,IDCENTRALEUSER,$year[0],ENCOURSREALISATION));
        $nbprojetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array(INDUSTRIEL,IDCENTRALEUSER,$year[0],ENCOURSREALISATION));
        $nbprojetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION,IDCENTRALEUSER,$year[0],ENCOURSREALISATION));
        if($year[0]==2013){
            $serieAcademique .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $year[0] . "'},";
            $serieAcademiquePartenariat .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $year[0] . "'},";
            $serieIndustriel .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $year[0] . "'},";
            $serieFormation .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $year[0] . "'},";
        }else{
            $serieAcademique .="{name: '" . $year[0] . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $year[0] . "'},";
            $serieAcademiquePartenariat .="{name: '" . $year[0] . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $year[0] . "'},";
            $serieIndustriel .="{name: '" . $year[0] . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $year[0] . "'},";
            $serieFormation .="{name: '" . $year[0] . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $year[0] . "'},";
        }
    }
    $serieAcademique .= "]},";
    $serieAcademiquePartenariat.= "]},";
    $serieIndustriel.= "]},";
    $serieFormation.= "]},";

//---------------------------------------------------------------------------------------   -----------------------------------------------------------------------------------------------------------------------------------        
    $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
    $title = TXT_TYPOLOGIEPROJETENCOURS;
    $subtitle = TXT_CLICDETAIL;
    $xasisTitle = "";
    
}
include_once 'commun/scriptBar.php';
