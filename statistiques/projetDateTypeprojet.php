<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojettypeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$string0 = '';
$string1 = '';
//$nbtotalprojet = $manager->getSingle("select count(idprojet_projet) from concerne");
$nbprojet = 0;
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$centraleplusdate="SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=?";
$touscentraleunedate="SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=?";
$tousdateunecentrale="SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=? ";
$touscentraletoutesdate="SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?";

if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1 && isset($_GET['centrale']) && $_GET['centrale']!=99) {//CHOIX CENTRALES+ANNEE
        $nbprojetAcademique = $manager->getSinglebyArray($centraleplusdate,array($_GET['anneeprojettypeprojet'],ACADEMIC,$_GET['centrale']));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($centraleplusdate,array($_GET['anneeprojettypeprojet'],ACADEMICPARTENARIAT,$_GET['centrale']));
	$nbprojetindustriel= $manager->getSinglebyArray($centraleplusdate,array($_GET['anneeprojettypeprojet'],INDUSTRIEL,$_GET['centrale']));
	$formation =$manager->getSinglebyArray($centraleplusdate,array($_GET['anneeprojettypeprojet'],FORMATION,$_GET['centrale']));
    }elseif (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1 && isset($_GET['centrale']) && $_GET['centrale']==99) {//CHOIX ANNEE + CENTRALE = TOUS
        $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],ACADEMIC));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],ACADEMICPARTENARIAT));
	$nbprojetindustriel= $manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],INDUSTRIEL));
	$formation =$manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],FORMATION));	
    }elseif (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] == 1 && isset($_GET['centrale']) &&  $_GET['centrale']!=99) {//CHOIX ANNEE=TOUS + CENTRALE
        $nbprojetAcademique = $manager->getSinglebyArray($tousdateunecentrale,array(ACADEMIC,$_GET['centrale']));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($tousdateunecentrale,array(ACADEMICPARTENARIAT,$_GET['centrale']));
	$nbprojetindustriel= $manager->getSinglebyArray($tousdateunecentrale,array(INDUSTRIEL,$_GET['centrale']));
	$formation =$manager->getSinglebyArray($tousdateunecentrale,array(FORMATION,$_GET['centrale']));
    }elseif(isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1 ) {//CHOIX ANNEE
        $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],ACADEMIC));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],ACADEMICPARTENARIAT));
	$nbprojetindustriel= $manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],INDUSTRIEL));
	$formation =$manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],FORMATION));
    }elseif(isset($_GET['centrale']) && $_GET['centrale'] != 99 && isset($_GET['l'])) {//CHOIX CENTRALE ANNEE=TOUS
        $nbprojetAcademique = $manager->getSinglebyArray($tousdateunecentrale,array(ACADEMIC,$_GET['centrale']));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($tousdateunecentrale,array(ACADEMICPARTENARIAT,$_GET['centrale']));
	$nbprojetindustriel= $manager->getSinglebyArray($tousdateunecentrale,array(INDUSTRIEL,$_GET['centrale']));
	$formation =$manager->getSinglebyArray($tousdateunecentrale,array(FORMATION,$_GET['centrale']));	
    }elseif(isset($_GET['centrale']) && $_GET['centrale'] == 99 && isset($_GET['l']) ) {//CHOIX CENTRALE ANNEE=TOUS
        $nbprojetAcademique = $manager->getSingle2($touscentraletoutesdate,ACADEMIC);
	$nbprojetAcademiquepartenariat=$manager->getSingle2($touscentraletoutesdate,ACADEMICPARTENARIAT);
	$nbprojetindustriel= $manager->getSingle2($touscentraletoutesdate,INDUSTRIEL);
	$formation =$manager->getSingle2($touscentraletoutesdate,FORMATION);
    }else {        
        $nbprojetAcademique = $manager->getSingle2($touscentraletoutesdate,ACADEMIC);
	$nbprojetAcademiquepartenariat=$manager->getSingle2($touscentraletoutesdate,ACADEMICPARTENARIAT);
	$nbprojetindustriel= $manager->getSingle2($touscentraletoutesdate,INDUSTRIEL);
	$formation =$manager->getSingle2($touscentraletoutesdate,FORMATION);
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1) {
        $nbprojetAcademique = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=? ",array($_GET['anneeprojettypeprojet'],ACADEMIC,$idcentrale));
        $nbprojetAcademiquepartenariat = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=?",array($_GET['anneeprojettypeprojet'],ACADEMICPARTENARIAT,$idcentrale));
        $nbprojetindustriel = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=?",array($_GET['anneeprojettypeprojet'],INDUSTRIEL,$idcentrale));
        $formation = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and  EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=?",array($_GET['anneeprojettypeprojet'],FORMATION,$idcentrale));
    } else {
        $nbprojetAcademique = $manager->getSinglebyArray("SELECT count(libelletype) FROM projet,typeprojet,concerne WHERE  idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? and idcentrale_centrale=? ",array(ACADEMIC,$idcentrale));
        $nbprojetAcademiquepartenariat = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and  idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? and idcentrale_centrale=?",array(ACADEMICPARTENARIAT,$idcentrale));
        $nbprojetindustriel = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? and idcentrale_centrale=?",array(INDUSTRIEL,$idcentrale));
        $formation = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and  idtypeprojet=? and idcentrale_centrale=?",array(FORMATION,$idcentrale));
    }
}
$string0 = '"' .TXT_ACADEMIQUE . '",' .'"'. TXT_ACADEMICPARTENARIAT . '",' .'"'.TXT_INDUSTRIEL . '",'.'"'.TXT_FORMATION . '",';
$string1 .=  $nbprojetAcademique.','.$nbprojetAcademiquepartenariat.','.$nbprojetindustriel.','.$formation.',' ;
$nbtotalprojet = $nbprojetAcademique+$nbprojetAcademiquepartenariat+$nbprojetindustriel+$formation;
if($typeUser==ADMINLOCAL){
    $xaxis = '['.substr($string0, 0, -1).']';
    $yaxis ='['. substr($string1, 0, -1).']';
}elseif($typeUser==ADMINNATIONNAL){
    $xaxis = '['.substr($string0, 0, -1).']';
    $yaxis ='['. substr($string1, 0, -1).']';
}
$title=TXT_PROJETPARDATETYPE;
if (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1 && isset($_GET['centrale']) && !empty($_GET['centrale'])) {
        $libellecentrale=$manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_GET['centrale']);
        $subtitle= TXT_NBPROJETALA . ' <b>' .$libellecentrale.'</b> '.TXT_POURLANNEE.' '. $_GET['anneeprojettypeprojet'] . ': <b>' . $nbtotalprojet.'</b>';    
}elseif (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] == 1 && isset($_GET['centrale']) && !empty($_GET['centrale'])) {//CHOIX TOUS POUR L'ANNEE
        $libellecentrale=$manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_GET['centrale']);
        $subtitle= TXT_NBPROJETALA . ' <b>' .$libellecentrale.'</b> '. ': <b>' . $nbtotalprojet.'</b>';    
}elseif (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1) {
    if($typeUser == ADMINNATIONNAL){
        $subtitle= TXT_NBPROJETSAU . ' ' . $_GET['anneeprojettypeprojet'] . ': <b>' . $nbtotalprojet.'</b>';    
    }elseif ($typeUser == ADMINLOCAL) {
        $subtitle= TXT_NBPROJET. ' ' .TXT_POURLANNEE.' '. $_GET['anneeprojettypeprojet'] . ': <b>' . $nbtotalprojet.'</b>';
    }
}elseif(isset($_GET['centrale'])&&isset($_GET['l'])&&$_GET['centrale']!=99){
    $libellecentrale=$manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_GET['centrale']);
    $subtitle= TXT_NBPROJETALA . ' <b>' .$libellecentrale.'</b> '. ': <b>' . $nbtotalprojet.'</b>';
}else {  
    $subtitle= TXT_NBPROJET . '  <b>' . $nbtotalprojet.'</b>';
}
$manager->exeRequete("drop table if exists tmpdateprojettypeprojet");
$manager->exeRequete("create table tmpdateprojettypeprojet as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojettypeprojet  from projet where EXTRACT(YEAR from dateprojet) >2012 order by EXTRACT(YEAR from dateprojet) asc;");
$row = $manager->getList("select anneeprojettypeprojet from tmpdateprojettypeprojet order by anneeprojettypeprojet asc");
$rowcentrale = $manager->getList("select idcentrale,libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");

