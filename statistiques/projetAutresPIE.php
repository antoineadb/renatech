<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$nbtotalprojet = $manager->getSingle("select count(idprojet_projet) from concerne");
$nbprojet = 0;
//TRAITEMENT PAR ANNEE
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojetautres']) && $_GET['anneeprojetautres'] != 1) {
        $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne co where idprojet_projet = idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array($_GET['anneeprojetautres'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? 
and p.idprojet not in(select idprojet_projet from projetpartenaire ) ", array($_GET['anneeprojetautres'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL AND EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? 
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array($_GET['anneeprojetautres'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
        $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
        $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
        $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / ($nbtotalprojet) * 100), 1) . '],';        
        
    } else {
        $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? 
and p.idprojet not in(select idprojet_projet from projetpartenaire ) ", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and idstatutprojet_statutprojet!=? 
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
        $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
        $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
        $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / ($nbtotalprojet) * 100), 1) . '],';
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojetautres']) && $_GET['anneeprojetautres'] != 1) {
        $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne co where idprojet_projet = idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?", array($_GET['anneeprojetautres'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale=? ", array($_GET['anneeprojetautres'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL AND EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?", array($_GET['anneeprojetautres'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
        $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
        $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
        $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / ($nbtotalprojet) * 100), 1) . '],';
    } else {
        $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne co where idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale=? ", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL  and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;        
        $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
        $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
        $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / ($nbtotalprojet) * 100), 1) . '],';
     }
}


$string = substr($string0 . $string3 . $string4, 0, -1); 

//$string = $projetinterne . $projetExogeneExterne . $projetExogeneCollaboratif;
$manager->exeRequete("drop table if exists tmpdateprojetautres");
$manager->exeRequete("create table tmpdateprojetautres as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet order by EXTRACT(YEAR from dateprojet) asc;");
$row = $manager->getList("select anneeprojet from tmpdateprojetautres order by anneeprojet asc");
?>

<?php if (isset($_GET['anneeprojetautres']) && $_GET['anneeprojetautres'] != 1) { ?>
    <div style="width:1000px;text-align:center;font-size:12px;margin-top: 65px;"><?php echo TXT_NBPROJET . ' ' . $_GET['anneeprojetautres'] . ': ' . $nbtotalprojet; ?><br/></div>
<?php } else { ?>
    <div style="width:1000px;text-align:center;font-size:12px;margin-top: 65px;"><?php echo TXT_NBPROJET . ' ' . $nbtotalprojet; ?><br/></div>
<?php } ?>
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
                renderTo: 'ChartDivAutresPie',
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
<div id="ChartDivAutresPie"></div>

