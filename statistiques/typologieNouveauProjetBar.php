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
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$nbprojet = 0;

$uneDateUneCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=?"
. " AND EXTRACT(YEAR from dateprojet)<=? and  trashed != ?";
$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)<=? and idtypeprojet=?"
. " and idcentrale_centrale!=? and  trashed != ?";

$toutesdateCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?and idcentrale_centrale=? and  trashed != ?";
$tousdateunecentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=? and  trashed != ?";

$touscentraletoutesdate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
        . " and idcentrale_centrale!=? and  trashed != ?";

if (IDTYPEUSER == ADMINNATIONNAL &&  isset($_GET['anneeTypo'])) {?>
<table>
    <tr>
        <td>
            <select  id="anneeTypo" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeTypo',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                     style="width: 250px;margin-left:35px" 
                     onchange="window.location.replace('<?php  echo "/" . REPERTOIRE; ?>/typoNewProjet/<?php echo $lang.'/'; ?>' + this.value)" >
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
        <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE;?>/chxStatistique/<?php echo $lang.'/'.IDSTATTYPOLOGIENOUVEAUPROJET; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
        </td>
    </tr>
</table>
<?php 
 $serieX = "";
    $nbprojetacademique = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
                . "and EXTRACT(YEAR from dateprojet)<=?  and idcentrale_centrale!=? and trashed!=? ", array(ACADEMIC, $_GET['anneeTypo'],IDCENTRALEAUTRE,TRUE));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
            . "and EXTRACT(YEAR from dateprojet)<=? and trashed !=? and idcentrale_centrale!=?", array(ACADEMICPARTENARIAT, $_GET['anneeTypo'],TRUE,IDCENTRALEAUTRE));
    $nbprojetindustriel = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
            . "and EXTRACT(YEAR from dateprojet)<=? and trashed!=?and idcentrale_centrale!=?", array(INDUSTRIEL, $_GET['anneeTypo'],TRUE,IDCENTRALEAUTRE));
    $formation = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
            . "and EXTRACT(YEAR from dateprojet)<=? and trashed!=? and idcentrale_centrale!=?", array(FORMATION, $_GET['anneeTypo'],TRUE,IDCENTRALEAUTRE));     
    $nbprojetNonDefini = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
            . "and EXTRACT(YEAR from dateprojet)<=? and trashed!=? and idcentrale_centrale!=? ", array(1, $_GET['anneeTypo'],TRUE,IDCENTRALEAUTRE));
      
    $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetacademique . ',drilldown: "' . "academic".$_GET['anneeTypo'] . '"}]},';
    $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat'.$_GET['anneeTypo'] . '"}]},';
    $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel'.$_GET['anneeTypo'] . '"}]},';
    $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation'.$_GET['anneeTypo'] . '"}]},';
    $serieX .= '{name: "' . "Non défini" . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetNonDefini . ',drilldown: "' . 'undefined'.$_GET['anneeTypo'] . '"}]}';

    $serieAcademique = "{id: '" . 'academic'.$_GET['anneeTypo'] . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
    $serieAcademiquePartenariat = "{id: '" . 'academicPartenariat'.$_GET['anneeTypo'] . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
    $serieIndustriel = "{id: '" . 'industriel'.$_GET['anneeTypo'] . "',name: '" . TXT_INDUSTRIEL . "',data: [";
    $serieFormation = "{id: '" . 'formation'.$_GET['anneeTypo'] . "',name: '" . TXT_FORMATION . "',data: [";
    $serieNonDefini = "{id: '" . 'undefined'.$_GET['anneeTypo'] . "',name: '" . "Non défini" . "',data: [";
    
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'], ACADEMIC,IDCENTRALEAUTRE,TRUE));
    $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'], ACADEMICPARTENARIAT,IDCENTRALEAUTRE,TRUE));
    $nbprojetIndustriel = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'], INDUSTRIEL,IDCENTRALEAUTRE,TRUE));
    $nbprojetFormation = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'], FORMATION,IDCENTRALEAUTRE,TRUE));
    $nbprojetNondefini = $manager->getSinglebyArray($touscentraleunedate, array($_GET['anneeTypo'], 1,IDCENTRALEAUTRE,TRUE));
    
    $serieAcademique            .="{name: '" . $_GET['anneeTypo'] . "', y: " . $nbprojetAcademique            . " , drilldown: '" . 'academic'            . $_GET['anneeTypo'] . "'},";
    $serieAcademiquePartenariat .="{name: '" . $_GET['anneeTypo'] . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $_GET['anneeTypo'] . "'},";
    $serieIndustriel            .="{name: '" . $_GET['anneeTypo'] . "', y: " . $nbprojetIndustriel            . " , drilldown: '" . 'industriel'          . $_GET['anneeTypo'] . "'},";
    $serieFormation             .="{name: '" . $_GET['anneeTypo'] . "', y: " . $nbprojetFormation             . " , drilldown: '" . 'formation'           . $_GET['anneeTypo'] . "'},";
    $serieNonDefini             .="{name: '" . $_GET['anneeTypo'] . "', y: " . $nbprojetNondefini             . " , drilldown: '" . 'undefined'           . $_GET['anneeTypo'] . "'},";
    
    $serieAcademique .= "]},";
    $serieAcademiquePartenariat.= "]},";
    $serieIndustriel.= "]},";
    $serieFormation.= "]},";
    $serieNonDefini.= "]},";

    $serieAcademique            .= "{id: '" . 'academic'            . $_GET['anneeTypo'] . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
    $serieAcademiquePartenariat .= "{id: '" . 'academicPartenariat' . $_GET['anneeTypo'] . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
    $serieIndustriel            .= "{id: '" . 'industriel'          . $_GET['anneeTypo'] . "',name: '" . TXT_INDUSTRIEL          . "',data: [";
    $serieFormation             .= "{id: '" . 'formation'           . $_GET['anneeTypo'] . "',name: '" . TXT_FORMATION           . "',data: [";
    $serieNonDefini             .= "{id: '" . 'undefined'           . $_GET['anneeTypo'] . "',name: '" . "Non défini"            . "',data: [";
    foreach ($centrales as $key => $centrale) {
        $nbprojetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC, $centrale[1],$_GET['anneeTypo'], TRUE));
        $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT, $centrale[1],$_GET['anneeTypo'], TRUE));
        $nbprojetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array( INDUSTRIEL, $centrale[1],$_GET['anneeTypo'], TRUE));
        $nbprojetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION, $centrale[1],$_GET['anneeTypo'], TRUE));
        $nbprojetNonDefini = $manager->getSinglebyArray($uneDateUneCentrale, array(1, $centrale[1],$_GET['anneeTypo'], TRUE));
        
        $serieAcademique .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $centrale[0] . $_GET['anneeTypo'] . "'},";
        $serieAcademiquePartenariat .="{name: '"     . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $centrale[0] . $_GET['anneeTypo'] . "'},";
        $serieIndustriel .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $centrale[0] . $_GET['anneeTypo'] . "'},";
        $serieFormation .="{name: '" . $centrale[0]  . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $centrale[0] . $_GET['anneeTypo'] . "'},";
        $serieNonDefini .="{name: '" . $centrale[0]  . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetNonDefini . " , drilldown: '" . 'undefined' . $centrale[0] . $_GET['anneeTypo'] . "'},";
    }
    $serieAcademique .="]},";
    $serieAcademiquePartenariat .="]},";
    $serieIndustriel .="]},";
    $serieFormation .="]},";
    $serieNonDefini .="]},";
    
    $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation . $serieNonDefini;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
    if($_GET['anneeTypo'] == 2013){
        $title = TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE ." inférieur ou égale à ". $_GET['anneeTypo'];
    }else{
        $title = TXT_TYPOLOGIENOUVEAUPROJETPOURANNEE . $_GET['anneeTypo'];
    }    
    $subtitle = TXT_CLICDETAIL;
    $xasisTitle = "";
    
}elseif (IDTYPEUSER == ADMINNATIONNAL &&  !isset($_GET['anneeTypo'])) {?>
<table>
    <tr>
        <td>
            <select  id="anneeTypo" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeTypo',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                     style="width: 250px;margin-left:35px" 
                     onchange="window.location.replace('<?php  echo "/" . REPERTOIRE; ?>/typoNewProjet/<?php echo $lang.'/'; ?>' + this.value)" >
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
   
$nbprojetAcademique = $manager->getSinglebyArray($touscentraletoutesdate, array(ACADEMIC,IDCENTRALEAUTRE,TRUE));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraletoutesdate,array(ACADEMICPARTENARIAT,IDCENTRALEAUTRE,TRUE));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraletoutesdate,array(INDUSTRIEL,IDCENTRALEAUTRE,TRUE));
    $formation = $manager->getSinglebyArray($touscentraletoutesdate,array(FORMATION,IDCENTRALEAUTRE,TRUE));
    $nbprojetNonDefini = $manager->getSinglebyArray($touscentraletoutesdate,array(1,IDCENTRALEAUTRE,TRUE));
    
    $serieX = "";
    $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademique . ',drilldown: "' . "academic" . '"}]},';
    $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat' . '"}]},';
    $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel' . '"}]},';
    $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation' . '"}]},';
    $serieX .= '{name: "' . "Non défini" . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetNonDefini . ',drilldown: "' . 'undefined' . '"}]}';
    
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
    $serieAcademique = "{id: '" . 'academic' . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
    $serieAcademiquePartenariat = "{id: '" . 'academicPartenariat' . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
    $serieIndustriel = "{id: '" . 'industriel' . "',name: '" . TXT_INDUSTRIEL . "',data: [";
    $serieFormation = "{id: '" . 'formation' . "',name: '" . TXT_FORMATION . "',data: [";
    $serieNonDefini = "{id: '" . 'undefined' . "',name: '" . "Non défini" . "',data: [";
    $nbprojetAcademique0=0;
    $nbprojetAcademiquePartenariat0=0;
    $nbprojetIndustriel0=0;
    $nbprojetFormation0=0;
    $nbprojetNondefini0=0;
    foreach ($years as $key => $year) {        
        $nbprojetAcademique0 = $manager->getSinglebyArray($touscentraleunedate, array($year[0], ACADEMIC,IDCENTRALEAUTRE, TRUE));
        $nbprojetAcademiquePartenariat0 = $manager->getSinglebyArray($touscentraleunedate, array($year[0], ACADEMICPARTENARIAT,IDCENTRALEAUTRE, TRUE));
        $nbprojetIndustriel0 = $manager->getSinglebyArray($touscentraleunedate, array($year[0], INDUSTRIEL,IDCENTRALEAUTRE, TRUE));
        $nbprojetFormation0 = $manager->getSinglebyArray($touscentraleunedate, array($year[0], FORMATION,IDCENTRALEAUTRE, TRUE));
        $nbprojetNondefini0 = $manager->getSinglebyArray($touscentraleunedate, array($year[0],1,IDCENTRALEAUTRE, TRUE));
        
         if($year[0]==2013){
            $serieAcademique .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetAcademique0 . " , drilldown: '" . 'academic' . $year[0] . "'},";
            $serieAcademiquePartenariat .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetAcademiquePartenariat0 . " , drilldown: '" . 'academicPartenariat' . $year[0] . "'},";
            $serieIndustriel .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetIndustriel0 . " , drilldown: '" . 'industriel' . $year[0] . "'},";
            $serieFormation .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetFormation0 . " , drilldown: '" . 'formation' . $year[0] . "'},";
            $serieNonDefini .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetNondefini0 . " , drilldown: '" . 'undefined' . $year[0] . "'},";
         }else{
            $serieAcademique .="{name: '" . $year[0] . "', y: " . $nbprojetAcademique0 . " , drilldown: '" . 'academic' . $year[0] . "'},";
            $serieAcademiquePartenariat .="{name: '" . $year[0] . "', y: " . $nbprojetAcademiquePartenariat0 . " , drilldown: '" . 'academicPartenariat' . $year[0] . "'},";
            $serieIndustriel .="{name: '" . $year[0] . "', y: " . $nbprojetIndustriel0 . " , drilldown: '" . 'industriel' . $year[0] . "'},";
            $serieFormation .="{name: '" . $year[0] . "', y: " . $nbprojetFormation0 . " , drilldown: '" . 'formation' . $year[0] . "'},";
            $serieNonDefini .="{name: '" . $year[0] . "', y: " . $nbprojetNondefini0 . " , drilldown: '" . 'undefined' . $year[0] . "'},";
         }
    }
    $serieAcademique .= "]},";
    $serieAcademiquePartenariat.= "]},";
    $serieIndustriel.= "]},";
    $serieFormation.= "]},";
    $serieNonDefini.= "]},";


    foreach ($years as $key => $year) {
        $serieAcademique .= "{id: '" . 'academic' . $year[0] . "',name: '" . ucfirst(TXT_ACADEMIQUE).' '. $year[0] . "',data: [";
        $serieAcademiquePartenariat.= "{id: '" . 'academicPartenariat' . $year[0] . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
        $serieIndustriel.= "{id: '" . 'industriel' . $year[0] . "',name: '" . TXT_INDUSTRIEL.' '. $year[0] . "',data: [";
        $serieFormation.= "{id: '" . 'formation' . $year[0] . "',name: '" . TXT_FORMATION.' '. $year[0] . "',data: [";
        $serieNonDefini.= "{id: '" . 'undefined' . $year[0] . "',name: '" . "Non défini".' '. $year[0] . "',data: [";
        
        foreach ($centrales as $key => $centrale) {
            $nbprojetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC, $centrale[1],$year[0], TRUE));
            $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT, $centrale[1],$year[0], TRUE));
            $nbprojetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array(INDUSTRIEL, $centrale[1],$year[0], TRUE));
            $nbprojetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION, $centrale[1],$year[0], TRUE));
            $nbprojetNonDefini = $manager->getSinglebyArray($uneDateUneCentrale, array(1, $centrale[1],$year[0], TRUE));
            
            $serieAcademique            .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetAcademique            . " , drilldown: '" . 'academic'            . $centrale[0] . $year[0] . "'},";
            $serieAcademiquePartenariat .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $centrale[0] . $year[0] . "'},";
            $serieIndustriel            .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetIndustriel            . " , drilldown: '" . 'industriel'          . $centrale[0] . $year[0] . "'},";
            $serieFormation             .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetFormation             . " , drilldown: '" . 'formation'           . $centrale[0] . $year[0] . "'},";
            $serieNonDefini             .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetNonDefini             . " , drilldown: '" . 'undefined'           . $centrale[0] . $year[0] . "'},";
        }
        $serieAcademique .="]},";
        $serieAcademiquePartenariat .="]},";
        $serieIndustriel .="]},";
        $serieFormation .="]},";
        $serieNonDefini .="]},";
    }
