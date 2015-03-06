<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['annee_nbprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$string0 = '';
$string1 = '';
//$nbprojet = $manager->getSingle("select count(idprojet_projet) from concerne");
$nbprojet = 0;
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);


$nb_jourcentraledate = "SELECT dureeestime FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet and idcentrale=? where periodestime= 1 AND EXTRACT(YEAR from dateprojet)=?;"; //jour
$nb_moiscentraledate = "SELECT dureeestime FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet and idcentrale=? where periodestime	= 2 AND EXTRACT(YEAR from dateprojet)=?;"; //mois
$nb_anneecentraledate = "SELECT dureeestime FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet and idcentrale=? where periodestime = 3 AND EXTRACT(YEAR from dateprojet)=?;"; //annee

$nb_jourcentrale = "SELECT dureeestime FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet and periodestime= 1 and idcentrale=? ;"; //jour
$nb_moiscentrale = "SELECT dureeestime FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet and periodestime= 2 and idcentrale=? ;"; //mois
$nb_anneecentrale = "SELECT dureeestime FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet and periodestime= 3 and idcentrale=?;"; //annee

$nb_jourdate = "SELECT dureeestime FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet  where periodestime= 1 AND EXTRACT(YEAR from dateprojet)=?;"; //jour
$nb_moisdate = "SELECT dureeestime FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet  where periodestime	= 2 AND EXTRACT(YEAR from dateprojet)=?;"; //mois
$nb_anneedate = "SELECT dureeestime FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet where periodestime = 3 AND EXTRACT(YEAR from dateprojet)=?;"; //annee



$totalprojetdatecentrale = "SELECT count(dureeprojet) FROM projet,centrale,concerne WHERE  idcentrale_centrale = idcentrale AND   idprojet_projet = idprojet and idcentrale=? AND EXTRACT(YEAR from dateprojet)=?";

$xaxis = '';
$yaxis = '';


if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['annee_nbprojet']) && $_GET['annee_nbprojet'] != 1 && isset($_GET['centrale']) && $_GET['centrale'] != 99) {//CHOIX CENTRALES+ANNEE
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSinglebyArray($totalprojetdatecentrale, array($arraylibellecentrale[$i]['libellecentrale'], $_GET['annee_nbprojet']));
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $xaxis.= '"' . $centrale . '",';
            $yaxis.=$donneeProjet . ',';
            $nbprojet +=$donneeProjet;
        }
    } elseif (isset($_GET['annee_nbprojet']) && $_GET['annee_nbprojet'] != 1 && isset($_GET['centrale']) && $_GET['centrale'] == 99) {//CHOIX ANNEE + CENTRALE = TOUS
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSingle2($totalprojetcentrale, $_GET['annee_nbprojet']);
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $xaxis.= '"' . $centrale . '",';
            $yaxis.=$donneeProjet . ',';
            $nbprojet +=$donneeProjet;
        }
    } elseif (isset($_GET['annee_nbprojet']) && $_GET['annee_nbprojet'] == 1 && isset($_GET['centrale']) && $_GET['centrale'] != 99) {//CHOIX ANNEE=TOUS + CENTRALE
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSinglebyArray($totalprojetdatecentrale, array($arraylibellecentrale[$i]['libellecentrale'], $_GET['annee_nbprojet']));
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $xaxis.= '"' . $centrale . '",';
            $yaxis.=$donneeProjet . ',';
            $nbprojet +=$donneeProjet;
        }
    } elseif (isset($_GET['annee_nbprojet']) && $_GET['annee_nbprojet'] != 1) {//CHOIX ANNEE
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSingle2($totalprojetcentrale, $_GET['annee_nbprojet']);
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $xaxis.= '"' . $centrale . '",';
            $yaxis.=$donneeProjet . ',';
            $nbprojet +=$donneeProjet;
        }
    } elseif (isset($_GET['centrale']) && $_GET['centrale'] != 99) {//CHOIX CENTRALE ANNEE=TOUS
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSinglebyArray($totalprojetdatecentrale, array($arraylibellecentrale[$i]['libellecentrale'], $_GET['annee_nbprojet']));
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $xaxis.= '"' . $centrale . '",';
            $yaxis.=$donneeProjet . ',';
            $nbprojet +=$donneeProjet;
        }
    } elseif (isset($_GET['centrale']) && $_GET['centrale'] == 99) {//CHOIX CENTRALE ANNEE=TOUS
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSingle($totalprojetanneecentrale);
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $xaxis.= '"' . $centrale . '",';
            $yaxis.=$donneeProjet . ',';
            $nbprojet +=$donneeProjet;
        }
    } else {

        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjetjour = 0;
            $donneeProjetmois = 0;
            $donneeProjetannee = 0;
            $arraydonneeProjetjour = $manager->getList2($nb_jourcentrale, $arraylibellecentrale[$i]['idcentrale']);
            for ($j = 0; $j < count($arraydonneeProjetjour); $j++) {
                $donneeProjetjour+=$arraydonneeProjetjour[$j]['dureeestime'];
            }
            $arraydonneeProjetmois = $manager->getList2($nb_moiscentrale, $arraylibellecentrale[$i]['idcentrale']);
            for ($k = 0; $k < count($arraydonneeProjetmois); $k++) {
                $donneeProjetmois+=$arraydonneeProjetmois[$k]['dureeestime'];
            }
            $arraydonneeProjetannee = $manager->getList2($nb_anneecentrale, $arraylibellecentrale[$i]['idcentrale']);
            for ($l = 0; $l < count($arraydonneeProjetannee); $l++) {
                $donneeProjetannee+=$arraydonneeProjetannee[$l]['dureeestime'];
            }
            $donneeProjet = $donneeProjetmois + ($donneeProjetannee * 12) + ($donneeProjetjour / 30);
            $xaxis.= '"' . $arraylibellecentrale[$i]['libellecentrale'] . '",';
            $yaxis.=$donneeProjet . ',';
            $nbprojet +=$donneeProjet;
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['annee_nbprojet']) && $_GET['annee_nbprojet'] != 1) {
        
    } else {
        
    }
}
$xaxis1 = '[' . substr($xaxis, 0, -1) . ']';
$yaxis1 = '[' . substr($yaxis, 0, -1) . ']';



