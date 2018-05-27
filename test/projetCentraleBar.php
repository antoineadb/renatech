<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$string0 = '';
$string1 = '';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$nbprojet = 0;
$arraystatutprojet = $manager->getList2("select libellestatutprojet,libellestatutprojeten,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet asc", TRANSFERERCENTRALE);
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 1) {
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=?
            AND  EXTRACT(YEAR from dateprojet)=?", array($arraylibellecentrale[$i]['libellecentrale'], $_GET['anneeprojet']));

            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $string0.='{value:' . ($i + 1) . ',text:"' . $centrale . '"},';
            $string1.='.addSeries("' . $arraylibellecentrale[$i]['libellecentrale'] . '",[{y: ' . $donneeProjet . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $donneeProjet . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
            $nbprojet +=$donneeProjet;
        }
    } elseif (isset($_GET['statut']) && $_GET['statut'] != 99) {
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet_projet) FROM  projet,concerne,statutprojet,centrale WHERE idprojet = idprojet_projet AND idstatutprojet_statutprojet = idstatutprojet AND idcentrale_centrale =idcentrale and  libellecentrale=?  and idstatutprojet_statutprojet=?
    ", array($arraylibellecentrale[$i]['libellecentrale'], $_GET['statut']));
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $string0.='{value:' . ($i + 1) . ',text:"' . $centrale . '"},';
            $string1.='.addSeries("' . $arraylibellecentrale[$i]['libellecentrale'] . '",[{y: ' . $donneeProjet . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $donneeProjet . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
            $nbprojet +=$donneeProjet;
        }
    } else {
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeProjet = $manager->getSingle2("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=?
    ", $arraylibellecentrale[$i]['libellecentrale']);
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $string0.='{value:' . ($i + 1) . ',text:"' . $centrale . '"},';
            $string1.='.addSeries("' . $arraylibellecentrale[$i]['libellecentrale'] . '",[{y: ' . $donneeProjet . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $donneeProjet . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
            $nbprojet +=$donneeProjet;
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 1) {
        for ($i = 0; $i < count($arraystatutprojet); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND  idcentrale_centrale=?
            AND  EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet=?", array($idcentrale, $_GET['anneeprojet'], $arraystatutprojet[$i]['idstatutprojet']));
            $string0.='{value:' . ($i + 1) . ',text:"' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '"},';
            $string1.='.addSeries("' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '",[{y: ' . $donneeProjet . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $donneeProjet . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
            $nbprojet +=$donneeProjet;
        }
    } else {
        for ($i = 0; $i < count($arraystatutprojet); $i++) {
            $donneeProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale_centrale=? and idstatutprojet_statutprojet=?", array($idcentrale, $arraystatutprojet[$i]['idstatutprojet']));
            $string0.='{value:' . ($i + 1) . ',text:"' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '"},';
            $string1.='.addSeries("' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '",[{y: ' . $donneeProjet . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $donneeProjet . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
            $nbprojet +=$donneeProjet;
        }
    }
}

$string = substr($string0, 0, -1); //echo $string.'<br>';
$string2 = substr($string1, 0, -1) . ');'; //	echo $string2;exit();
$manager->exeRequete("drop table if exists tmpdateprojet");
$manager->exeRequete("create table tmpdateprojet as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet where EXTRACT(YEAR from dateprojet)>2012 order by EXTRACT(YEAR from dateprojet) asc;");
$row = $manager->getList("select anneeprojet from tmpdateprojet order by anneeprojet asc");
?>
<table>
    <tr>
        <td>
            <select  id="anneeprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'annee',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'" 
                     style="width: 250px;margin-left:35px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueProjet/<?php echo $lang ?>/' + this.value)" >
                         <?php
                         for ($i = 0; $i < count($row); $i++) {
                             echo '<option value="' . ($row[$i]['anneeprojet']) . '">' . $row[$i]['anneeprojet'] . '</option>';
                         }echo '<option value="' . 1 . '">' . TXT_TOUS . '</option>';
                         ?>
            </select>
        </td>
        <?php if ($typeUser == ADMINNATIONNAL) { ?>
            <td style="width: 20px"></td>
            <td>
                <select  id="statutProjet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'statut',value: '',required:false,placeHolder: '<?php echo TXT_SELECTSTATUT; ?>'"                 
                         style="width: 250px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statutProjet/<?php echo $lang ?>/' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($arraystatutprojet); $i++) {
                                 if ($lang == 'fr') {
                                     echo '<option value="' . ($arraystatutprojet[$i]['idstatutprojet']) . '">' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojet'])) . '</option>';
                                 } elseif ($lang == 'en') {
                                     echo '<option value="' . ($arraystatutprojet[$i]['idstatutprojet']) . '">' . stripslashes(str_replace("''", "'", $arraystatutprojet[$i]['libellestatutprojeten'])) . '</option>';
                                 }
                             }echo '<option value="' . 99 . '">' . TXT_TOUS . '</option>';
                             ?>
                </select> 
            </td>
        <?php } ?>
    </tr>