//---------------------------------------------------------------------------------------   -----------------------------------------------------------------------------------------------------------------------------------        
    $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation . $serieNonDefini;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
    $title = TXT_TYPOLOGIENOUVEAUPROJET;
    $subtitle = TXT_CLICDETAIL;
    $xasisTitle = "";
}
if (IDTYPEUSER == ADMINLOCAL) {
    $nbprojetAcademique = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMIC,IDCENTRALEUSER, TRUE));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMICPARTENARIAT,IDCENTRALEUSER, TRUE));
    $nbprojetindustriel = $manager->getSinglebyArray($toutesdateCentrale, array(INDUSTRIEL,IDCENTRALEUSER, TRUE));
    $formation = $manager->getSinglebyArray($toutesdateCentrale, array(FORMATION,IDCENTRALEUSER, TRUE));
    $nbprojetnondefini = $manager->getSinglebyArray($toutesdateCentrale, array(1,IDCENTRALEUSER, TRUE));
    $serieX = "";
    $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademique . ',drilldown: "' . "academic" . '"}]},';
    $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat' . '"}]},';
    $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel' . '"}]},';
    $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation' . '"}]},';
    $serieX .= '{name: "' . "Non défini" . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetnondefini . ',drilldown: "' . 'undefined' . '"}]}';
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
    $serieAcademique = "{id: '" . 'academic' . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
    $serieAcademiquePartenariat = "{id: '" . 'academicPartenariat' . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
    $serieIndustriel = "{id: '" . 'industriel' . "',name: '" . TXT_INDUSTRIEL . "',data: [";
    $serieFormation = "{id: '" . 'formation' . "',name: '" . TXT_FORMATION . "',data: [";
    $serieNonDefini = "{id: '" . 'undefined' . "',name: '" . 'Non défini' . "',data: [";
    foreach ($years as $key => $year) {
        $nbprojetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC,IDCENTRALEUSER,$year[0], TRUE));
        $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT,IDCENTRALEUSER,$year[0], TRUE));
        $nbprojetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array(INDUSTRIEL,IDCENTRALEUSER,$year[0], TRUE));
        $nbprojetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION,IDCENTRALEUSER,$year[0], TRUE));
        $nbprojetnondefini = $manager->getSinglebyArray($uneDateUneCentrale, array(1,IDCENTRALEUSER,$year[0], TRUE));
        
        if($year[0]==2013){
            $serieAcademique .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $year[0] . "'},";
            $serieAcademiquePartenariat .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $year[0] . "'},";
            $serieIndustriel .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $year[0] . "'},";
            $serieFormation .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $year[0] . "'},";
            $serieNonDefini .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetnondefini . " , drilldown: '" . 'undefined' . $year[0] . "'},";
        }else{
            $serieAcademique .="{name: '" . $year[0] . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $year[0] . "'},";
            $serieAcademiquePartenariat .="{name: '" . $year[0] . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $year[0] . "'},";
            $serieIndustriel .="{name: '" . $year[0] . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $year[0] . "'},";
            $serieFormation .="{name: '" . $year[0] . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $year[0] . "'},";
            $serieNonDefini .="{name: '" . $year[0] . "', y: " . $nbprojetnondefini . " , drilldown: '" . 'undefined' . $year[0] . "'},";
        }        
    }
    $serieAcademique .= "]},";
    $serieAcademiquePartenariat.= "]},";
    $serieIndustriel.= "]},";
    $serieFormation.= "]},";
    $serieNonDefini.= "]},";

//---------------------------------------------------------------------------------------   -----------------------------------------------------------------------------------------------------------------------------------        
    $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation.$serieNonDefini;
    $serie = str_replace("},]", "}]", $serie0);
    $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
    $title = TXT_TYPOLOGIENOUVEAUPROJET;
    $subtitle = TXT_CLICDETAIL;
    $xasisTitle = "";
    
}
include_once 'commun/scriptBar.php';
