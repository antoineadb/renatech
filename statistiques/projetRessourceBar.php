<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$arrayRessource = $manager->getList("select libelleressource,idressource from ressource");
$totalressource = count($arrayRessource);
$nbprojet = 0;
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] != 1) {
        for ($i = 0; $i < $totalressource; $i++) {
            $nbressource = $manager->getSinglebyArray("SELECT count(idressource) FROM ressource,ressourceprojet,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet "
                    . "and EXTRACT(YEAR from dateprojet)=? and idressource=?", array($_GET['anneeprojetRessource'], $arrayRessource[$i]['idressource']));
            $string0 .= '"' .$arrayRessource[$i]['libelleressource']. '",';
            $string1 .= $nbressource.',' ;
            $nbprojet +=$nbressource;
        }
    } else {
        for ($i = 0; $i < $totalressource; $i++) {
            $nbressource = $manager->getSinglebyArray("SELECT count(idressource) FROM ressource,ressourceprojet,projet WHERE idressource_ressource = idressource "
                    . "AND idprojet = idprojet_projet and idressource=?", array($arrayRessource[$i]['idressource']));
            $nbprojet +=$nbressource;
            $string0 .= '"' .$arrayRessource[$i]['libelleressource']. '",';
            $string1 .= $nbressource.',' ;
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] != 1) {
        for ($i = 0; $i < $totalressource; $i++) {
            $nbressource = $manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and idressource=?", array($idcentrale, $_GET['anneeprojetRessource'], $arrayRessource[$i]['idressource']));            
            $nbprojet +=$nbressource;
            $string0 .= '"' .$arrayRessource[$i]['libelleressource']. '",';
            $string1 .= $nbressource.',' ;
        }
    } else {
        for ($i = 0; $i < $totalressource; $i++) {
            $nbressource = $manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and idressource=?", array($idcentrale, $arrayRessource[$i]['idressource']));          
            $nbprojet +=$nbressource;
            $string0 .= '"' .$arrayRessource[$i]['libelleressource']. '",';
            $string1 .= $nbressource.',' ;
            
        }
    }
}

$xaxis = '['.substr($string0, 0, -1).']';
$yaxis ='['. substr($string1, 0, -1).']';
if (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] != 1) {
    $title=TXT_NBRESSOURCEDATE;
    $subtitle= TXT_NBRESSOURCEAU . ' ' . $_GET['anneeprojetRessource'] . ': <b>' . $nbprojet.'</b>';
}else {  
    $subtitle= TXT_NBRESSOURCE . ': <b>' . $nbprojet.'</b>';     
    $title=TXT_NBRESSOURCEDATE;
} 
$manager->exeRequete("drop table if exists tmpdate");
$manager->exeRequete("create table tmpdate as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet where EXTRACT(YEAR from dateprojet)>2012;");
$row = $manager->getList("select anneeprojet from tmpdate order by anneeprojet asc");
?>
<table><tr><td>
            <div style="float: left;margin-left:20px;margin-top:0px;">
                <select  id="anneeRessource" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellepays',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                         style="width: 250px;margin-left:35px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueRessource/<?php echo $lang ?>/' + this.value)" >
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
                if (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] != 1) {
                    $dateselection = $_GET['anneeprojetRessource'];
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                } elseif (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] == 1) {
                    $dateselection = TXT_TOUS;
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                }
                ?></div>
        </td>
    </tr></table>
<script>
        $(function () {
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'ChartDivRESSOUCE',
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
<div id="ChartDivRESSOUCE" style="width: 1000px"></div>
<div id="sliders">
	<table>
            <tr><td style="font-size: 0.8em;">Alpha Angle</td><td><input id="R0" type="range" min="0" max="45" value="0"/> <span id="R0-value" class="value"></span></td></tr>
	    <tr><td style="font-size: 0.8em;">Beta Angle</td><td><input id="R1" type="range" min="0" max="45" value="0"/> <span id="R1-value" class="value"></span></td></tr>
	</table>
</div>
<hr>
