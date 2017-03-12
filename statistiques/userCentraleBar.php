<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres'  order by idcentrale asc");
array_push($arraylibellecentrale, array("libellecentrale" => '' . TXT_ACADEMIQUEEXTERNE . ''));
array_push($arraylibellecentrale, array("libellecentrale" => '' . TXT_INDUSTRIEL . ''));
$string0 = '';
$string1 = '';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$nbtotaluser = 0;
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {
        $donneeuserindustriel = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeurindust_qualitedemandeurindust is not null and EXTRACT(YEAR from datecreation)=?", $_GET['anneeuser']);
        $donneeuseracaexterne = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null and EXTRACT(YEAR from datecreation)=?", $_GET['anneeuser']);
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneUser = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND libellecentrale=? and EXTRACT(YEAR from datecreation)=?", array($arraylibellecentrale[$i]['libellecentrale'], $_GET['anneeuser']));
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $string0.='"' . $centrale . '",';
            if($donneUser!=0){
                $string1 .=  $donneUser.',';
            }
            $nbtotaluser+=$donneUser;
        }
        $string3 = $donneeuseracaexterne . ',';
        $string4 = $donneeuserindustriel . ',';
    } else {
        $donneeuserindustriel = $manager->getSingle("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeurindust_qualitedemandeurindust is not null ");
        $donneeuseracaexterne = $manager->getSingle("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null");
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneUser = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND libellecentrale=?", $arraylibellecentrale[$i]['libellecentrale']);
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];            
            $string0.='"' . $centrale . '",';
            if($donneUser!=0){
                $string1 .=  $donneUser.',';
            }
            $nbtotaluser+=$donneUser;
        }
        $string3 = $donneeuseracaexterne . ',';
        $string4 = $donneeuserindustriel . ',';
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {
        $donneeuseracaexterne = $manager->getSinglebyArray("select count(u.idutilisateur)  FROM  creer cr,projet p,concerne co,utilisateur u
WHERE p.idprojet = cr.idprojet_projet AND co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND u.idcentrale_centrale is null
and u.idqualitedemandeuraca_qualitedemandeuraca is not null and co.idcentrale_centrale = ? and EXTRACT(YEAR from datecreation)=?", array($idcentrale, $_GET['anneeuser']));
        $donneeuserindustriel = $manager->getSinglebyArray("SELECT count(u.idutilisateur) FROM creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND  co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur and co.idcentrale_centrale = ? AND EXTRACT(YEAR from datecreation)=? and u.idqualitedemandeurindust_qualitedemandeurindust is not null", array($idcentrale, $_GET['anneeuser']));
        $donneUser = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND EXTRACT(YEAR from datecreation)=? and idcentrale_centrale=?", array($_GET['anneeuser'], $idcentrale));
        $string0 = '"' .TXT_ACADEMIQUEINTERNE . '",' .'"'. TXT_ACADEMIQUEEXTERNE . '",' .'"'.TXT_INDUSTRIEL . '",';
        $string1 .=  $donneUser.','.$donneeuseracaexterne.','.$donneeuserindustriel.',' ;
    } else {
        $donneeuseracaexterne = $manager->getSingle2("select count(u.idutilisateur)  FROM  creer cr,projet p,concerne co,utilisateur u
WHERE p.idprojet = cr.idprojet_projet AND co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND u.idcentrale_centrale is null
and u.idqualitedemandeuraca_qualitedemandeuraca is not null and co.idcentrale_centrale = ?", $idcentrale);
        $donneeuserindustriel = $manager->getSingle2("SELECT count(u.idutilisateur) FROM creer cr,projet p,concerne co,utilisateur u
WHERE p.idprojet = cr.idprojet_projet AND  co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur and co.idcentrale_centrale = ? and u.idqualitedemandeurindust_qualitedemandeurindust is not null", $idcentrale);
        $donneUser = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND idcentrale_centrale=?", $idcentrale);
        $string0 = '"' .TXT_ACADEMIQUEINTERNE . '",' .'"'. TXT_ACADEMIQUEEXTERNE . '",' .'"'.TXT_INDUSTRIEL . '",';
        $string1 .=  $donneUser.','.$donneeuseracaexterne.','.$donneeuserindustriel.',' ;
    }
}
$nbuser = $donneUser + $donneeuseracaexterne + $donneeuserindustriel + $nbtotaluser;
if($typeUser==ADMINLOCAL){
    $xaxis = '['.substr($string0, 0, -1).']';
    $yaxis ='['. substr($string1, 0, -1).']';
}elseif($typeUser==ADMINNATIONNAL){
    $xaxis = '['.substr($string0, 0, -1).']';
    $yaxis ='['. substr($string1.$string3.$string4, 0, -1).']';
}
$manager->exeRequete("drop table if exists tmpdate");
$manager->exeRequete("create table tmpdate as select distinct  EXTRACT(YEAR from datecreation)as anneeuser  from utilisateur;");
$row = $manager->getList("select anneeuser from tmpdate order by anneeuser asc");

if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {
    if($typeUser == ADMINNATIONNAL){
        $title=TXT_NBUSERDATECENTRALE;
        $subtitle= TXT_NBUSERCENTRALEYEAR . ' ' . $_GET['anneeuser'] . ': <b>' . $nbuser.'</b>';    
    }elseif ($typeUser == ADMINLOCAL) {
        $title=TXT_NBUSERDATECENTRALE;
        $subtitle= TXT_NBUSERCENTRALEYEAR . ' ' . $_GET['anneeuser'] . ': <b>' . $nbuser.'</b>';
    }

}else {  
    $subtitle= TXT_NOMBREBUSER . '  <b>' . $nbuser.'</b>';     
    $title=TXT_NBUSERDATECENTRALE;
}


?>
<table><tr><td>
            <div style="float: left">
                <select  id="anneeuser" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellepays',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                         style="width: 250px;margin-left:35px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueUser/<?php echo $lang ?>/' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($row); $i++) {
                                 echo '<option value="' . ($row[$i]['anneeuser']) . '">' . $row[$i]['anneeuser'] . '</option>';
                             }
                             echo '<option value="1">' . TXT_TOUS . '</option>';
                             ?>
                </select>
            </div>
            <div style="float: right;margin-left:20px;margin-top:28px;">
                <?php
                if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {
                    $dateselection = $_GET['anneeuser'];
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                } elseif (isset($_GET['anneeuser']) && $_GET['anneeuser'] == 1) {
                    $dateselection = TXT_TOUS;
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                }
                ?></div>
        </td>
    </tr></table>
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
                },
                    
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

