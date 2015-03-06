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
            $string0.='{value:' . ($i + 1) . ',text:"' . $centrale . '"},';
            $string1.='.addSeries("' . $arraylibellecentrale[$i]['libellecentrale'] . '",[{y: ' . $donneUser . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $donneUser . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbtotaluser+=$donneUser;
        }
        $string3 = '.addSeries("' . TXT_ACADEMIQUEEXTERNE . '",[{y: ' . $donneeuseracaexterne . ', x: ' . (count($arraylibellecentrale) - 1) . ', tooltip: "" + ' . $donneeuseracaexterne . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
        $string4 = '.addSeries("' . TXT_INDUSTRIEL . '",[{y: ' . $donneeuserindustriel . ', x: ' . (count($arraylibellecentrale)) . ', tooltip: "" + ' . $donneeuserindustriel . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
    } else {
        $donneeuserindustriel = $manager->getSingle("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeurindust_qualitedemandeurindust is not null ");
        $donneeuseracaexterne = $manager->getSingle("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null");
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneUser = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND libellecentrale=?", $arraylibellecentrale[$i]['libellecentrale']);
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $string0.='{value:' . ($i + 1) . ',text:"' . $centrale . '"},';
            $string1.='.addSeries("' . $arraylibellecentrale[$i]['libellecentrale'] . '",[{y: ' . $donneUser . ', x: ' . ($i + 1) . ', tooltip: "" + ' . $donneUser . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
            $nbtotaluser+=$donneUser;
        }
        $string3 = '.addSeries("' . TXT_ACADEMIQUEEXTERNE . '",[{y: ' . $donneeuseracaexterne . ', x: ' . (count($arraylibellecentrale) - 1) . ', tooltip: "" + ' . $donneeuseracaexterne . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
        $string4 = '.addSeries("' . TXT_INDUSTRIEL . '",[{y: ' . $donneeuserindustriel . ', x: ' . (count($arraylibellecentrale)) . ', tooltip: "" + ' . $donneeuserindustriel . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
    }
} elseif ($typeUser == ADMINLOCAL) {
    if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {
        $donneeuseracaexterne = $manager->getSinglebyArray("select count(u.idutilisateur)  FROM  creer cr,projet p,concerne co,utilisateur u
WHERE p.idprojet = cr.idprojet_projet AND co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND u.idcentrale_centrale is null
and u.idqualitedemandeuraca_qualitedemandeuraca is not null and co.idcentrale_centrale = ? and EXTRACT(YEAR from datecreation)=?", array($idcentrale, $_GET['anneeuser']));
        $donneeuserindustriel = $manager->getSinglebyArray("SELECT count(u.idutilisateur) FROM creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND  co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur and co.idcentrale_centrale = ? AND EXTRACT(YEAR from datecreation)=? and u.idqualitedemandeurindust_qualitedemandeurindust is not null", array($idcentrale, $_GET['anneeuser']));
        $donneUser = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND EXTRACT(YEAR from datecreation)=? and idcentrale_centrale=?", array($_GET['anneeuser'], $idcentrale));
        $string0 = '{value:1,text:"' . TXT_ACADEMIQUEINTERNE . '"},{value:2,text:"' . TXT_ACADEMIQUEEXTERNE . '"},{value:3,text:"' . TXT_INDUSTRIEL . '"},';
        $string1 = '.addSeries("' . TXT_ACADEMIQUEINTERNE . '",[{y: ' . $donneUser . ', x:1 , tooltip: "" + ' . $donneUser . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
        $string3 = '.addSeries("' . TXT_ACADEMIQUEEXTERNE . '",[{y: ' . $donneeuseracaexterne . ', x: 2, tooltip: "" + ' . $donneeuseracaexterne . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
        $string4 = '.addSeries("' . TXT_INDUSTRIEL . '",[{y: ' . $donneeuserindustriel . ', x: 3, tooltip: "" + ' . $donneeuserindustriel . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
    } else {
        $donneeuseracaexterne = $manager->getSingle2("select count(u.idutilisateur)  FROM  creer cr,projet p,concerne co,utilisateur u
WHERE p.idprojet = cr.idprojet_projet AND co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur AND u.idcentrale_centrale is null
and u.idqualitedemandeuraca_qualitedemandeuraca is not null and co.idcentrale_centrale = ?", $idcentrale);
        $donneeuserindustriel = $manager->getSingle2("SELECT count(u.idutilisateur) FROM creer cr,projet p,concerne co,utilisateur u
WHERE p.idprojet = cr.idprojet_projet AND  co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur and co.idcentrale_centrale = ? and u.idqualitedemandeurindust_qualitedemandeurindust is not null", $idcentrale);
        $donneUser = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND idcentrale_centrale=?", $idcentrale);
        $string0 = '{value:1,text:"' . TXT_ACADEMIQUEINTERNE . '"},{value:2,text:"' . TXT_ACADEMIQUEEXTERNE . '"},{value:3,text:"' . TXT_INDUSTRIEL . '"},';
        $string1 = '.addSeries("' . TXT_ACADEMIQUEINTERNE . '",[{y: ' . $donneUser . ', x:1 , tooltip: "" + ' . $donneUser . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
        $string3 = '.addSeries("' . TXT_ACADEMIQUEEXTERNE . '",[{y: ' . $donneeuseracaexterne . ', x: 2, tooltip: "" + ' . $donneeuseracaexterne . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
        $string4 = '.addSeries("' . TXT_INDUSTRIEL . '",[{y: ' . $donneeuserindustriel . ', x: 3, tooltip: "" + ' . $donneeuserindustriel . ', stroke: {color: "", width: 5}, fill: "#11497C"}])';
    }
}
$nbuser = $donneUser + $donneeuseracaexterne + $donneeuserindustriel + $nbtotaluser;
$string = substr($string0, 0, -1);
$string2 = substr($string1 . $string3 . $string4, 0, -1) . ');';
$manager->exeRequete("drop table if exists tmpdate");
$manager->exeRequete("create table tmpdate as select distinct  EXTRACT(YEAR from datecreation)as anneeuser  from utilisateur;");
$row = $manager->getList("select anneeuser from tmpdate order by anneeuser asc");
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
<div style="width:1000px;text-align:center;font-size:12px;margin-top: 20px;">
    <?php echo TXT_NOMBREBUSER; ?>	<?php echo '<b>' . $nbuser . '</b>'; ?><br/></div>
<div id="ChartDiv" style="width:1000px;"></div>
<script>
    dojo.require("dojox.charting.Chart2D");
    dojo.require("dojox.charting.plot2d.Columns");
    dojo.require("dojox.charting.themes.Wetland");
    dojo.require("dojox.charting.action2d.Highlight");
    dojo.require("dojox.charting.action2d.Tooltip");
    dojo.require("dojox.charting.themes.CubanShirts");
    dojo.require("dojox.charting.widget.SelectableLegend");
    dojo.require("dojox.charting.widget.Legend");
    dojo.addOnLoad(function() {
        var c = new dojox.charting.Chart2D("ChartDiv");
        c.addPlot("default", {type: "Columns", tension: 6, gap: 7})
                .addAxis("x", {labels: [<?php echo $string; ?>], fixLower: "minor", fixUpper: "minor"
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
