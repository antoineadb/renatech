<script type='text/javascript'>//<![CDATA[
    $(function () {
        Highcharts.setOptions({
            lang: {
                drillUpText: "<"
            }
        });
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
            credits: {
                enabled: false//Pour éviter d'avoir le lien wwww.hightchart.com en bas à droite de la courbe
            },
            title: {
                text: "<?php echo $title; ?>",
                style: {'font-size': '1.8em', 'font-weight': 'bolder'}
            },
            subtitle: {
                text: '<?php echo $subtitle ?>'
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: "<?php echo $xasisTitle; ?>"
                }

            },
            legend: {
                enabled: true,
                x: -20,
                y: 10
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.0f}'
                    }                   
                }
                
            },                      
                
            series: [<?php echo $serieX ?>],
            drilldown: {
                _animation: {
                    duration: 2000
                },
                series:
                        [<?php echo $serieY; ?>]
            }
        })
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
    //]]> 

</script>
<div id="container" style="width: 1000px"></div>

<div id="sliders">
    <table>
        <tr><td style="font-size: 0.8em;">Alpha Angle</td><td><input id="R0" type="range" min="0" max="45" value="0"/> <span id="R0-value" class="value"></span></td></tr>
        <tr><td style="font-size: 0.8em;">Beta Angle</td><td><input id="R1" type="range" min="0" max="45" value="0"/> <span id="R1-value" class="value"></span></td></tr>
    </table>
</div>
<hr>