?>
<table>
    <tr>
        <td>
            <select  id="anneeprojettypeprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'annee',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                     style="width: 250px;margin-left:10px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueProjetType/<?php echo $lang ?>/' + this.value)" >
                         <?php
                         for ($i = 0; $i < count($row); $i++) {
                             echo '<option value="' . ($row[$i]['anneeprojettypeprojet']) . '">' . $row[$i]['anneeprojettypeprojet'] . '</option>';
                         }echo '<option value="' . 1 . '">' . TXT_TOUS . '</option>';
                         ?>
            </select>
        </td>
</tr>

<?php if(isset($_GET['anneeprojettypeprojet'])&&!empty($_GET['anneeprojettypeprojet'])&& $typeUser==ADMINNATIONNAL){?>
<tr>
    <td>
        <select  id="centraleprojettypeprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'centrale',value: '',required:false,placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'" 
                 style="width: 250px;margin-left:10px" 
                 onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueProjetType/<?php echo $lang.'/'.$_GET['anneeprojettypeprojet'].'/'; ?>' + this.value)" >
                         <?php
                         for ($i = 0; $i < count($rowcentrale); $i++) {
                             echo '<option value="' . ($rowcentrale[$i]['idcentrale']) . '">' . $rowcentrale[$i]['libellecentrale'] . '</option>';
                         }echo '<option value="' . 99 . '">' . TXT_TOUS . '</option>';
                         ?>
        </select>
    </td>
</tr>
<?php }elseif($typeUser==ADMINNATIONNAL){?>
    <td>
        <select  id="centraleprojettypeprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'centrale',value: '',required:false,placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                     style="width: 250px;margin-left:10px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueProjetType/<?php echo $lang ?>/c/' + this.value)" >
                         <?php
                         for ($i = 0; $i < count($rowcentrale); $i++) {
                             echo '<option value="' . ($rowcentrale[$i]['idcentrale']) . '">' . $rowcentrale[$i]['libellecentrale'] . '</option>';
                         }echo '<option value="' . 99 . '">' . TXT_TOUS . '</option>';
                         ?>
        </select>
    </td>
</tr>
<?php }?>
</table>
<script>
        $(function () {
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            type: 'column',
            margin: 75,
            options3d: {
                enabled: true,
                alpha: 0,
                beta: 0,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: '<?php echo $title; ?>'
               
        },
         subtitle: {
                    text: "<?php echo $subtitle; ?>"
                },
        credits: {
                enabled: false
        },
         xAxis: {
            categories:  <?php  echo $xaxis; ?>
        },
        yAxis: {
            opposite: true,
            title: {
                    text: "<?php echo TXT_NOMBREOCCURRENCE; ?>"
                }   
        },
        
        plotOptions: {
            column: {
                depth: 25
            },
            series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.0f}'
                        }
                    }
        },
        series: [{
            name: 'valeurs',
            data: <?php echo $yaxis ;?>, color:'#338099',showInLegend: false 
        }]
    });

    function showValues() {
        $('#R0-value').html(chart.options.chart.options3d.alpha);
        $('#R1-value').html(chart.options.chart.options3d.beta);
    }

    // Activate the sliders
    $('#R0').on('change', function () {
        chart.options.chart.options3d.alpha = this.value;
        showValues();
        chart.redraw(false);
    });
    $('#R1').on('change', function () {
        chart.options.chart.options3d.beta = this.value;
        showValues();
        chart.redraw(false);
    });

    showValues();
});
</script>
<?php
BD::deconnecter(); 
?>
<div id="container" style="width: 1000px"></div>
<div id="sliders">
	<table>
            <tr><td style="font-size: 0.8em;">Alpha Angle</td><td><input id="R0" type="range" min="0" max="45" value="0"/> <span id="R0-value" class="value"></span></td></tr>
	    <tr><td style="font-size: 0.8em;">Beta Angle</td><td><input id="R1" type="range" min="0" max="45" value="0"/> <span id="R1-value" class="value"></span></td></tr>
	</table>
</div>
<hr>
