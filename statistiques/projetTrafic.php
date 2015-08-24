<?php 
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$totaltraffic = $manager->getSingle2("select c_total from compteur where c_login=?", 'temoin');
$arrayPeriode = $manager->getList("select distinct c_firstvisit from compteur order by c_firstvisit  asc");
$nbperiode = count($arrayPeriode);
$Valeur = '';
$arrayValeur = array();
$interv =  round($nbperiode/10,0);
foreach ($arrayPeriode as $key => $value) {
    $nbTraffic = $manager->getSingle2("select count(c_id) from compteur where c_firstvisit=?", $value[0]);    
    $string0.='"' . $value[0] . '",';
    $Valeur.=$nbTraffic . ',';
    $arrayvaleur = array_push($arrayValeur, $nbTraffic);
}
$string = substr($string0, 0, -1);
$valeur = substr($Valeur, 0, -1);
$maxvaleur = max($arrayValeur);
$minvaleur = min($arrayValeur);
?>
<script src='<?php echo '/' . REPERTOIRE; ?>/js/jquery-1.8.0.min.js'></script>
<script src="<?php echo '/' . REPERTOIRE; ?>/js/highcharts.js"></script>
<script src="<?php echo '/' . REPERTOIRE; ?>/js/exporting.js"></script>
<?php if ($lang == 'fr') { ?>
    <script>
        $(function () {
            $('#ChartTraffic').highcharts({
                chart: {
                    type: 'area'
                },
                title: {
                    text: '<?php echo TXT_TRAFFIC; ?>'
                },
                subtitle: {
                    text: '<?php echo TXT_NBVISITE . ': <b>' . $totaltraffic . '</b>'; ?>'
                },
                credits: {
                    enabled: false//Pour éviter d'avoir le lien wwww.hightchart.com en bas à droite de la courbe
                },
                xAxis: {
                    allowDecimals: false,
                    lineWidth: 0,
                    minorGridLineWidth: 0,
                    lineColor: 'transparent',
                    categories: [<?php echo $string; ?>],
                    labels: {
                        enabled: true,
                        step: <?php echo $interv;?>,
                        staggerLines: 1,
                        style: {
                            color: 'black'
                        },
                        formatter: function () {
                            var mydate = new Date(this.value);
                            return mydate.toLocaleDateString();
                        }
                    },
                    minorTickLength: 0,
                    tickLength: 0
                },
                yAxis: {
                    title: {
                        text: '<?php echo TXT_NOMBREBVISITE; ?>'
                    },
                    max: '<?php echo $maxvaleur; ?>',
                    labels: {
                        formatter: function () {
                            return this.value;
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}<b>{point.y:,.0f}</b>'
                },
                plotOptions: {
                    area: {
                        marker: {
                            enabled: false,
                            symbol: 'circle',
                            radius: 2,
                            states: {
                                hover: {
                                    enabled: true
                                }
                            }
                        }
                    }
                },
                series: [{
                        name: '<?php echo TXT_NOMBREBVISITE; ?>',
                            data: [<?php echo $valeur; ?>], showInLegend: false, color: '#3B8099'
                    }]
            });
        });
    </script>
<?php } else { ?>
    <script>
        $(function () {
            $('#ChartTraffic').highcharts({
                chart: {
                    type: 'area'
                },
                title: {
                    text: '<?php echo TXT_TRAFFIC; ?>'
                },
                subtitle: {
                    text: '<?php echo TXT_NBVISITE . ': <b>' . $totaltraffic . '</b>'; ?>'
                },
                credits: {
                    enabled: false//Pour éviter d'avoir le lien wwww.hightchart.com en bas à droite de la courbe
                },
                xAxis: {
                    type: 'datetime',
                    allowDecimals: false,
                    lineWidth: 0,
                    minorGridLineWidth: 0,
                    lineColor: 'transparent',
                    categories: [<?php echo $string; ?>],
                    labels: {
                        enabled: true,
                        step: 10,
                        staggerLines: 1,
                        style: {
                            color: 'black'
                        }
                    },
                    minorTickLength: 0,
                    tickLength: 0
                },
                yAxis: {
                    title: {
                        text: '<?php echo TXT_NOMBREBVISITE; ?>'
                    },
                    max: '<?php echo $maxvaleur; ?>',
                    labels: {
                        formatter: function () {
                            return this.value;
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}<b>{point.y:,.0f}</b>'
                },
                plotOptions: {
                    area: {
                        marker: {
                            enabled: false,
                            symbol: 'circle',
                            radius: 2,
                            states: {
                                hover: {
                                    enabled: true
                                }
                            }
                        }
                    }
                },
                series: [{
                        name: '<?php echo TXT_NOMBREBVISITE; ?>',
                        data: [<?php echo $valeur; ?>], showInLegend: false, color: '#3B8099'
                    }]
            });
        });
    </script>
    <?php
}
BD::deconnecter();
?>
<div id="ChartTraffic" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

 
 
