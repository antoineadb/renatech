<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$arraySourcefinancement = $manager->getList("select libellesourcefinancement,idsourcefinancement from sourcefinancement");
$nbsf = count($arraySourcefinancement);
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$nbprojet = 0;
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] != 1) {
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet "
                    . "and EXTRACT(YEAR from dateprojet)=? and idsourcefinancement=?", array($_GET['anneeprojetsf'], $arraySourcefinancement[$i]['idsourcefinancement']));
            $string0 .= '"' .$arraySourcefinancement[$i]['libellesourcefinancement']. '",';
            $string1 .=  $nbsource.',' ;            
            $nbprojet+=$nbsource;
        }
    } else {
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet "
                    . "and idsourcefinancement=?", array($arraySourcefinancement[$i]['idsourcefinancement']));
            $string0 .= '"' .$arraySourcefinancement[$i]['libellesourcefinancement']. '",';
            $string1 .=  $nbsource.',' ;
            $nbprojet+=$nbsource;
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] != 1) {
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and ps.idsourcefinancement_sourcefinancement=?", array($idcentrale, $_GET['anneeprojetsf'], $arraySourcefinancement[$i]['idsourcefinancement']));            
            $string0 .= '"' .$arraySourcefinancement[$i]['libellesourcefinancement']. '",';
            $string1 .=  $nbsource.',' ;  
            $nbprojet+=$nbsource;
        }
    } else {
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and ps.idsourcefinancement_sourcefinancement=?", array($idcentrale, $arraySourcefinancement[$i]['idsourcefinancement']));
            $string0 .= '"' .$arraySourcefinancement[$i]['libellesourcefinancement']. '",';
            $string1 .=  $nbsource.',' ;  
            $nbprojet+=$nbsource;
        }
    }
}

$xaxis = '['.substr($string0, 0, -1).']';
$yaxis ='['. substr($string1, 0, -1).']';


if (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] != 1) {
    $title=TXT_SFBYDATE;;
    $subtitle= TXT_NBSFAU . ' ' . $_GET['anneeprojetsf'] . ': <b>' . $nbprojet.'</b>';
}else {  
    $subtitle= TXT_NBSF . ': <b>' . $nbprojet.'</b>';     
    $title=TXT_SFBYDATE;
} 

$manager->exeRequete("drop table if exists tmpdate");
$manager->exeRequete("create table tmpdate as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet where EXTRACT(YEAR from dateprojet)>2012;");
$row = $manager->getList("select anneeprojet from tmpdate order by anneeprojet asc");
?>
<table>
    <tr>
        <td>
            <div style="float: left">
                <select  id="anneesf" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellepays',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                         style="width: 250px;margin-left:35px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueSF/<?php echo $lang ?>/' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($row); $i++) {
                                 echo '<option value="' . ($row[$i]['anneeprojet']) . '">' . $row[$i]['anneeprojet'] . '</option>';
                             }
                             echo '<option value="' . 1 . '">' . TXT_TOUS . '</option>';
                             ?>
                </select>
            </div>
            <div style="float: right;margin-left:20px;margin-top:28px;">
                <?php
                if (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] != 1) {
                    $dateselection = $_GET['anneeprojetsf'];
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                } elseif (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] == 1) {
                    $dateselection = TXT_TOUS;
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                }
                ?></div>
        </td>
    </tr>
</table>
<script>
        $(function () {
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'ChartDivSF',
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
<div id="ChartDivSF" style="width: 1000px"></div>
<div id="sliders">
	<table>
            <tr><td style="font-size: 0.8em;">Alpha Angle</td><td><input id="R0" type="range" min="0" max="45" value="0"/> <span id="R0-value" class="value"></span></td></tr>
	    <tr><td style="font-size: 0.8em;">Beta Angle</td><td><input id="R1" type="range" min="0" max="45" value="0"/> <span id="R1-value" class="value"></span></td></tr>
	</table>
</div>
<hr>
