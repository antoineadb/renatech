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

        $projetExogeneExterne = '.addSeries("' . TXT_PROJETEXOEXTERNE . '",[{y: ' . $nbprojetExogeneExterne . ', x:2 , tooltip: "" + ' . $nbprojetExogeneExterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
        $projetExogeneCollaboratif = '.addSeries("' . TXT_PROJETEXOCOLLABORATIF . '",[{y: ' . $nbprojetExogeneCollaboratif . ', x:3 , tooltip: "" + ' . $nbprojetExogeneCollaboratif . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
        $projetInterne = '.addSeries("' . TXT_PROJETINTERNE . '",[{y: ' . $nbprojetInterne . ', x:1 , tooltip: "" + ' . $nbprojetInterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
    } else {
        $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? 
and p.idprojet not in(select idprojet_projet from projetpartenaire ) ", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and idstatutprojet_statutprojet!=? 
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
        $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
        $projetExogeneExterne = '.addSeries("' . TXT_PROJETEXOEXTERNE . '",[{y: ' . $nbprojetExogeneExterne . ', x:2 , tooltip: "" + ' . $nbprojetExogeneExterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
        $projetExogeneCollaboratif = '.addSeries("' . TXT_PROJETEXOCOLLABORATIF . '",[{y: ' . $nbprojetExogeneCollaboratif . ', x:3 , tooltip: "" + ' . $nbprojetExogeneCollaboratif . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
        $projetInterne = '.addSeries("' . TXT_PROJETINTERNE . '",[{y: ' . $nbprojetInterne . ', x:1 , tooltip: "" + ' . $nbprojetInterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
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

        $projetExogeneExterne = '.addSeries("' . TXT_PROJETEXOEXTERNE . '",[{y: ' . $nbprojetExogeneExterne . ', x:2 , tooltip: "" + ' . $nbprojetExogeneExterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
        $projetExogeneCollaboratif = '.addSeries("' . TXT_PROJETEXOCOLLABORATIF . '",[{y: ' . $nbprojetExogeneCollaboratif . ', x:3 , tooltip: "" + ' . $nbprojetExogeneCollaboratif . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
        $projetInterne = '.addSeries("' . TXT_PROJETINTERNE . '",[{y: ' . $nbprojetInterne . ', x:1 , tooltip: "" + ' . $nbprojetInterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
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
        $projetExogeneExterne = '.addSeries("' . TXT_PROJETEXOEXTERNE . '",[{y: ' . $nbprojetExogeneExterne . ', x:2 , tooltip: "" + ' . $nbprojetExogeneExterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
        $projetExogeneCollaboratif = '.addSeries("' . TXT_PROJETEXOCOLLABORATIF . '",[{y: ' . $nbprojetExogeneCollaboratif . ', x:3 , tooltip: "" + ' . $nbprojetExogeneCollaboratif . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
        $projetInterne = '.addSeries("' . TXT_PROJETINTERNE . '",[{y: ' . $nbprojetInterne . ', x:1 , tooltip: "" + ' . $nbprojetInterne . ', stroke: {color: "", width: 5}, fill: "#697B7C"}])';
    }
}
$string = $projetInterne . $projetExogeneExterne . $projetExogeneCollaboratif;
$string0 = '{value:1,text:"' . TXT_PROJETINTERNE . '"},{value:2,text:"' . TXT_PROJETEXOEXTERNE . '"},{value:3,text:"' . TXT_PROJETEXOCOLLABORATIF . '"}';
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
<?php if (isset($_GET['anneeprojetautres']) && $_GET['anneeprojetautres'] != 1) { ?>
    <div style="width:1000px;text-align:center;font-size:12px;margin-top: 20px;"><?php echo TXT_NBPROJETSAU . ' ' . $_GET['anneeprojetautres'] . ': <b>' . $nbtotalprojet.'</b>'; ?><br/></div>
<?php } else { ?>
    <div style="width:1000px;text-align:center;font-size:12px;margin-top: 20px;">
        <?php echo TXT_NBPROJET; ?><?php echo  ': <b>' . $nbtotalprojet . '</b>'; ?><br/></div>
    <?php } ?>
<div id="ChartDivAutresBar" style="width:1000px;"></div>
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
        var c = new dojox.charting.Chart2D("ChartDivAutresBar");
        c.addPlot("default", {type: "Columns", tension: 6, gap: 7})
                .addAxis("x", {labels: [<?php echo $string0; ?>], fixLower: "major", fixUpper: "major"
                }).addAxis("y", {vertical: true, fixLower: "major", fixUpper: "major", min: 0
        }).setTheme(dojox.charting.themes.Wetland)
<?php echo $string . ';'; ?>
        var a1 = new dojox.charting.action2d.Tooltip(c, "default");
        var a2 = new dojox.charting.action2d.Highlight(c, "default");
        c.render();
    });
</script>
<?php
BD::deconnecter();
