<?php
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'class/Manager.php';
$db = BD::connecter();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">          
        <script src='<?php echo '/'.REPERTOIRE; ?>/js/jquery-1.8.0.min.js'></script>
        <script src="<?php echo '/'.REPERTOIRE; ?>/js/highcharts.js"></script>
        <script src="<?php echo '/'.REPERTOIRE; ?>/js/exporting.js"></script>
        <script src="<?php echo '/'.REPERTOIRE; ?>/js/rgbcolor.js"></script>
        <script src="<?php echo '/'.REPERTOIRE; ?>/js/StackBlur.js"></script>
        <script src="<?php echo '/'.REPERTOIRE; ?>/js/canvg.js"></script>
        <script src="<?php echo '/'.REPERTOIRE; ?>/js/highcharts-3d.js"></script>
        <script src="<?php echo '/'.REPERTOIRE; ?>/js/drilldown.js"></script>
        <script src="<?php echo '/'.REPERTOIRE; ?>/js/grid-light.js"></script>
        
        <?php        ?>
        <script type='text/javascript'>//<![CDATA[
            $(function () {
               Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
            return {
                radialGradient: {cx: 0.5, cy: 0.3, r: 0.7},
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.5).get('rgb')] // darken
                ]
                    };
                });
                // Create the chart
                var chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'container',
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45,
                            beta: 0
                    } 
                    },
                    title: {
                        text: 'Deep drilldown'
                    },
                    
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    xAxis: {
                        type: 'category'
                    },
                    legend: {
                        enabled: true
                    },
                    credits: {
                        enabled: false
                    },
                    plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            },
                            
                            }
                        }
                    },
                    series: [{
                            type: 'pie',
                            name: 'Things',
                            colorByPoint: true,
                            data: [{
                                    name: 'Animals',
                                    y: 5,
                                    drilldown: 'animals'
                                }, {
                                    name: 'Food',
                                    y: 4,
                                    drilldown: 'food'
                                }]
                        }],
                    drilldown: {
                        series: [{
                                id: 'food',
                                name: 'Food',
                                data: [{
                                        name: 'Apple',
                                        y: 1.5,
                                        drilldown: 'apple'
                                    },
                                    ['Banana', 1],
                                    ['Peer', 0.5],
                                    ['Pineapple', 1]
                                ]
                            }, {
                                id: 'apple',
                                data: [['1/6', 1],
                                    ['1/3', 2],
                                    ['1/2', 3]]
                            }, {
                                id: 'animals',
                                name: 'Animals',
                                data: [{
                                        name: 'Cats',
                                        y: 5,
                                        drilldown: 'cats'
                                    }, ['Dogs', 2],
                                    ['Cows', 1],
                                    ['Sheep', 1],
                                    ['Pigs', 1]
                                ]
                            }, {
                                id: 'cats',
                                data: [1, 2, 3]
                            }]
                    }
                })
            });
            //]]> 

        </script>

    </head>
    <body>        
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div><hr>
    </body>

</html>

<?php
BD::deconnecter();
