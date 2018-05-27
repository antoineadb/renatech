<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");

if (isset($_GET['idcentrale']) && $_GET['idcentrale'] != 99 && isset($_GET['anneecentrale']) && $_GET['anneecentrale'] != 1) {//UNE CENTRALE+UNE DATE
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet and co.idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $projetInterne = $nbprojetInterne;
    $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / $nbtotalprojet * 100), 1) . '],';
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] != 99 && isset($_GET['anneecentrale']) && $_GET['anneecentrale'] == 1) {//UNE CENTRALE+TOUTES DATE
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet and co.idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / $nbtotalprojet * 100), 1) . '],';
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] == 99 && isset($_GET['anneecentrale']) && $_GET['anneecentrale'] != 1) {//TOUTES CENTRALE+UNE DATE
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / $nbtotalprojet * 100), 1) . '],';
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] == 99 && isset($_GET['anneecentrale']) && $_GET['anneecentrale'] == 1) {//TOUTES CENTRALE+TOUTES DATE
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / $nbtotalprojet * 100), 1) . '],';
}elseif (isset($_GET['anneecentrale']) && $_GET['anneecentrale'] != 1) {//ANNEE SEUL != TOUS
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / $nbtotalprojet * 100), 1) . '],';
} elseif (isset($_GET['anneecentrale']) && $_GET['anneecentrale'] == 1) {//ANNEE SEUL	CENTRALE= TOUS
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / $nbtotalprojet * 100), 1) . '],';
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] != 99) {//CENTRALE SEUL	ANNEE!= TOUS
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet and co.idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / $nbtotalprojet * 100), 1) . '],';
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] == 99) {//CENTRALE SEUL	ANNEE= TOUS
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / $nbtotalprojet * 100), 1) . '],';
} else {
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $string0 ='["' . TXT_PROJETINTERNE . '",' . round((($nbprojetInterne / $nbtotalprojet) * 100), 1) . '],';
    $string3 = '["' . TXT_PROJETEXOEXTERNE . '",' . round((($nbprojetExogeneExterne / $nbtotalprojet) * 100), 1) . '],';
    $string4 = '["' . TXT_PROJETEXOCOLLABORATIF . '",' . round(($nbprojetExogeneCollaboratif / $nbtotalprojet * 100), 1) . '],';
}
$string = substr($string0 . $string3 . $string4, 0, -1);
$manager->exeRequete("drop table if exists tmpdateprojetautres");
$manager->exeRequete("create table tmpdateprojetautres as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet order by EXTRACT(YEAR from dateprojet) asc;");
$row = $manager->getList("select anneeprojet from tmpdateprojetautres order by anneeprojet asc");
?>
<script type='text/javascript'>//<![CDATA[ 
    $(function () {
        Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
            return {
                radialGradient: {cx: 0.5, cy: 0.3, r: 0.7},
                stops: [
                    [0, color],
                    [1, Highcharts.Color(color).brighten(-0.5).get('rgb')] // darken
                ]
            };
        });
        var chart = new Highcharts.Chart({
            chart: {
                renderTo: 'ChartDivAutrescentralePie',
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
<script src="<?php echo '/' . REPERTOIRE; ?>/js/grid-light.js"></script>
<div id="ChartDivAutrescentralePie"></div>

