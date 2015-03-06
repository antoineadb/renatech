<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojetautres']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$string0 = '';
$string1 = '';
$nbtotalprojet = $manager->getSingle("select count(idprojet_projet) from concerne");
$nbprojet = 0;
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
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
        $string0 = '"' .TXT_PROJETINTERNE . '",' .'"'. TXT_PROJETEXOEXTERNE . '",' .'"'.TXT_PROJETEXOCOLLABORATIF . '",';
        $string1 .=  $nbprojetInterne.','.$nbprojetExogeneExterne.','.$nbprojetExogeneCollaboratif.',' ;        
    } else {
        $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? 
and p.idprojet not in(select idprojet_projet from projetpartenaire ) ", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and idstatutprojet_statutprojet!=? 
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
        $string0 = '"' .TXT_PROJETINTERNE . '",' .'"'. TXT_PROJETEXOEXTERNE . '",' .'"'.TXT_PROJETEXOCOLLABORATIF . '",';
        $string1 .=  $nbprojetInterne.','.$nbprojetExogeneExterne.','.$nbprojetExogeneCollaboratif.',' ;
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojetautres']) && $_GET['anneeprojetautres'] != 1) {
        $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne co where idprojet_projet = idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?", array($_GET['anneeprojetautres'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale=?", array($_GET['anneeprojetautres'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL AND EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?", array($_GET['anneeprojetautres'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
        $string0 = '"' .TXT_PROJETINTERNE . '",' .'"'. TXT_PROJETEXOEXTERNE . '",' .'"'.TXT_PROJETEXOCOLLABORATIF . '",';
        $string1 .=  $nbprojetInterne.','.$nbprojetExogeneExterne.','.$nbprojetExogeneCollaboratif.',' ;
    } else {
        $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne co where idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and idcentrale_centrale=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $idcentrale));
        $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
        $string0 = '"' .TXT_PROJETINTERNE . '",' .'"'. TXT_PROJETEXOEXTERNE . '",' .'"'.TXT_PROJETEXOCOLLABORATIF . '",';
        $string1 .=  $nbprojetInterne.','.$nbprojetExogeneExterne.','.$nbprojetExogeneCollaboratif.',' ;        
    }
}
if($typeUser==ADMINLOCAL){
    $xaxis = '['.substr($string0, 0, -1).']';
    $yaxis ='['. substr($string1, 0, -1).']';
}elseif($typeUser==ADMINNATIONNAL){
    $xaxis = '['.substr($string0, 0, -1).']';
    $yaxis ='['. substr($string1, 0, -1).']';
}

if (isset($_GET['anneeprojetautres']) && $_GET['anneeprojetautres'] != 1) {
    if($typeUser == ADMINNATIONNAL){
        $title=TXT_PROJETPARDATETYPE;
        $subtitle= TXT_NBPROJETSAU . ' ' . $_GET['anneeprojetautres'] . ': <b>' . $nbtotalprojet.'</b>';    
    }elseif ($typeUser == ADMINLOCAL) {
        $title=TXT_PROJETPARDATETYPE;
        $subtitle= TXT_NBPROJETSAU . ' ' . $_GET['anneeprojetautres'] . ': <b>' . $nbtotalprojet.'</b>';
    }
}else {  
    $subtitle= TXT_NBPROJET . '  <b>' . $nbtotalprojet.'</b>';     
    $title=TXT_PROJETPARDATETYPE;
} 


$manager->exeRequete("drop table if exists tmpdateprojetautrebar");
$manager->exeRequete("create table tmpdateprojetautrebar as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet where EXTRACT(YEAR from dateprojet) >2012 order by EXTRACT(YEAR from dateprojet) asc;");
$row = $manager->getList("select anneeprojet from tmpdateprojetautrebar order by anneeprojet asc");
?>
<table>
    <tr>
        <td>
            <select  id="anneeprojetautresBar" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'annee',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                     style="width: 250px;margin-left:50px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueProjetAutres/<?php echo $lang ?>/' + this.value)" >
                         <?php
                         for ($i = 0; $i < count($row); $i++) {
                             echo '<option value="' . ($row[$i]['anneeprojet']) . '">' . $row[$i]['anneeprojet'] . '</option>';
                         }echo '<option value="' . 1 . '">' . TXT_TOUS . '</option>';
                         ?>
            </select>
        </td>
    </tr>
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
            categories:  <?php  echo $xaxis; ?>
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
            data: <?php echo $yaxis ;?>, color:'#338099',showInLegend: false 
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
