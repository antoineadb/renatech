<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title> - jsFiddle demo by Fusher</title>
  <script type='text/javascript' src='../js/jquery-1.8.0.min.js'></script>
  <script type='text/javascript' src="../js/rgbcolor.js"></script>
  <script type='text/javascript' src="../js/StackBlur.js"></script>
  <script type='text/javascript' src="../js/canvg.js"></script>
  
  <script>
      $(function () {
    var toRad = Highcharts.toRadians;
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 0,
                beta: 0,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Projet par date et par type'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        credits: {
                enabled: false
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
            name: 'valeur',
            data: [
                ['En attente',3.3],['En cours d\'analyse',1.2],['En cours d\'expertise',3.3],['Refusé',2.7],['Fini',9.8],['Cloturé',0],['En cours de réalisation',79.9]
            ]
        }]
    });
      $('#R1').on('change', function () {
        chart.options.chart.options3d.angle1 = toRad(this.value);
        chart.series[0].update();
    });
});
    </script>
</head>
<body>
<script src="../js/highcharts.js"></script>
<script src="../js/exporting.js"></script>
<script src="../js//highcharts-3d.js"></script>

<div id="container" style="height: 400px; width: 1000px; border-style:solid; border-width:thin;"></div>
<input id="R1" type="range" min="0" max="90" value="30" />
</body>


</html>

