<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$datay = array();
$arraylibelle = array();
$string0 = '';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$arraystatutprojet = $manager->getList2("select libellestatutprojet,libellestatutprojeten,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet asc", TRANSFERERCENTRALE);
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 1) {
        $nbtotalprojet = $manager->getSingle2("SELECT count(idprojet_projet) FROM projet,concerne WHERE idprojet = idprojet_projet and EXTRACT(YEAR from dateprojet)=?", $_GET['anneeprojet']);
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet_projet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=? and EXTRACT(YEAR from dateprojet)=?
    ", array($arraylibellecentrale[$i]['libellecentrale'], $_GET['anneeprojet']));
            $centrale = stripslashes(str_replace("''", "'",$arraylibellecentrale[$i]['libellecentrale']));
            if ($nbtotalprojet != 0) {
                $string0.='["'. $centrale.'",'.round((($donneeProjet / $nbtotalprojet) * 100), 1).'],';//$string0.='{y:' . $donneeProjet . ',text:"' . $centrale . '",stroke:"black",tooltip:"' . round((($donneeProjet / $nbtotalprojet) * 100), 1) . '%"},';
            }
        }
    } elseif (isset($_GET['statut']) && $_GET['statut'] != 99) {
        $nbtotalprojet = $manager->getSingle2("SELECT count(idprojet_projet) FROM projet,concerne WHERE idprojet = idprojet_projet and idstatutprojet_statutprojet=?", $_GET['statut']);
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=? and idstatutprojet_statutprojet=?
    ", array($arraylibellecentrale[$i]['libellecentrale'], $_GET['statut']));
            $centrale = stripslashes(str_replace("''", "'",$arraylibellecentrale[$i]['libellecentrale']));
            if ($nbtotalprojet != 0) {
                $string0.='["'.$centrale.'",'.round((($donneeProjet / $nbtotalprojet) * 100), 1).'],';
            }
        }
    } else {
        $nbtotalprojet = $manager->getSingle("select count(idprojet_projet) from concerne");
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSingle2("SELECT count(idprojet_projet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=?
    ", $arraylibellecentrale[$i]['libellecentrale']);
            $centrale = stripslashes(str_replace("''", "'",$arraylibellecentrale[$i]['libellecentrale']));
            if ($nbtotalprojet != 0) {
                $string0.='["'.$centrale.'",'.round((($donneeProjet / $nbtotalprojet) * 100), 1).'],';
            }
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 1) {
        $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet_projet) FROM projet,concerne WHERE idprojet = idprojet_projet and EXTRACT(YEAR from dateprojet)=? and idcentrale_centrale=?", array($_GET['anneeprojet'], $idcentrale));
        for ($i = 0; $i < count($arraystatutprojet); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet_projet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet=?
    ", array($idcentrale, $_GET['anneeprojet'], $arraystatutprojet[$i]['idstatutprojet']));
            if ($nbtotalprojet != 0) {
                if($lang=='fr'){
                    $string0.='["'.stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])).'",'.round((($donneeProjet / $nbtotalprojet) * 100), 1).'],';
                }elseif($lang=='en'){
                    $string0.='["'.stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojeten'])).'",'.round((($donneeProjet / $nbtotalprojet) * 100), 1).'],';
                }
            }
        }
    } else {
        $nbtotalprojet = $manager->getSingle2("SELECT count(idprojet_projet) FROM projet,concerne WHERE idprojet = idprojet_projet and idcentrale_centrale=?", $idcentrale);
        for ($i = 0; $i < count($arraystatutprojet); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet_projet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale_centrale=? and idstatutprojet_statutprojet=?
    ", array($idcentrale, $arraystatutprojet[$i]['idstatutprojet']));
            if ($nbtotalprojet != 0) {
                if($lang=='fr'){
                    $string0.='["'.stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])).'",'.round((($donneeProjet / $nbtotalprojet) * 100), 1).'],';
                }elseif($lang=='en'){
                    $string0.='["'.stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojeten'])).'",'.round((($donneeProjet / $nbtotalprojet) * 100), 1).'],';
                }
            }
        }
    }
}
$string = substr($string0, 0, -1);
 if (isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 1) {
    if($typeUser == ADMINNATIONNAL){
        $title=TXT_PROJETPARDATECENTRALE;
        $subtitle= TXT_NBPROJETSAU . ' ' . $_GET['anneeprojet'] . ': <b>' . $nbprojet.'</b>';    
    }elseif ($typeUser == ADMINLOCAL) {
        $title=TXT_PROJETPARDATETYPE;
        $subtitle= TXT_NBPROJETSALA . ' ' . $_GET['anneeprojet'] . ': <b>' . $nbprojet.'</b>';
    }

} elseif (isset($_GET['statut']) && $_GET['statut'] != 99) {    
    $nbtotalprojetstatut = $manager->getSingle2("select count(idprojet_projet) from concerne where idstatutprojet_statutprojet=?", $_GET['statut']);
    $libellestatut = $manager->getSingle2("select libellestatutprojet from statutprojet where idstatutprojet=?", $_GET['statut']);
    $subtitle= TXT_NBPROJET . ' ' . strtolower(stripslashes(str_replace("''", "'", $libellestatut))) . ': <b>' . $nbtotalprojetstatut.'</b>';
    $title=TXT_PROJETPARDATECENTRALE;
} else {  
    if($typeUser == ADMINNATIONNAL){
        $title=TXT_PROJETPARDATECENTRALE;
    }elseif ($typeUser == ADMINLOCAL) {
        $title=TXT_PROJETPARDATETYPE;
    }        
    $subtitle= TXT_NBPROJET . ' <b>' . $nbprojet.'</b>'; 
} ?> 
<script type='text/javascript'>//<![CDATA[ 
    $(function () {
        Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.5).get('rgb')] // darken
            ]
        };
    });
        
    //$('#chartNode2').highcharts({
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
    <script src="<?php echo '/'.REPERTOIRE; ?>/js/grid-light.js"></script>
    <div id="chartNode2"></div>