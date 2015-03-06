<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraySourcefinancement = $manager->getList("select libellesourcefinancement,idsourcefinancement from sourcefinancement");
$nbsf = count($arraySourcefinancement);
$string0 = '';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] != 1) {
        $nbtotalsource = $manager->getSinglebyArray("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet and EXTRACT(YEAR from dateprojet)=? ", array($_GET['anneeprojetsf']));
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet "
                    . "and EXTRACT(YEAR from dateprojet)=? and idsourcefinancement=?", array($_GET['anneeprojetsf'], $arraySourcefinancement[$i]['idsourcefinancement']));
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",' . round((($nbsource / $nbtotalsource) * 100), 1) . '],';
        }
    } else {
        $nbtotalsource = $manager->getSingle("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet ");
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSingle2("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet and idsourcefinancement=?", $arraySourcefinancement[$i]['idsourcefinancement']);
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",' . round((($nbsource / $nbtotalsource) * 100), 1) . '],';
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] != 1) {
        $nbtotalsource = $manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? ", array($idcentrale, $_GET['anneeprojetsf']));
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and ps.idsourcefinancement_sourcefinancement=?", array($idcentrale, $_GET['anneeprojetsf'], $arraySourcefinancement[$i]['idsourcefinancement']));            
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",' . round((($nbsource / $nbtotalsource) * 100), 1) . '],';
        }
    } else {
        $nbtotalsource = $manager->getSingle2("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? ", $idcentrale);
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and ps.idsourcefinancement_sourcefinancement=?", array($idcentrale, $arraySourcefinancement[$i]['idsourcefinancement']));
            $string0 .='["' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",' . round((($nbsource / $nbtotalsource) * 100), 1) . '],';
            
        }
    }
}
$string = substr($string0, 0, -1);
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
                    renderTo: 'chartNodeSFPIE',
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
<script src="<?php echo '/' . REPERTOIRE; ?>/js/grid-light.js"></script>
<div id="chartNodeSFPIE"></div>

