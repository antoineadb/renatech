<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneeuserdate from utilisateur order by anneeuserdate asc");
$string = '';
$string1 = '';
$string2 = '';
$string3 = '';
if ($typeUser == ADMINNATIONNAL) {
    $nbTotaluser = $manager->getSingle("select count (idutilisateur) from utilisateur");
        $nbUserExterne = $manager->getSingle("select count(idutilisateur) from utilisateur where idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca is not null");
        $string .= '["' . TXT_ACADEMIQUEEXTERNE . '",' . round((($nbUserExterne / $nbTotaluser) * 100), 1) . '],';
        $nbUserInterne = $manager->getSingle("select count(idutilisateur) from utilisateur where idcentrale_centrale is not null");
        $string .= '["' . TXT_ACADEMIQUEINTERNE . '",' . round((($nbUserInterne / $nbTotaluser) * 100), 1) . '],';
        $nbUserIndustriel = $manager->getSingle("select count(idutilisateur) from utilisateur where idqualitedemandeurindust_qualitedemandeurindust is not null");
        $string .= '["' . TXT_INDUSTRIEL . '",' . round((($nbUserIndustriel / $nbTotaluser) * 100), 1) . '],';

}

$string = substr($string, 0, -1);

$title = 'a définir';
$subtitle = 'a définir';
?>

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
        var chart = new Highcharts.Chart({
            chart: {
                renderTo: 'ChartDivUserPie2',
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: '<?php echo $title; ?>'
            },
            subtitle: {
                text: "<?php echo $subtitle; ?>"
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                        connectorColor: 'silver'
                    }
                }
            },
            series: [{
                    type: 'pie',
                    name: '<?php echo TXT_VALEUR; ?>',
                    data: [
<?php echo $string ?>
                    ]
                }]
        });
    });
</script>

<div id="ChartDivUserPie2" ></div>
<?php
BD::deconnecter();

