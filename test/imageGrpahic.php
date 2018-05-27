<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title> - jsFiddle demo by Fusher</title>
  <script src='../js/jquery-1.8.0.min.js'></script> 
  <script src="../js/highcharts.js"></script>
  <script src="../js/highcharts-3d.js"></script>
   <script src="../js/exporting.js"></script>
</head>
<body>
<script>
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'area'
        },
        title: {
            text: 'US and USSR nuclear stockpiles'
        },
        subtitle: {
            text: 'Source: <a href="http://thebulletin.metapress.com/content/c4120650912x74k7/fulltext.pdf">' +
                'thebulletin.metapress.com</a>'
        },
        xAxis: {
            allowDecimals: false,
            labels: {
                formatter: function () {
                    return this.value; // clean, unformatted number for year
                }
            }
        },
        yAxis: {
            title: {
                text: 'Nuclear weapon states'
            },
            labels: {
                formatter: function () {
                    return this.value   ;
                }
            }
        },
        tooltip: {
            pointFormat: '{series.name} produced <b>{point.y:,.0f}</b><br/>warheads in {point.x}'
        },
        plotOptions: {
            area: {
                pointStart: 01    ,
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
            name: 'USA',
            data: [3,3,3,3,1,3,2,1,2,2,3,8,8,13,1,2,21,4,11,8,8,3,2,3,5,10,10,5,5,9,5,4,11,13,9,7,9,5,7,9,6,2,21,4,8,6,12,12,12,8,17,18,5,9,8,10,3,2,21,6,11,14,18,2,5,6]
        }]
    });
});
</script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</body>


</html>

