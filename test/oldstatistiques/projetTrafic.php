<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$totaltraffic = $manager->getSingle2("select c_total from compteur where c_login=?", 'temoin');
$arrayPeriode = $manager->getList("select distinct c_firstvisit from compteur order by c_firstvisit  asc");
$nbperiode = count($arrayPeriode);
$i=0;
$Valeur='0,';
$arrayValeur= array();
foreach ($arrayPeriode as $key => $value) {
    $i+= 1;
    $nbTraffic= $manager->getSingle2("select count(c_id) from compteur where c_firstvisit=?", $value[0]);
    $string0.='{value:' . ($i + 1) . ',text:"' . $value[0] . '"},';
    $string1.='.addSeries("' . $value[0] . '",[{y: ' . $nbTraffic . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $nbTraffic . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
    $Valeur.=$nbTraffic.',';    
    $arrayvaleur=  array_push($arrayValeur, $nbTraffic);
}
$string = substr($string0, 0, -1);
$string2 = substr($string1, 0, -1) . ');';
$valeur = substr($Valeur, 0, -1);
$maxvaleur = max($arrayValeur)*1.1;
$minvaleur = min($arrayValeur);
?>
<div style="width:1000px;text-align:center;font-size:12px;margin-top: 40px;"><?php echo TXT_NBVISITE . ': <b>' . $totaltraffic . '</b>'; ?><br/></div>
<div id="ChartTraffic" ></div>
<script>
require(["dojox/charting/Chart","dojox/charting/themes/Dollar","dojox/charting/plot2d/StackedAreas","dojox/charting/plot2d/Markers","dojox/charting/axis2d/Default","dojo/domReady!"],
    function(Chart, theme) {
        var chartData = [<?php echo $valeur; ?>];
        //var chartData = [0,10,20,5,15,10,10,20,5,15,10,10,20,5,15,0,10,20,5,15,10,14,22,10,0,5,15,10,10,20,5,15,10,10,20,5,15,0,0,20,5,15,10,14,22,23,46,88,45,22,88];
        var chart = new Chart("ChartTraffic");
        chart.setTheme(theme);
        chart.addPlot("default", {type: "StackedAreas"});
        chart.addAxis("x",{labels: [<?php echo $string; ?>],min:2,fixLower: "minor", fixUpper: "minor"});
        /*chart.addAxis("x",{labels: [{value:2,text:"2014-07-14"},{value:3,text:"2014-07-15"},{value:4,text:"2014-07-16"},{value:5,text:"2014-07-17"},{value:6,text:"2014-07-18"},{value:7,text:"2014-07-19"},{value:8,text:"2014-07-20"},
{value:9,text:"2014-07-21"},{value:10,text:"2014-07-22"},{value:11,text:"2014-07-23"},{value:12,text:"2014-07-24"},{value:13,text:"2014-07-25"},{value:14,text:"2014-07-26"},{value:15,text:"2014-07-27"},{value:16,text:"2014-07-28"},
{value:17,text:"2014-07-29"},{value:18,text:"2014-07-30"},{value:19,text:"2014-08-01"},{value:20,text:"2014-08-04"},{value:21,text:"2014-08-05"},{value:22,text:"2014-08-06"},{value:23,text:"2014-08-07"},{value:24,text:"2014-08-08"},
{value:25,text:"2014-08-09"},{value:26,text:"2014-08-10"},{value:27,text:"2014-08-11"},{value:28,text:"2014-08-12"},{value:29,text:"2014-08-13"},{value:30,text:"2014-08-14"},{value:31,text:"2014-08-15"},{value:32,text:"2014-08-16"},
{value:33,text:"2014-08-17"},{value:34,text:"2014-08-18"},{value:35,text:"2014-08-19"},{value:36,text:"2014-08-20"},{value:37,text:"2014-08-21"},{value:38,text:"2014-08-22"},{value:39,text:"2014-08-23"},{value:40,text:"2014-08-24"},
{value:41,text:"2014-08-25"},{value:42,text:"2014-08-26"},{value:43,text:"2014-08-27"},{value:44,text:"2014-08-28"},{value:45,text:"2014-08-29"},{value:46,text:"2014-08-30"},{value:47,text:"2014-08-31"},{value:48,text:"2014-09-01"},
{value:49,text:"2014-09-02"},{value:50,text:"2014-09-03"}],min:2,fixLower: "minor", fixUpper: "minor"});
        chart.addAxis("y", { min: 0, max: 90, vertical: true, fixLower: "major", fixUpper: "major" });*/
        chart.addAxis("y", { min: 0, max: <?php echo $maxvaleur; ?>, vertical: true, fixLower: "major", fixUpper: "major" });
        chart.addSeries("traffic",chartData);        
        chart.render();
});
</script>
<?php
BD::deconnecter();
