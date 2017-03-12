<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);


$centraleplusdate="SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=?";
$touscentraleunedate="SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=?";
$tousdateunecentrale="SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=? ";
$touscentraletoutesdate="SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?";
//TRAITEMENT PAR ANNEE
if ($typeUser == ADMINNATIONNAL) {
     if (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1 && isset($_GET['centrale']) && $_GET['centrale']!=99) {//CHOIX CENTRALES+ANNEE
        $nbprojetAcademique = $manager->getSinglebyArray($centraleplusdate,array($_GET['anneeprojettypeprojet'],ACADEMIC,$_GET['centrale']));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($centraleplusdate,array($_GET['anneeprojettypeprojet'],ACADEMICPARTENARIAT,$_GET['centrale']));
	$nbprojetindustriel= $manager->getSinglebyArray($centraleplusdate,array($_GET['anneeprojettypeprojet'],INDUSTRIEL,$_GET['centrale']));
	$formation =$manager->getSinglebyArray($centraleplusdate,array($_GET['anneeprojettypeprojet'],FORMATION,$_GET['centrale']));
    }elseif (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1 && isset($_GET['centrale']) && $_GET['centrale']==99) {//CHOIX ANNEE + CENTRALE = TOUS
        $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],ACADEMIC));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],ACADEMICPARTENARIAT));
	$nbprojetindustriel= $manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],INDUSTRIEL));
	$formation =$manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],FORMATION));	
    }elseif (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] == 1 && isset($_GET['centrale']) &&  $_GET['centrale']!=99) {//CHOIX ANNEE=TOUS + CENTRALE
        $nbprojetAcademique = $manager->getSinglebyArray($tousdateunecentrale,array(ACADEMIC,$_GET['centrale']));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($tousdateunecentrale,array(ACADEMICPARTENARIAT,$_GET['centrale']));
	$nbprojetindustriel= $manager->getSinglebyArray($tousdateunecentrale,array(INDUSTRIEL,$_GET['centrale']));
	$formation =$manager->getSinglebyArray($tousdateunecentrale,array(FORMATION,$_GET['centrale']));
    }elseif(isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1 ) {//CHOIX ANNEE
        $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],ACADEMIC));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],ACADEMICPARTENARIAT));
	$nbprojetindustriel= $manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],INDUSTRIEL));
	$formation =$manager->getSinglebyArray($touscentraleunedate,array($_GET['anneeprojettypeprojet'],FORMATION));
    }elseif(isset($_GET['centrale']) && $_GET['centrale'] != 99 && isset($_GET['l'])) {//CHOIX CENTRALE ANNEE=TOUS
        $nbprojetAcademique = $manager->getSinglebyArray($tousdateunecentrale,array(ACADEMIC,$_GET['centrale']));
	$nbprojetAcademiquepartenariat=$manager->getSinglebyArray($tousdateunecentrale,array(ACADEMICPARTENARIAT,$_GET['centrale']));
	$nbprojetindustriel= $manager->getSinglebyArray($tousdateunecentrale,array(INDUSTRIEL,$_GET['centrale']));
	$formation =$manager->getSinglebyArray($tousdateunecentrale,array(FORMATION,$_GET['centrale']));	
    }elseif(isset($_GET['centrale']) && $_GET['centrale'] == 99 && isset($_GET['l']) ) {//CHOIX CENTRALE ANNEE=TOUS
        $nbprojetAcademique = $manager->getSingle2($touscentraletoutesdate,ACADEMIC);
	$nbprojetAcademiquepartenariat=$manager->getSingle2($touscentraletoutesdate,ACADEMICPARTENARIAT);
	$nbprojetindustriel= $manager->getSingle2($touscentraletoutesdate,INDUSTRIEL);
	$formation =$manager->getSingle2($touscentraletoutesdate,FORMATION);
    }else {        
        $nbprojetAcademique = $manager->getSingle2($touscentraletoutesdate,ACADEMIC);
	$nbprojetAcademiquepartenariat=$manager->getSingle2($touscentraletoutesdate,ACADEMICPARTENARIAT);
	$nbprojetindustriel= $manager->getSingle2($touscentraletoutesdate,INDUSTRIEL);
	$formation =$manager->getSingle2($touscentraletoutesdate,FORMATION);
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 1) {
        $nbprojetAcademique = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=? ",array($_GET['anneeprojettypeprojet'],ACADEMIC,$idcentrale));
        $nbprojetAcademiquepartenariat = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=?",array($_GET['anneeprojettypeprojet'],ACADEMICPARTENARIAT,$idcentrale));
        $nbprojetindustriel = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet AND EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=?",array($_GET['anneeprojettypeprojet'],INDUSTRIEL,$idcentrale));
        $formation = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and  EXTRACT(YEAR from dateprojet)=? and idtypeprojet=? and idcentrale_centrale=?",array($_GET['anneeprojettypeprojet'],FORMATION,$idcentrale));
    } else {
        $nbprojetAcademique = $manager->getSinglebyArray("SELECT count(libelletype) FROM projet,typeprojet,concerne WHERE  idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? and idcentrale_centrale=? ",array(ACADEMIC,$idcentrale));
        $nbprojetAcademiquepartenariat = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and  idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? and idcentrale_centrale=?",array(ACADEMICPARTENARIAT,$idcentrale));
        $nbprojetindustriel = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? and idcentrale_centrale=?",array(INDUSTRIEL,$idcentrale));
        $formation = $manager->getSinglebyArray("SELECT count(libelletype) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and  idtypeprojet=? and idcentrale_centrale=?",array(FORMATION,$idcentrale));
    }
}
$nbtotalprojet = $nbprojetAcademique+$nbprojetAcademiquepartenariat+$nbprojetindustriel+$formation;
$string0 = '["' . TXT_ACADEMIQUE . '",' . round((($nbprojetAcademique / $nbtotalprojet) * 100), 1) . '],';
$string3 = '["' . TXT_ACADEMICPARTENARIAT . '",' . round((($nbprojetAcademiquepartenariat / $nbtotalprojet) * 100), 1) . '],';
$string4 = '["' . TXT_FORMATION . '",' . round(($formation / ($nbtotalprojet) * 100), 1) . '],';
$string5 = '["' . TXT_INDUSTRIEL . '",' . round(($nbprojetindustriel / ($nbtotalprojet) * 100), 1) . '],';
$string = substr($string0 . $string3 . $string4 . $string5, 0, -1);
$manager->exeRequete("drop table if exists tmpdateprojetautres");
$manager->exeRequete("create table tmpdateprojetautres as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet order by EXTRACT(YEAR from dateprojet) asc;");
$row = $manager->getList("select anneeprojet from tmpdateprojetautres order by anneeprojet asc");
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

