<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$string0 = '';
$string1 = '';
$arrayRessource = $manager->getList("select libelleressource,idressource from ressource");
$totalressource = count($arrayRessource);
$nbprojet = 0;
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] != 1) {
        for ($i = 0; $i < $totalressource; $i++) {
            $nbressource = $manager->getSinglebyArray("SELECT count(idressource) FROM ressource,ressourceprojet,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet "
                    . "and EXTRACT(YEAR from dateprojet)=? and idressource=?", array($_GET['anneeprojetRessource'], $arrayRessource[$i]['idressource']));

            $string0.='{value:' . ($i + 1) . ',text:"' . $arrayRessource[$i]['libelleressource'] . '"},';
            $string1.='.addSeries("' . $arrayRessource[$i]['libelleressource'] . '",[{y: ' . $nbressource . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $nbressource . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbprojet +=$nbressource;
        }
    } else {
        for ($i = 0; $i < $totalressource; $i++) {
            $nbressource = $manager->getSinglebyArray("SELECT count(idressource) FROM ressource,ressourceprojet,projet WHERE idressource_ressource = idressource "
                    . "AND idprojet = idprojet_projet and idressource=?", array($arrayRessource[$i]['idressource']));

            $string0.='{value:' . ($i + 1) . ',text:"' . $arrayRessource[$i]['libelleressource'] . '"},';
            $string1.='.addSeries("' . $arrayRessource[$i]['libelleressource'] . '",[{y: ' . $nbressource . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $nbressource . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbprojet +=$nbressource;
        }
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] != 1) {
        for ($i = 0; $i < $totalressource; $i++) {
            $nbressource = $manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and idressource=?", array($idcentrale, $_GET['anneeprojetRessource'], $arrayRessource[$i]['idressource']));
            $string0.='{value:' . ($i + 1) . ',text:"' . $arrayRessource[$i]['libelleressource'] . '"},';
            $string1.='.addSeries("' . $arrayRessource[$i]['libelleressource'] . '",[{y: ' . $nbressource . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $nbressource . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbprojet +=$nbressource;
        }
    } else {
        for ($i = 0; $i < $totalressource; $i++) {
            $nbressource = $manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and idressource=?", array($idcentrale, $arrayRessource[$i]['idressource']));
            $string0.='{value:' . ($i + 1) . ',text:"' . $arrayRessource[$i]['libelleressource'] . '"},';
            $string1.='.addSeries("' . $arrayRessource[$i]['libelleressource'] . '",[{y: ' . $nbressource . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $nbressource . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbprojet +=$nbressource;
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
            <div style="float: left;margin-left:20px;margin-top:0px;">
                <select  id="anneeRessource" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellepays',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                         style="width: 250px;margin-left:35px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueRessource/<?php echo $lang ?>/' + this.value)" >
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
                if (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] != 1) {
                    $dateselection = $_GET['anneeprojetRessource'];
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                } elseif (isset($_GET['anneeprojetRessource']) && $_GET['anneeprojetRessource'] == 1) {
                    $dateselection = TXT_TOUS;
                    echo TXT_SELECTEDDATE . ' ' . $dateselection;
                }
                ?></div>
        </td>
    </tr></table>
<div style="width:1000px;text-align:center;font-size:12px;margin-top: 40px;"><?php echo TXT_RESSOURCES . ': <b>' . $nbprojet.'</b>'; ?><br/></div>
<div id="ChartDivRESSOUCE" style="width:1000px;"></div>
<script>
    dojo.require("dojox.charting.Chart2D");
    dojo.require("dojox.charting.plot2d.Columns");
    dojo.require("dojox.charting.themes.Wetland");
    dojo.require("dojox.charting.action2d.Highlight");
    dojo.require("dojox.charting.action2d.Tooltip");
    dojo.require("dojox.charting.themes.CubanShirts");
    dojo.require("dojox.charting.widget.Legend");
    dojo.addOnLoad(function() {
        var c = new dojox.charting.Chart2D("ChartDivRESSOUCE");
        c.addPlot("default", {type: "Columns", tension: 6, gap: 7})
                .addAxis("x", {labels: [<?php echo $string; ?>], fixLower: "major", fixUpper: "major"
                }).addAxis("y", {vertical: true, fixLower: "major", fixUpper: "major", min: 0
        }).setTheme(dojox.charting.themes.Wetland)
<?php echo $string2; ?>
        var a1 = new dojox.charting.action2d.Tooltip(c, "default");
        var a2 = new dojox.charting.action2d.Highlight(c, "default");
        c.render();
    });
</script>
<?php
BD::deconnecter();
