<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");

if (isset($_GET['idcentrale']) && $_GET['idcentrale'] != 99) {    
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet WHERE  idprojet_projet = idprojet AND idcentrale_centrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?",array($_GET['idcentrale'],ENATTENTE,ENCOURSANALYSE,ACCEPTE,REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?",array($_GET['idcentrale'],ENATTENTE,ENCOURSANALYSE,ACCEPTE,REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet and co.idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?",array($_GET['idcentrale'],ENATTENTE,ENCOURSANALYSE,ACCEPTE,REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;    
    
    $projetinterne='{y:' . $nbprojetInterne . ',text:"' . TXT_PROJETINTERNE .'<br>('.$nbprojetInterne.')'. '",stroke:"black",tooltip:"' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '%"},';
    $projetExogeneExterne='{y:' . $nbprojetExogeneExterne . ',text:"' . TXT_PROJETEXOEXTERNE .'<br>('.$nbprojetExogeneExterne.')'. '",stroke:"black",tooltip:"' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '%"},';
    $projetExogeneCollaboratif='{y:' . $nbprojetExogeneCollaboratif . ',text:"' . TXT_PROJETEXOCOLLABORATIF . '<br>('.$nbprojetExogeneCollaboratif.')'. '",stroke:"black",tooltip:"' . round((($nbprojetExogeneCollaboratif / $nbtotalprojet) * 100), 1) . '%"}';
 
}else{
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?",array(ENATTENTE,ENCOURSANALYSE,ACCEPTE,REFUSE));
   $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?",array(ENATTENTE,ENCOURSANALYSE,ACCEPTE,REFUSE)); 
$nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?",array(ENATTENTE,ENCOURSANALYSE,ACCEPTE,REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = '.addSeries("' . TXT_PROJETEXOEXTERNE. '",[{y: ' . $nbprojetExogeneExterne . ', x:2 , tooltip: "" + ' . $nbprojetExogeneExterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
    $projetExogeneCollaboratif = '.addSeries("' . TXT_PROJETEXOCOLLABORATIF. '",[{y: ' . $nbprojetExogeneCollaboratif . ', x:3 , tooltip: "" + ' . $nbprojetExogeneCollaboratif . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
    $projetInterne = '.addSeries("' . TXT_PROJETINTERNE. '",[{y: ' . $nbprojetInterne . ', x:1 , tooltip: "" + ' . $nbprojetInterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
    
    $projetinterne='{y:' . $nbprojetInterne . ',text:"' . TXT_PROJETINTERNE . '<br>('.$nbprojetInterne.')'.'",stroke:"black",tooltip:"' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '%"},';
    $projetExogeneExterne='{y:' . $nbprojetExogeneExterne . ',text:"' . TXT_PROJETEXOEXTERNE .'<br>('.$nbprojetExogeneExterne.')'. '",stroke:"black",tooltip:"' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '%"},';
    $projetExogeneCollaboratif='{y:' . $nbprojetExogeneCollaboratif . ',text:"' . TXT_PROJETEXOCOLLABORATIF .'<br>('.$nbprojetExogeneCollaboratif.')'. '",stroke:"black",tooltip:"' . round((($nbprojetExogeneCollaboratif / $nbtotalprojet) * 100), 1) . '%"}';
}
$string = $projetinterne.$projetExogeneExterne.$projetExogeneCollaboratif;
$manager->exeRequete("drop table if exists tmpdateprojetautres");
$manager->exeRequete("create table tmpdateprojetautres as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet order by EXTRACT(YEAR from dateprojet) asc;");
$row = $manager->getList("select anneeprojet from tmpdateprojetautres order by anneeprojet asc");
?>

<?php if (isset($_GET['anneeprojetautres']) && $_GET['anneeprojetautres'] != 1) { ?>
<div style="width:1000px;text-align:center;font-size:12px;margin-top: 65px;"><?php echo TXT_NBPROJET . ' ' . $_GET['anneeprojetautres'] . ': ' .  $nbtotalprojet; ?><br/></div>
<?php }else{ ?>
<div style="width:1000px;text-align:center;font-size:12px;margin-top: 65px;"><?php echo TXT_NBPROJET. ' '  . $nbtotalprojet; ?><br/></div>
<?php } ?>
<div id="ChartDivAutrescentralePie" style="width:1000px;"></div>
<script>
    require(["dojox/charting/Chart", "dojox/charting/themes/Claro", "dojox/charting/plot2d/Pie", "dojox/charting/action2d/Tooltip", "dojox/charting/action2d/MoveSlice", "dojox/charting/plot2d/Markers",
        "dojox/charting/axis2d/Default", "dojo/domReady!"
    ], function(Chart, theme, Pie, Tooltip, MoveSlice) {
        var chart = new Chart("ChartDivAutrescentralePie");
        chart.setTheme(theme);
        chart.addPlot("default", {type: Pie, markers: true, radius: 120});
        chart.addAxis("x");
        chart.addAxis("y", {min: 5000, max: 30000, vertical: true, fixLower: "major", fixUpper: "major"});
        chart.addSeries("", [<?php echo $string; ?>]);
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
<?php BD::deconnecter(); 