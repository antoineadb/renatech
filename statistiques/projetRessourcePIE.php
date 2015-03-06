<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arrayRessource = $manager->getList("select libelleressource,idressource from ressource");
$totalressource = count($arrayRessource);
$string0 = '';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] != 1) {
        $nbtotalressource = $manager->getSinglebyArray("SELECT count(idressource) FROM ressourceprojet,ressource,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet and EXTRACT(YEAR from dateprojet)=? ", array($_GET['anneeprojetRessource']));
        if (!empty($nbtotalressource)) {
            for ($i = 0; $i < $totalressource; $i++) {
                $nbressource = $manager->getSinglebyArray("SELECT count(idressource) FROM ressourceprojet,ressource,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet "
                        . "and EXTRACT(YEAR from dateprojet)=? and idressource=?", array($_GET['anneeprojetRessource'], $arrayRessource[$i]['idressource']));
                $string0 .='["' . $arrayRessource[$i]['libelleressource'] . '",' . round((($nbressource / $nbtotalressource) * 100), 1) . '],';
            }
        }
    } else {
        $nbtotalressource = $manager->getSingle("SELECT count(idressource) FROM ressourceprojet,ressource,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet ");
        for ($i = 0; $i < $totalressource; $i++) {
            $nbressource = $manager->getSingle2("SELECT count(idressource) FROM ressourceprojet,ressource,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet  and idressource=?", $arrayRessource[$i]['idressource']);
            $string0 .='["' . $arrayRessource[$i]['libelleressource'] . '",' . round((($nbressource / $nbtotalressource) * 100), 1) . '],';            
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] != 1) {
        $nbtotalressource = $manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? ", array($idcentrale, $_GET['anneeprojetRessource']));
        if (!empty($nbtotalressource)) {
            for ($i = 0; $i < $totalressource; $i++) {
                $nbressource = $manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and idressource=?", array($idcentrale, $_GET['anneeprojetRessource'], $arrayRessource[$i]['idressource']));
                $string0 .='["' . $arrayRessource[$i]['libelleressource'] . '",' . round((($nbressource / $nbtotalressource) * 100), 1) . '],';
            }
        }
    } else {
        $nbtotalressource = $manager->getSingle2("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? ", $idcentrale);
        if (!empty($nbtotalressource)) {
            for ($i = 0; $i < $totalressource; $i++) {
                $nbressource = $manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and idressource=?", array($idcentrale, $arrayRessource[$i]['idressource']));
                $string0 .='["' . $arrayRessource[$i]['libelleressource'] . '",' . round((($nbressource / $nbtotalressource) * 100), 1) . '],';
            }
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
                    renderTo: 'chartNodeRESSOURCEPIE',
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
<div id="chartNodeRESSOURCEPIE"></div>

