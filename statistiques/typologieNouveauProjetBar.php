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
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? AND masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$nbprojet = 0;
$yearMin = "2014";
$yearMax = $manager->getSingle("select max(EXTRACT(YEAR from dateprojet)) as year from projet");
$uneDateUneCentrale = 
        "   SELECT count(idprojet)
            FROM projet 
            LEFT JOIN concerne ON idprojet_projet = idprojet
            LEFT JOIN typeprojet ON idtypeprojet_typeprojet = idtypeprojet
            WHERE  idtypeprojet=? 
            AND idcentrale_centrale=?
            AND EXTRACT(YEAR from dateprojet)<=? 
            AND EXTRACT(YEAR from dateprojet)>=?  
            AND  trashed != ?";
$touscentraleunedate = 
         " SELECT count(idprojet) 
           FROM projet
           LEFT JOIN concerne ON idprojet_projet = idprojet
           LEFT JOIN typeprojet ON idtypeprojet_typeprojet = idtypeprojet
           WHERE EXTRACT(YEAR from dateprojet)<=?
           AND EXTRACT(YEAR from dateprojet)>=?   
           AND idtypeprojet=?"
. " AND idcentrale_centrale!=? AND  trashed != ?";
$toutesdateCentrale = 
        " SELECT count(idprojet) 
          FROM projet 
          LEFT JOIN concerne ON idprojet_projet = idprojet
          LEFT JOIN typeprojet ON idtypeprojet_typeprojet = idtypeprojet
          WHERE idtypeprojet=?
          AND idcentrale_centrale=? 
          AND  trashed != ?
          AND EXTRACT(YEAR from dateprojet)<=? 
          AND EXTRACT(YEAR from dateprojet)>=?";

$touscentraletoutesdate = 
        "   SELECT count(idprojet) 
            FROM projet 
            LEFT JOIN concerne ON idprojet_projet = idprojet
            LEFT JOIN typeprojet ON idtypeprojet_typeprojet = idtypeprojet
            WHERE idtypeprojet=? 
            AND idcentrale_centrale!=? 
            AND  trashed != ? 
            AND EXTRACT(YEAR from dateprojet)<=? AND EXTRACT(YEAR from dateprojet)>=? ";
        ?>


     
<div  method="post" action="<?php echo '/' . REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDSTATTYPOLOGIENOUVEAUPROJET; ?>" id='filtreDuAu' name='filtreDuAu' data-dojo-type="dijit/form/Form"  >
     <script type="dojo/on" data-dojo-event="submit">
        var dateDebut =parseInt(dijit.byId("anneeDu").value);
        var dateFin =parseInt(dijit.byId("anneeAu").value);
        if (isNaN(dateDebut)) {
            alert('Le format du champ "Date du:" est incorrecte');
            return false;  
            exit();
        }else if(dateDebut<1980 && dateDebut>2099){
            alert("L'année saisie n'est pas dans la plage autorisée ");
            return false;  
            exit();        
        }else if (this.validate()){
            return true;
        }else{
            alert("<?php echo "Valeur non autorisé!"; ?>");
            return false;
        }
    </script>
         
    <?php
    if (isset($_POST['anneeDu'])) {
        $anneeDepart = $_POST['anneeDu'];
    } else {
        $anneeDepart = $yearMin;
    }
    if (isset($_POST['anneeAu'])) {
        $anneeFin = $_POST['anneeAu'];
    } else {
        $anneeFin = $yearMax;
    }

    $annee = anneeStatistique($anneeDepart, $anneeFin);
    ?>
    <table class="filterDate">    
        <tr>
            <td valign="middle" style="text-align: left;padding-left:5px;;width:65px"><?php echo "Du" . ':  ' ?></td>
            <td><input id="anneeDu" style="width:135px;height:25px;padding-left: 5px;margin-right: 18px;"  type="text" name="anneeDu"   data-dojo-type="dijit/form/NumberTextBox" 
                       data-dojo-props="constraints:{min:1980,max:2099,places:0},  invalidMessage:'<?php echo TXT_INT; ?>',placeHolder: '<?php echo "Année de début"; ?>'" value="<?php echo $anneeDepart; ?>"

            </td>
            <td valign="middle" style="text-align: left;padding-left:5px;;width:65px"><?php echo "Au" . ':  ' ?></td>
            <td>
                <input id="anneeAu" style="width:135px;height:25px;padding-left: 5px;margin-right: 18px;"  type="text" name="anneeAu"   data-dojo-type="dijit/form/NumberTextBox"
                       data-dojo-props="constraints:{min:1980,max:2099,places:0}, invalidMessage:'<?php echo TXT_INT; ?>',placeHolder: '<?php echo "Année de fin"; ?>'" value="<?php echo $anneeFin; ?>" />
            </td>
            <td>
                <button data-dojo-type="dijit/form/Button" type="submit" style="padding-left: 22px" ><?php echo TXT_ENVOYER; ?></button>
            </td>            
        </tr>       
    </table>