</table>
<?php if (isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 1) { ?>
    <div style="width:1000px;text-align:center;font-size:12px;margin-top: 20px;"><?php echo TXT_NBPROJETSAU . ' ' . $_GET['anneeprojet'] . ': <b>' . $nbprojet.'</b>'; ?><br/></div>
<?php
} elseif (isset($_GET['statut']) && $_GET['statut'] != 99) {
    $nbtotalprojetstatut = $manager->getSingle2("select count(idprojet_projet) from concerne where idstatutprojet_statutprojet=?", $_GET['statut']);
    $libellestatut = $manager->getSingle2("select libellestatutprojet from statutprojet where idstatutprojet=?", $_GET['statut']);
    ?>
    <div style="width:1000px;text-align:center;font-size:12px;margin-top: 20px;"><?php echo TXT_NBPROJET . ' ' . strtolower(stripslashes(str_replace("''", "'", $libellestatut))) . ': <b>' . $nbtotalprojetstatut.'</b>'; ?><br/></div>
<?php } else { ?>
    <div style="width:1000px;text-align:center;font-size:12px;margin-top: 20px;"><?php echo TXT_NBPROJET . ' <b>' . $nbprojet.'</b>'; ?><br/></div>
<?php } ?>
<div id="reportTotalsChartDiv" style="width:1000px;"></div>
<script>    /*
    dojo.require("dojox.charting.Chart2D");
    dojo.require("dojox.charting.plot2d.Columns");
    dojo.require("dojox.charting.themes.Wetland");
    dojo.require("dojox.charting.action2d.Highlight");
    dojo.require("dojox.charting.action2d.Tooltip");
    dojo.require("dojox.charting.themes.CubanShirts");
    dojo.require("dojox.charting.widget.SelectableLegend");
    dojo.require("dojox.charting.widget.Legend");
    dojo.require("dojox.gfx.utils");
    dojo.addOnLoad(function() {
        var c = new dojox.charting.Chart2D("reportTotalsChartDiv");
        c.addPlot("default", {type: "Columns", tension: 6, gap: 7})
                .addAxis("x", {labels: [<?php echo $string; ?>], fixLower: "major", fixUpper: "major"
                }).addAxis("y", {vertical: true, fixLower: "major", fixUpper: "major", min: 0
        }).setTheme(dojox.charting.themes.Wetland)
<?php echo $string2; ?>
        var a1 = new dojox.charting.action2d.Tooltip(c, "default");
        var a2 = new dojox.charting.action2d.Highlight(c, "default");
        c.render();
        
        document.getElementById("syncbutton").onclick = function (e) {
       var svgString = Utils.toSvg(c.surface).results[0];
       document.getElementById("svgchart").innerHTML = svgString;      
    };
    });*/
</script>
<script>
    var mychart;
require([
        "dojo/ready", 
        "dojox/charting/Chart2D", 
        "dojox/charting/plot2d/Columns",
        "dojox/charting/themes/Wetland",
        "dojox/charting/action2d/Highlight",
        "dojox/gfx/utils", 
        "dojox/charting/action2d/Tooltip", 
        "dojox/charting/themes/CubanShirts",
        "dojox/charting/action2d/Highlight", 
        "dojox/charting/widget/SelectableLegend",
        "dojox/charting/widget/Legend", "dojox/charting/themes/Claro"], function (ready, Chart, Utils, Tooltip, Highlight, ClaroTheme) {
  ready(function () {
        mychart = Chart("reportTotalsChartDiv");
        mychart.title = "My Chart";
        mychart.addPlot("default", {type: "Columns", tension: 6, gap: 7,lines: true,areas: false,markers: true});

        mychart.addAxis("x", {vertical: false,labels: [<?php echo $string; ?>], fixLower: "major", fixUpper: "major"});
        mychart.addAxis("y", {vertical: true, fixLower: "major", fixUpper: "major", min: 0});
        mychart.setTheme(dojox.charting.themes.Wetland)
            <?php echo $string2; ?>
      
        
        var a1 = new dojox.charting.action2d.Tooltip(mychart, "default");
        var a2 = new dojox.charting.action2d.Highlight(mychart, "default");
        mychart.render();
        
        document.getElementById("syncbutton").onclick = function (e) {
       var svgString = Utils.toSvg(c.surface).results[0];
       document.getElementById("svgchart").innerHTML = svgString;      
    };
  })});
</script>

<?php BD::deconnecter(); ?>