if ($typeUser == ADMINLOCAL) {
    $xaxis = '[' . substr($string0, 0, -1) . ']';
    $yaxis = '[' . substr($string1, 0, -1) . ']';
} elseif ($typeUser == ADMINNATIONNAL) {
    $xaxis = '[' . substr($string0, 0, -1) . ']';
    $yaxis = '[' . substr($string1, 0, -1) . ']';
}
$title = TXT_DUREEREELPROJET;

if (isset($_GET['annee_nbprojet']) && $_GET['annee_nbprojet'] != 1 && isset($_GET['centrale']) && !empty($_GET['centrale'])) {
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_GET['centrale']);
    $subtitle = TXT_NBPROJETALA . ' <b>' . $libellecentrale . '</b> ' . TXT_POURLANNEE . ' ' . $_GET['annee_nbprojet'] . ': <b>' . $nbprojet . '</b>';
} elseif (isset($_GET['annee_nbprojet']) && $_GET['annee_nbprojet'] == 1 && isset($_GET['centrale']) && !empty($_GET['centrale'])) {//CHOIX TOUS POUR L'ANNEE
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_GET['centrale']);
    $subtitle = TXT_NBPROJETALA . ' <b>' . $libellecentrale . '</b> ' . ': <b>' . $nbprojet . '</b>';
} elseif (isset($_GET['annee_nbprojet']) && $_GET['annee_nbprojet'] != 1) {
    if ($typeUser == ADMINNATIONNAL) {
        $subtitle = TXT_NBPROJETSAU . ' ' . $_GET['annee_nbprojet'] . ': <b>' . $nbprojet . '</b>';
    } elseif ($typeUser == ADMINLOCAL) {
        $subtitle = TXT_NBPROJET . ' ' . TXT_POURLANNEE . ' ' . $_GET['annee_nbprojet'] . ': <b>' . $nbprojet . '</b>';
    }
} elseif (isset($_GET['centrale']) && isset($_GET['l']) && $_GET['centrale'] != 99) {
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_GET['centrale']);
    $subtitle = TXT_NBPROJETALA . ' <b>' . $libellecentrale . '</b> ' . ': <b>' . $nbprojet . '</b>';
} else {
    $subtitle = TXT_DUREETOTALPROJET . '  <b>' . $nbprojet . '</b>';
}
$manager->exeRequete("drop table if exists tmpdateprojettypeprojet");
$manager->exeRequete("create table tmpdateprojettypeprojet as select distinct  EXTRACT(YEAR from dateprojet)as annee_nbprojet  from projet where EXTRACT(YEAR from dateprojet) >2012 order by EXTRACT(YEAR from dateprojet) asc;");
$row = $manager->getList("select annee_nbprojet from tmpdateprojettypeprojet order by annee_nbprojet asc");
$rowcentrale = $manager->getList("select idcentrale,libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
?>
<table>
    <tr>
    <td>
        <select  id="annee_nbprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'annee',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                 style="width: 250px;margin-left:10px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiquenbProjet/<?php echo $lang ?>/' + this.value)" >
                     <?php
                     for ($i = 0; $i < count($row); $i++) {
                         echo '<option value="' . ($row[$i]['annee_nbprojet']) . '">' . $row[$i]['annee_nbprojet'] . '</option>';
                     }echo '<option value="' . 1 . '">' . TXT_TOUS . '</option>';
                     ?>
        </select>
    </td>
</tr>

<?php if (isset($_GET['annee_nbprojet']) && !empty($_GET['annee_nbprojet']) && $typeUser == ADMINNATIONNAL) { ?>
    <tr>
    <td>
        <select  id="centraleprojettypeprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'centrale',value: '',required:false,placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'" 
                 style="width: 250px;margin-left:10px" 
                 onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiquenbProjet/<?php echo $lang . '/' . $_GET['annee_nbprojet'] . '/'; ?>' + this.value)" >
                     <?php
                     for ($i = 0; $i < count($rowcentrale); $i++) {
                         echo '<option value="' . ($rowcentrale[$i]['idcentrale']) . '">' . $rowcentrale[$i]['libellecentrale'] . '</option>';
                     }echo '<option value="' . 99 . '">' . TXT_TOUS . '</option>';
                     ?>
        </select>
    </td>
    </tr>
<?php } elseif ($typeUser == ADMINNATIONNAL) { ?>
    <td>
        <select  id="centraleprojettypeprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'centrale',value: '',required:false,placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                 style="width: 250px;margin-left:10px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiquenbProjet/<?php echo $lang ?>' + this.value)" >
                     <?php
                     for ($i = 0; $i < count($rowcentrale); $i++) {
                         echo '<option value="' . ($rowcentrale[$i]['idcentrale']) . '">' . $rowcentrale[$i]['libellecentrale'] . '</option>';
                     }echo '<option value="' . 99 . '">' . TXT_TOUS . '</option>';
                     ?>
        </select>
    </td>
    </tr>
<?php } ?>
</table>
<script>
    $(function () {
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
            title: {
                text: '<?php echo $title; ?>'

            },
            subtitle: {
                text: "<?php echo $subtitle; ?>"
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: <?php echo $xaxis1; ?>
            },
            yAxis: {
                opposite: true,
                title: {
                    text: "<?php echo TXT_NOMBREOCCURRENCE; ?>"
                }
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
                    name: 'valeurs',
                    data: <?php echo $yaxis1; ?>, color: '#338099', showInLegend: false
                }]
        });

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
</script>
<?php
BD::deconnecter();
?>
<div id="container" style="width: 1000px"></div>
<div id="sliders">
    <table>
        <tr><td style="font-size: 0.8em;">Alpha Angle</td><td><input id="R0" type="range" min="0" max="45" value="0"/> <span id="R0-value" class="value"></span></td></tr>
        <tr><td style="font-size: 0.8em;">Beta Angle</td><td><input id="R1" type="range" min="0" max="45" value="0"/> <span id="R1-value" class="value"></span></td></tr>
    </table>
</div>
<hr>
