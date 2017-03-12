<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
array_push($arraylibellecentrale, array("libellecentrale" => '' . TXT_ACADEMIQUEEXTERNE . ''));
array_push($arraylibellecentrale, array("libellecentrale" => '' . TXT_INDUSTRIEL . ''));
$string0 = '';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {
        $nbtotaluser = $manager->getSingle2("SELECT  count(idutilisateur) FROM utilisateur WHERE   EXTRACT(YEAR from datecreation)=?", $_GET['anneeuser']);
        $donneeuserindustriel = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeurindust_qualitedemandeurindust is not null and EXTRACT(YEAR from datecreation)=?", $_GET['anneeuser']);
        $donneeuseracaexterne = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null and EXTRACT(YEAR from datecreation)=?", $_GET['anneeuser']);
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeUser = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND EXTRACT(YEAR from datecreation)=? and libellecentrale=?", array($_GET['anneeuser'], $arraylibellecentrale[$i]['libellecentrale']));
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            if ($donneeUser != 0) {
                $string0.='["' . $centrale . '",' . round(($donneeUser / ($nbtotaluser) * 100), 1) . '],';
            }
        }
    } else {
        $donneeuserindustriel = $manager->getSingle("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeurindust_qualitedemandeurindust is not null ");
        $donneeuseracaexterne = $manager->getSingle("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null");
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $nbtotaluser = $manager->getSingle("select count(idutilisateur) from utilisateur");
            $donneeUser = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND libellecentrale=?", $arraylibellecentrale[$i]['libellecentrale']);
            if ($donneeUser != 0) {
                $centrale = $arraylibellecentrale[$i]['libellecentrale'];
                $string0.='["' . $centrale . '",' . round(($donneeUser / ($nbtotaluser) * 100), 1) . '],';              
            }
        }  
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {
        $donneeuseracaexterne = $manager->getSinglebyArray("select count(u.idutilisateur)  FROM  creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND co.idprojet_projet = p.idprojet 
            AND u.idutilisateur = cr.idutilisateur_utilisateur AND u.idcentrale_centrale is null and u.idqualitedemandeuraca_qualitedemandeuraca is not null and co.idcentrale_centrale = ? 
            and EXTRACT(YEAR from datecreation)=?", array($idcentrale, $_GET['anneeuser']));
        $donneeuserindustriel = $manager->getSinglebyArray("SELECT count(u.idutilisateur) FROM creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND  co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur and co.idcentrale_centrale = ? AND EXTRACT(YEAR from datecreation)=? and u.idqualitedemandeurindust_qualitedemandeurindust is not null", array($idcentrale, $_GET['anneeuser']));
        $donneeUser = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND EXTRACT(YEAR from datecreation)=? and idcentrale_centrale=?", array($_GET['anneeuser'], $idcentrale));
        $nbtotaluser = $donneeuseracaexterne + $donneeuserindustriel + $donneeUser;
        $string0.='["' . TXT_ACADEMIQUEINTERNE . '",' . round(($donneeUser / ($nbtotaluser) * 100), 1) . '],';
    } else {
        $donneeuserindustriel = $manager->getSingle2("SELECT count(u.idutilisateur) FROM creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND  co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur and co.idcentrale_centrale = ?  and u.idqualitedemandeurindust_qualitedemandeurindust is not null", $idcentrale);
        $donneeuseracaexterne = $manager->getSingle2("select count(u.idutilisateur)  FROM  creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND co.idprojet_projet = p.idprojet 
            AND u.idutilisateur = cr.idutilisateur_utilisateur AND u.idcentrale_centrale is null and u.idqualitedemandeuraca_qualitedemandeuraca is not null and co.idcentrale_centrale = ?", $idcentrale);
        $donneeUser = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND idcentrale_centrale=?", $idcentrale);
        $nbtotaluser = $donneeuseracaexterne + $donneeuserindustriel + $donneeUser;
        $string0.='["' . TXT_ACADEMIQUEINTERNE . '",' . round(($donneeUser / ($nbtotaluser) * 100), 1) . '],';
    }
}
$string3 = '["' . TXT_ACADEMIQUEEXTERNE . '",' . round(($donneeuseracaexterne / ($nbtotaluser) * 100), 1) . '],';
$string4 = '["' . TXT_INDUSTRIEL . '",' . round(($donneeuserindustriel / ($nbtotaluser) * 100), 1) . '],';
$string = substr($string0 . $string3 . $string4, 0, -1);

//.'<br>'.'$string3 '.$string3.'<br>$string4 '.$string4;
if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {
    if ($typeUser == ADMINNATIONNAL) {
        $title = TXT_NBUSERDATECENTRALE;
        $subtitle = TXT_NBUSERCENTRALEYEAR . ' ' . $_GET['anneeuser'] . ': <b>' . $nbuser . '</b>';
    } elseif ($typeUser == ADMINLOCAL) {
        $title = TXT_NBUSERDATECENTRALE;
        $subtitle = TXT_NBUSERCENTRALEYEAR . ' ' . $_GET['anneeuser'] . ': <b>' . $nbuser . '</b>';
    }
} else {
    $subtitle = TXT_NOMBREBUSER . '  <b>' . $nbuser . '</b>';
    $title = TXT_NBUSERDATECENTRALE;
}
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
                renderTo: 'chartNode2',
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
<div id="chartNode2"></div>
