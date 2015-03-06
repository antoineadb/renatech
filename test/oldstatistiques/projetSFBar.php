<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$arraySourcefinancement = $manager->getList("select libellesourcefinancement,idsourcefinancement from sourcefinancement");
$nbsf = count($arraySourcefinancement);
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$nbprojet = 0;
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] != 1) {
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet "
                    . "and EXTRACT(YEAR from dateprojet)=? and idsourcefinancement=?", array($_GET['anneeprojetsf'], $arraySourcefinancement[$i]['idsourcefinancement']));

            $string0.='{value:' . ($i + 1) . ',text:"' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '"},';
            $string1.='.addSeries("' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",[{y: ' . $nbsource . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $nbsource . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbprojet+=$nbsource;
        }
    } else {
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet "
                    . "and idsourcefinancement=?", array($arraySourcefinancement[$i]['idsourcefinancement']));

            $string0.='{value:' . ($i + 1) . ',text:"' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '"},';
            $string1.='.addSeries("' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",[{y: ' . $nbsource . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $nbsource . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbprojet+=$nbsource;
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] != 1) {
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and ps.idsourcefinancement_sourcefinancement=?", array($idcentrale, $_GET['anneeprojetsf'], $arraySourcefinancement[$i]['idsourcefinancement']));
            $string0.='{value:' . ($i + 1) . ',text:"' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '"},';
            $string1.='.addSeries("' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",[{y: ' . $nbsource . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $nbsource . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbprojet+=$nbsource;
        }
    } else {
        for ($i = 0; $i < $nbsf; $i++) {
            $nbsource = $manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and ps.idsourcefinancement_sourcefinancement=?", array($idcentrale, $arraySourcefinancement[$i]['idsourcefinancement']));

            $string0.='{value:' . ($i + 1) . ',text:"' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '"},';
            $string1.='.addSeries("' . $arraySourcefinancement[$i]['libellesourcefinancement'] . '",[{y: ' . $nbsource . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $nbsource . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbprojet+=$nbsource;
        }
    }
}
$string = substr($string0, 0, -1);
$string2 = substr($string1, 0, -1) . ');';
$manager->exeRequete("drop table if exists tmpdate");
$manager->exeRequete("create table tmpdate as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet where EXTRACT(YEAR from dateprojet)>2012;");
$row = $manager->getList("select anneeprojet from tmpdate order by anneeprojet asc");
?>
<table><tr><td>
            <div style="float: left">
                <select  id="anneesf" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellepays',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                         style="width: 250px;margin-left:35px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueSF/<?php echo $lang ?>/' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($row); $i++) {
                                 echo '<option value="' . ($row[$i]['anneeprojet']) . '">' . $row[$i]['anneeprojet'] . '</option>';
                             }
                             echo '<option value="' . 1 . '">' . TXT_TOUS . '</option>';
                             ?>
                </select>
            </div>
            <div style="float: right;margin-left:20px;margin-top:28px;">
                <?php
                if (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] != 1) {
                    $dateselection = $_GET['anneeprojetsf'];
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                } elseif (isset($_GET['anneeprojetsf']) && $_GET['anneeprojetsf'] == 1) {
                    $dateselection = TXT_TOUS;
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                }
                ?></div>
        </td>
    </tr></table>
<div style="width:1000px;text-align:center;font-size:12px;margin-top: 20px;"><?php echo TXT_SOURCEFINANCEMENT . ': <b>' . $nbprojet.'</b>'; ?><br/></div>
<div id="ChartDivSF" style="width:1000px"></div>
<script>
    dojo.require("dojox.charting.Chart2D");
    dojo.require("dojox.charting.plot2d.Columns");
    dojo.require("dojox.charting.themes.Wetland");
    dojo.require("dojox.charting.action2d.Highlight");
    dojo.require("dojox.charting.action2d.Tooltip");
    dojo.require("dojox.charting.themes.CubanShirts");
    dojo.require("dojox.charting.widget.Legend");
    dojo.addOnLoad(function() {
        var c = new dojox.charting.Chart2D("ChartDivSF");
        c.addPlot("default", {type: "Columns", tension: 6, gap: 7})
                .addAxis("x", {labels: [<?php echo $string; ?>], fixLower: "minor", fixUpper: "minor"
                }).addAxis("y", {vertical: true, fixLower: "minor", fixUpper: "minor", min: 0
        }).setTheme(dojox.charting.themes.Wetland)
<?php echo $string2; ?>
        var a1 = new dojox.charting.action2d.Tooltip(c, "default");
        var a2 = new dojox.charting.action2d.Highlight(c, "default");
        c.render();
    });
</script>
<?php
BD::deconnecter();
