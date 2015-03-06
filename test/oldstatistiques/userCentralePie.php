<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
array_push($arraylibellecentrale, array("libellecentrale"=>''.TXT_ACADEMIQUEEXTERNE.''));
array_push($arraylibellecentrale, array("libellecentrale"=>''.TXT_INDUSTRIEL.''));
$datay = array();
$arraylibelle = array();
$string0 = '';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idcentrale= $manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
if ($typeUser == ADMINNATIONNAL) {
    if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {
        $nbtotaluser = $manager->getSingle2("SELECT  count(idutilisateur) FROM utilisateur WHERE   EXTRACT(YEAR from datecreation)=?", $_GET['anneeuser']);
        $donneeuserindustriel = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeurindust_qualitedemandeurindust is not null and EXTRACT(YEAR from datecreation)=?", $_GET['anneeuser']);
        $donneeuseracaexterne = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null and EXTRACT(YEAR from datecreation)=?", $_GET['anneeuser']);
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $donneeUser = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND EXTRACT(YEAR from datecreation)=? and libellecentrale=?", array($_GET['anneeuser'], $arraylibellecentrale[$i]['libellecentrale']));
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            if ($nbtotaluser != 0) {
                $string0.='{y:' . $donneeUser . ',text:"' . $centrale . '",stroke:"black",tooltip:"' . round(($donneeUser / ($nbtotaluser) * 100), 1) . '%"},';
            }
        }
    } else {
        $donneeuserindustriel = $manager->getSingle("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeurindust_qualitedemandeurindust is not null ");
        $donneeuseracaexterne = $manager->getSingle("SELECT count(idutilisateur) FROM  utilisateur  WHERE  idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null");
        for ($i = 0; $i < count($arraylibellecentrale); $i++) {
            $nbtotaluser = $manager->getSingle("select count(idutilisateur) from utilisateur");
            $donneeUser = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND libellecentrale=?", $arraylibellecentrale[$i]['libellecentrale']);
            $centrale = $arraylibellecentrale[$i]['libellecentrale'];
            $string0.='{y:' . $donneeUser . ',text:"' . $centrale . '",stroke:"black",tooltip:"' . round(($donneeUser / ($nbtotaluser) * 100), 1) . '%"},';
        }
    }
}elseif ($typeUser == ADMINLOCAL) {    
    if (isset($_GET['anneeuser']) && $_GET['anneeuser'] != 1) {        
        $donneeuseracaexterne = $manager->getSinglebyArray("select count(u.idutilisateur)  FROM  creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND co.idprojet_projet = p.idprojet 
            AND u.idutilisateur = cr.idutilisateur_utilisateur AND u.idcentrale_centrale is null and u.idqualitedemandeuraca_qualitedemandeuraca is not null and co.idcentrale_centrale = ? 
            and EXTRACT(YEAR from datecreation)=?", array($idcentrale, $_GET['anneeuser']));        
        $donneeuserindustriel = $manager->getSinglebyArray("SELECT count(u.idutilisateur) FROM creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND  co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur and co.idcentrale_centrale = ? AND EXTRACT(YEAR from datecreation)=? and u.idqualitedemandeurindust_qualitedemandeurindust is not null", array($idcentrale, $_GET['anneeuser']));        
        $donneeUser = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND EXTRACT(YEAR from datecreation)=? and idcentrale_centrale=?", array($_GET['anneeuser'],$idcentrale));
        $nbtotaluser =$donneeuseracaexterne+$donneeuserindustriel+$donneeUser;
        $string0='{y:' . $donneeUser . ',text:"' . TXT_ACADEMIQUEINTERNE . '",stroke:"black",tooltip:"' . round(($donneeUser / ($nbtotaluser) * 100), 1) . '%"},';
    } else {
        $donneeuserindustriel = $manager->getSingle2("SELECT count(u.idutilisateur) FROM creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND  co.idprojet_projet = p.idprojet AND u.idutilisateur = cr.idutilisateur_utilisateur and co.idcentrale_centrale = ?  and u.idqualitedemandeurindust_qualitedemandeurindust is not null",$idcentrale);
        $donneeuseracaexterne = $manager->getSingle2("select count(u.idutilisateur)  FROM  creer cr,projet p,concerne co,utilisateur u WHERE p.idprojet = cr.idprojet_projet AND co.idprojet_projet = p.idprojet 
            AND u.idutilisateur = cr.idutilisateur_utilisateur AND u.idcentrale_centrale is null and u.idqualitedemandeuraca_qualitedemandeuraca is not null and co.idcentrale_centrale = ?",$idcentrale);
        $donneeUser = $manager->getSingle2("SELECT count(idutilisateur) FROM  utilisateur, loginpassword,centrale  WHERE idcentrale_centrale = idcentrale AND idlogin = idlogin_loginpassword AND idcentrale_centrale=?", $idcentrale);
        $nbtotaluser =$donneeuseracaexterne+$donneeuserindustriel+$donneeUser;        
        $string0='{y:' . $donneeUser . ',text:"' .  TXT_ACADEMIQUEINTERNE . '",stroke:"black",tooltip:"' . round(($donneeUser / ($nbtotaluser) * 100), 1) . '%"},';
    }
}
$string3='{y:' . $donneeuseracaexterne . ',text:"'.TXT_ACADEMIQUEEXTERNE.'",stroke:"black",tooltip:"' . round(($donneeuseracaexterne / ($nbtotaluser) * 100), 1) . '%"},';
$string4='{y:' . $donneeuserindustriel . ',text:"'.TXT_INDUSTRIEL.'",stroke:"black",tooltip:"' . round(($donneeuserindustriel / ($nbtotaluser) * 100), 1) . '%"},';

$string = substr($string0.$string3.$string4, 0, -1);
?><div style="width:1000px;text-align:center;font-size:15px;margin-top: 65px;"><?php echo '<b>Répartition en %</b>'; ?></div>
<div id="chartNode" style="width:1000px;"></div>
<script>
    require(["dojox/charting/Chart", "dojox/charting/themes/Claro", "dojox/charting/plot2d/Pie", "dojox/charting/action2d/Tooltip", "dojox/charting/action2d/MoveSlice", "dojox/charting/plot2d/Markers",
        "dojox/charting/axis2d/Default", "dojo/domReady!,dojox/charting/widget/Legend"
    ], function(Chart, theme, Pie, Tooltip, MoveSlice, Legend) {
        var chart = new Chart("chartNode");
        chart.setTheme(theme);
        chart.addPlot("default", {type: Pie, markers: true, radius: 120});
        chart.addAxis("x");
        chart.addAxis("y", {min: 5000, max: 30000, vertical: true, fixLower: "major", fixUpper: "major"});
        chart.addSeries("toto", [<?php echo $string; ?>]);
        chart.connectToPlot("default", function(evt) {
            var shape = evt.shape, type = evt.type;
            if (type === "onmouseover") {
                if (!shape.originalFill) {
                    shape.originalFill = shape.fillStyle;
                }
                shape.setFill("pink");
            } else if (type === "onmouseout") {
                shape.setFill(shape.originalFill);
            }
        });
        var tip = new Tooltip(chart, "default");
        var mag = new MoveSlice(chart, "default");
        chart.render();
    });
</script>