<script src='<?php echo '/' . REPERTOIRE; ?>/js/jquery-1.8.0.min.js'></script> 
<script src='<?php echo '/' . REPERTOIRE; ?>/js/controle.js'></script> 
<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$totaltraffic = $manager->getSingle2("select c_total from compteur where c_login=?", 'temoin');
$arrayPeriode = $manager->getList("select distinct c_firstvisit from compteur order by c_firstvisit  asc");
$nbperiode = count($arrayPeriode);
$interv = round($nbperiode / 10, 0);
$fptrafic = fopen('tmp/sitetrafic.json', 'w');
$data = "";
for ($i = 0; $i < $nbperiode; $i++) {
    $nbTraffic = $manager->getSingle2("select count(c_id) from compteur where c_firstvisit=?", $arrayPeriode[$i][0]);
    $data = '[' . (strtotime($arrayPeriode[$i][0].' GMT').'000') . ',' . $nbTraffic . '],';
    fputs($fptrafic, $data);
    fwrite($fptrafic, '');
}
$jsonFile = "tmp/sitetrafic.json";
$json = file_get_contents($jsonFile);
$jsonFinish = '[' . substr($json, 0, -1) . ']';
file_put_contents($jsonFile, $jsonFinish);
fclose($fptrafic);
chmod('tmp/sitetrafic.json', 0777);
?>

<script type='text/javascript'>//<![CDATA[
    $(function () {
         $.getJSON("<?php echo '/' . REPERTOIRE . '/tmp/sitetrafic.json'; ?>", function (data) {
            // Create the chart
            $('#ChartTraffic').highcharts('StockChart', {
                rangeSelector: {
                    selected: 1
                },
                subtitle: {
                      text: '<?php echo TXT_NBVISITE.': <b>'.$totaltraffic.'</b>'; ?>'
                },                
                credits: {
                   enabled: false//Pour éviter d'avoir le lien wwww.hightchart.com en bas à droite de la courbe
               },
         
                series: [{
                        name: '<?php echo TXT_NOMBREBVISITE; ?>',
                        data: data,
                        type: 'area',
                        threshold: null,
                       
                        tooltip: {
                            valueDecimals: 0
                        },
                                
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },
                            stops: [
                                [0, Highcharts.getOptions().colors[0]],
                                [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                            ]
                        }
                    }]
            });
        });
    });


    //]]> 

</script> 
<?php
BD::deconnecter();
?>
<div id="ChartTraffic" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script src="<?php echo '/' . REPERTOIRE; ?>/js/highstock.js"></script>
<script src="<?php echo '/' . REPERTOIRE; ?>/js/exporting.js"></script>