<?php if (IDTYPEUSER == ADMINNATIONNAL) {
    
    $nbprojetAcademique = $manager->getSinglebyArray($touscentraletoutesdate, array(ACADEMIC,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($touscentraletoutesdate,array(ACADEMICPARTENARIAT,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $nbprojetindustriel = $manager->getSinglebyArray($touscentraletoutesdate,array(INDUSTRIEL,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $formation = $manager->getSinglebyArray($touscentraletoutesdate,array(FORMATION,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $service = $manager->getSinglebyArray($touscentraletoutesdate,array(SERVICE,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    $maintenance = $manager->getSinglebyArray($touscentraletoutesdate,array(MAINTENANCE,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    
    $nbprojetNonDefini = $manager->getSinglebyArray($touscentraletoutesdate,array(1,IDCENTRALEAUTRE,TRUE,$anneeFin,$anneeDepart));
    
    $serieX = "";
    $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademique . ',drilldown: "' . "academic" . '"}]},';
    $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat' . '"}]},';
    $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel' . '"}]},';
    $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation' . '"}]},';
    $serieX .= '{name: "' . TXT_SERVICE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $service . ',drilldown: "' . 'service' . '"}]},';
    $serieX .= '{name: "' . TXT_MAINTENANCE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $maintenance . ',drilldown: "' . 'maintenance' . '"}]},';
    
    
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
    foreach ($annee as $key => $year) {        
        $nbprojetAcademique0 = $manager->getSinglebyArray($touscentraleunedate, array($year,$anneeDepart,ACADEMIC,IDCENTRALEAUTRE, TRUE));
        $nbprojetAcademiquePartenariat0 = $manager->getSinglebyArray($touscentraleunedate, array($year,$anneeDepart,ACADEMICPARTENARIAT,IDCENTRALEAUTRE, TRUE));
        $nbprojetIndustriel0 = $manager->getSinglebyArray($touscentraleunedate, array($year,$anneeDepart,INDUSTRIEL,IDCENTRALEAUTRE, TRUE));
        $nbprojetFormation0 = $manager->getSinglebyArray($touscentraleunedate, array($year,$anneeDepart,FORMATION,IDCENTRALEAUTRE, TRUE));
        $nbprojetNondefini0 = $manager->getSinglebyArray($touscentraleunedate, array($year,$anneeDepart,1,IDCENTRALEAUTRE, TRUE));
        
         if($year==2013){
            $serieAcademique .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetAcademique0 . " , drilldown: '" . 'academic' . $year . "'},";
            $serieAcademiquePartenariat .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetAcademiquePartenariat0 . " , drilldown: '" . 'academicPartenariat' . $year . "'},";
            $serieIndustriel .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetIndustriel0 . " , drilldown: '" . 'industriel' . $year . "'},";
            $serieFormation .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetFormation0 . " , drilldown: '" . 'formation' . $year . "'},";
            $serieNonDefini .="{name: '" . 'Inférieur ou égale à 2013' . "', y: " . $nbprojetNondefini0 . " , drilldown: '" . 'undefined' . $year . "'},";
         }else{
            $serieAcademique .="{name: '" . $year . "', y: " . $nbprojetAcademique0 . " , drilldown: '" . 'academic' . $year . "'},";
            $serieAcademiquePartenariat .="{name: '" . $year . "', y: " . $nbprojetAcademiquePartenariat0 . " , drilldown: '" . 'academicPartenariat' . $year . "'},";
            $serieIndustriel .="{name: '" . $year . "', y: " . $nbprojetIndustriel0 . " , drilldown: '" . 'industriel' . $year . "'},";
            $serieFormation .="{name: '" . $year . "', y: " . $nbprojetFormation0 . " , drilldown: '" . 'formation' . $year . "'},";
            $serieNonDefini .="{name: '" . $year . "', y: " . $nbprojetNondefini0 . " , drilldown: '" . 'undefined' . $year . "'},";
         }
    }
    $serieAcademique .= "]},";
    $serieAcademiquePartenariat.= "]},";
    $serieIndustriel.= "]},";
    $serieFormation.= "]},";
    $serieNonDefini.= "]},";


    foreach ($annee as $key => $year) {
        $serieAcademique .= "{id: '" . 'academic' . $year . "',name: '" . ucfirst(TXT_ACADEMIQUE).' '. $year . "',data: [";
        $serieAcademiquePartenariat.= "{id: '" . 'academicPartenariat' . $year . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
        $serieIndustriel.= "{id: '" . 'industriel' . $year . "',name: '" . TXT_INDUSTRIEL.' '. $year . "',data: [";
        $serieFormation.= "{id: '" . 'formation' . $year . "',name: '" . TXT_FORMATION.' '. $year . "',data: [";
        $serieNonDefini.= "{id: '" . 'undefined' . $year . "',name: '" . "Non défini".' '. $year . "',data: [";
        
        foreach ($centrales as $key => $centrale) {
            $nbprojetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC, $centrale[1],$year,$anneeDepart, TRUE));
            $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT, $centrale[1],$year,$anneeDepart, TRUE));
            $nbprojetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array(INDUSTRIEL, $centrale[1],$year,$anneeDepart, TRUE));
            $nbprojetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION, $centrale[1],$year,$anneeDepart, TRUE));
            $nbprojetNonDefini = $manager->getSinglebyArray($uneDateUneCentrale, array(1, $centrale[1],$year,$anneeDepart, TRUE));
            
            $serieAcademique            .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetAcademique            . " , drilldown: '" . 'academic'            . $centrale[0] . $year . "'},";
            $serieAcademiquePartenariat .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $centrale[0] . $year . "'},";
            $serieIndustriel            .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetIndustriel            . " , drilldown: '" . 'industriel'          . $centrale[0] . $year . "'},";
            $serieFormation             .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetFormation             . " , drilldown: '" . 'formation'           . $centrale[0] . $year . "'},";           
            $serieNonDefini             .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetNonDefini             . " , drilldown: '" . 'undefined'           . $centrale[0] . $year . "'},";
            
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
    
    $subtitle = TXT_CLICDETAIL;
    $xasisTitle = "";
}
if (IDTYPEUSER == ADMINLOCAL) {
    $nbprojetAcademique = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMIC,IDCENTRALEUSER, TRUE,$anneeFin,$anneeDepart));
    $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMICPARTENARIAT,IDCENTRALEUSER, TRUE,$anneeFin,$anneeDepart));
    $nbprojetindustriel = $manager->getSinglebyArray($toutesdateCentrale, array(INDUSTRIEL,IDCENTRALEUSER, TRUE,$anneeFin,$anneeDepart));
    $formation = $manager->getSinglebyArray($toutesdateCentrale, array(FORMATION,IDCENTRALEUSER, TRUE,$anneeFin,$anneeDepart));
    $nbprojetnondefini = $manager->getSinglebyArray($toutesdateCentrale, array(1,IDCENTRALEUSER, TRUE,$anneeFin,$anneeDepart));
    $service = $manager->getSinglebyArray($toutesdateCentrale,array(SERVICE,IDCENTRALEUSER,TRUE,$anneeFin,$anneeDepart));
    $maintenance = $manager->getSinglebyArray($toutesdateCentrale,array(MAINTENANCE,IDCENTRALEUSER,TRUE,$anneeFin,$anneeDepart));
    
    $serieX = "";
    $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademique . ',drilldown: "' . "academic" . '"}]},';
    $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat' . '"}]},';
    $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel' . '"}]},';
    $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation' . '"}]},';
    $serieX .= '{name: "' . TXT_SERVICE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'service' . '"}]},';
    $serieX .= '{name: "' . TXT_MAINTENANCE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $service . ',drilldown: "' . 'maintenance' . '"}]},';
    
    $serieX .= '{name: "' . "Non défini" . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetnondefini . ',drilldown: "' . 'undefined' . '"}]}';
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
    $serieAcademique = "{id: '" . 'academic' . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
    $serieAcademiquePartenariat = "{id: '" . 'academicPartenariat' . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
    $serieIndustriel = "{id: '" . 'industriel' . "',name: '" . TXT_INDUSTRIEL . "',data: [";
    $serieFormation = "{id: '" . 'formation' . "',name: '" . TXT_FORMATION . "',data: [";
    $serieNonDefini = "{id: '" . 'undefined' . "',name: '" . 'Non défini' . "',data: [";
    foreach ($annee as $key => $year) {
        $nbprojetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC,IDCENTRALEUSER,$year,$anneeDepart, TRUE));
        $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT,IDCENTRALEUSER,$year,$anneeDepart, TRUE));
        $nbprojetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array(INDUSTRIEL,IDCENTRALEUSER,$anneeDepart,$year, TRUE));
        $nbprojetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION,IDCENTRALEUSER,$year,$anneeDepart, TRUE));
        $nbprojetnondefini = $manager->getSinglebyArray($uneDateUneCentrale, array(1,IDCENTRALEUSER,$year,$anneeDepart, TRUE));
        
        if($year==2013){
            $serieAcademique .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $year . "'},";
            $serieAcademiquePartenariat .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $year . "'},";
            $serieIndustriel .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $year . "'},";
            $serieFormation .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $year . "'},";
            $serieNonDefini .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbprojetnondefini . " , drilldown: '" . 'undefined' . $year . "'},";
        }else{
            $serieAcademique .="{name: '" . $year . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $year . "'},";
            $serieAcademiquePartenariat .="{name: '" . $year . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $year . "'},";
            $serieIndustriel .="{name: '" . $year . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $year . "'},";
            $serieFormation .="{name: '" . $year . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $year . "'},";
            $serieNonDefini .="{name: '" . $year . "', y: " . $nbprojetnondefini . " , drilldown: '" . 'undefined' . $year . "'},";
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
    $subtitle = TXT_CLICDETAIL;
    $xasisTitle = "";
    
}
 
if ($lang == 'fr') {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart;
        } else {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart . " à " . $anneeFin;
        }
    } else {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart;
        } else {
            $title = TXT_TYPOLOGIENOUVEAUPROJET . ' ' . $anneeDepart . " to " . $anneeFin;
        }
    }
include_once 'commun/scriptBar.php';
?>
</div>