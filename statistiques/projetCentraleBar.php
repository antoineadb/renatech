<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$xaxis = '';
$yaxis = '';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$nbprojet = 0;
$arraystatutprojet = $manager->getList2("select libellestatutprojet,libellestatutprojeten,idstatutprojet from statutprojet where idstatutprojet!=? order by ordre asc", TRANSFERERCENTRALE);
$nbarraystatutprojet  =count($arraystatutprojet);
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 1) {
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=?
            AND  EXTRACT(YEAR from dateprojet)=?", array($arraylibellecentrale[$i]['libellecentrale'], $_GET['anneeprojet']));
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];            
            $xaxis.= '"'.$centrale . '",';
            $yaxis.=$donneeProjet.',';
            $nbprojet +=$donneeProjet;
        }
    } elseif (isset($_GET['statut']) && $_GET['statut'] != 99) {
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet_projet) FROM  projet,concerne,statutprojet,centrale WHERE idprojet = idprojet_projet AND idstatutprojet_statutprojet = idstatutprojet AND idcentrale_centrale =idcentrale and  libellecentrale=?  and idstatutprojet_statutprojet=?
    ", array($arraylibellecentrale[$i]['libellecentrale'], $_GET['statut']));
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $xaxis.= '"'.$centrale . '",';
            $yaxis.=$donneeProjet.',';
            $nbprojet +=$donneeProjet;
        }
    } else {
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSingle2("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=?
    ", $arraylibellecentrale[$i]['libellecentrale']);
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $xaxis.= '"'.$centrale . '",';
            $yaxis.=$donneeProjet.',';            
            $nbprojet +=$donneeProjet;            
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 1) {
        for ($i = 0; $i < $nbarraystatutprojet; $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND  idcentrale_centrale=?
            AND  EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet=?", array($idcentrale, $_GET['anneeprojet'], $arraystatutprojet[$i]['idstatutprojet']));
            if($lang=='fr'){
                $xaxis.='"'. stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '",';
            }elseif($lang=='en'){
                $xaxis.='"'. stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojeten'])) . '",';
            }
            $yaxis.=$donneeProjet.',';
            $nbprojet +=$donneeProjet;
        }
    } else {
        for ($i = 0; $i < $nbarraystatutprojet; $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale_centrale=? and idstatutprojet_statutprojet=?", array($idcentrale, $arraystatutprojet[$i]['idstatutprojet']));
            if($lang=='fr'){
                $xaxis.='"'. stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '",';
            }elseif($lang=='en'){
                $xaxis.='"'. stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojeten'])) . '",';
            }
            $yaxis.=$donneeProjet.',';
            
            $nbprojet +=$donneeProjet;
        }       
    }
}
$xaxis1 = '['.substr($xaxis, 0, -1). ']';
$yaxis1 = '['.substr($yaxis, 0, -1) . ']';
$manager->exeRequete("drop table if exists tmpdateprojet");
$manager->exeRequete("create table tmpdateprojet as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet where EXTRACT(YEAR from dateprojet)>2012 order by EXTRACT(YEAR from dateprojet) asc;");
?>
<table>
    <tr>
        <td>
            <select  id="anneeprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'annee',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'" 
            style="width: 250px;margin-left:35px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueProjet/<?php echo $lang ?>/' + this.value)" >
                <?php
                $row = $manager->getList("select anneeprojet from tmpdateprojet order by anneeprojet asc");
                for ($i = 0; $i < count($row); $i++) {
                    echo '<option value="' . ($row[$i]['anneeprojet']) . '">' . $row[$i]['anneeprojet'] . '</option>';
                }echo '<option value="' . 1 . '">' . TXT_TOUS . '</option>';
                ?>
            </select>
        </td>
    </tr>
    <tr><?php if ($typeUser == ADMINNATIONNAL) { ?>
        <td>
                <select  id="statutProjet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'statut',value: '',required:false,placeHolder: '<?php echo TXT_SELECTSTATUT; ?>'"                 
                         style="width: 250px;margin-top:5px;margin-left: 35px" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statutProjet/<?php echo $lang ?>/' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($arraystatutprojet); $i++) {
                                 if ($lang == 'fr') {
                                     echo '<option value="' . ($arraystatutprojet[$i]['idstatutprojet']) . '">' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '</option>';
                                 } elseif ($lang == 'en') {
                                     echo '<option value="' . ($arraystatutprojet[$i]['idstatutprojet']) . '">' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojeten'])) . '</option>';
                                 }
                             }echo '<option value="' . 99 . '">' . TXT_TOUS . '</option>';
                             ?>
                </select> 
            </td>
        <?php } ?>
    </tr>
</table>
<?php if (isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 1) {
    if($typeUser == ADMINNATIONNAL){
        $title=TXT_PROJETDATESTATUT;
        $subtitle= TXT_NBPROJETSAU . ' ' . $_GET['anneeprojet'] . ': <b>' . $nbprojet.'</b>';    
    }elseif ($typeUser == ADMINLOCAL) {
        $title=TXT_PROJETPARDATETYPE;
        $subtitle= TXT_NBPROJETSALA . ' ' . $_GET['anneeprojet'] . ': <b>' . $nbprojet.'</b>';
    }

} elseif (isset($_GET['statut']) && $_GET['statut'] != 99) {
    $nbtotalprojetstatut = $manager->getSingle2("select count(idprojet_projet) from concerne where idstatutprojet_statutprojet=?", $_GET['statut']);
    $libellestatut = $manager->getSingle2("select libellestatutprojet from statutprojet where idstatutprojet=?", $_GET['statut']);
    $title=TXT_PROJETDATESTATUT;
    $subtitle= TXT_NBPROJET . ' ' . strtolower(stripslashes(str_replace("''", "'", $libellestatut))) . ': <b>' . $nbtotalprojetstatut.'</b>';    
} else {  
    $subtitle= TXT_NBPROJET . ' <b>' . $nbprojet.'</b>'; 
    $title=TXT_PROJETDATESTATUT;
} ?> 
    <script>   
        $(function () {
    // Set up the chart
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
            text: '<?php echo $title; ?>',
               
        },
         subtitle: {
                    text: "<?php echo $subtitle; ?>"
                },
        credits: {
                enabled: false
        },
         xAxis: {
            categories:  <?php  echo $xaxis1?>
        },
        yAxis: {
            opposite: true,
            title: {
                    text: "<?php echo TXT_NOMBREOCCURRENCE; ?>"
                },
                    
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
            data: <?php echo $yaxis1; ?>, color:'#338099',showInLegend: false 
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