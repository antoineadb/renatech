<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojetautres']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$nblibelle = count($arraylibellecentrale);
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
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] != 99 && isset($_GET['anneecentrale']) && $_GET['anneecentrale'] == 1) {//UNE CENTRALE+TOUTES DATE
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet and co.idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $projetInterne = $nbprojetInterne;
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] == 99 && isset($_GET['anneecentrale']) && $_GET['anneecentrale'] != 1) {//TOUTES CENTRALE+UNE DATE
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $projetInterne = $nbprojetInterne;
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] == 99 && isset($_GET['anneecentrale']) && $_GET['anneecentrale'] == 1) {//TOUTES CENTRALE+TOUTES DATE
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $projetInterne = $nbprojetInterne;
}elseif (isset($_GET['anneecentrale']) && $_GET['anneecentrale'] != 1) {//ANNEE SEUL != TOUS
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? AND EXTRACT(YEAR from dateprojet)=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE, $_GET['anneecentrale']));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $projetInterne = $nbprojetInterne;
} elseif (isset($_GET['anneecentrale']) && $_GET['anneecentrale'] == 1) {//ANNEE SEUL	CENTRALE= TOUS
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $projetInterne = $nbprojetInterne;
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] != 99) {//CENTRALE SEUL	ANNEE!= TOUS
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet and co.idcentrale_centrale=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array($_GET['idcentrale'], ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $projetInterne = $nbprojetInterne;
} elseif (isset($_GET['idcentrale']) && $_GET['idcentrale'] == 99) {//CENTRALE SEUL	ANNEE= TOUS
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $projetInterne = $nbprojetInterne;
} else {
    $nbtotalprojet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE  idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur And u.idcentrale_centrale is not null and  co.idprojet_projet = p.idprojet  and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?", array(ENATTENTE, ENCOURSANALYSE, ACCEPTE, REFUSE));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $projetExogeneExterne = $nbprojetExogeneExterne;
    $projetExogeneCollaboratif = $nbprojetExogeneCollaboratif;
    $projetInterne = $nbprojetInterne;
}
$xaxis = '["' . TXT_PROJETINTERNE . '",' . '"' . TXT_PROJETEXOEXTERNE . '",' . '"' . TXT_PROJETEXOCOLLABORATIF . '"]';
$yaxis = '[' . $projetInterne . ',' . $projetExogeneExterne . ',' . $projetExogeneCollaboratif . ']';
$manager->exeRequete("create table tmpdatecentrale as select distinct  EXTRACT(YEAR from dateprojet)as anneecentrale  from projet where EXTRACT(YEAR from dateprojet) >2012 order by EXTRACT(YEAR from dateprojet) asc;");
$row = $manager->getList("select anneecentrale from tmpdatecentrale order by anneecentrale asc");
//GESTION DU TITRE ET DU SOUS TITRE
if (isset($_GET['anneecentrale']) && $_GET['anneecentrale'] != 1 && isset($_GET['idcentrale']) && !empty($_GET['idcentrale'])) {
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_GET['idcentrale']);
    $subtitle = TXT_NBPROJETALA . ' <b>' . $libellecentrale . '</b> ' . TXT_POURLANNEE . ' ' . $_GET['anneecentrale'] . ': <b>' . $nbtotalprojet . '</b>';
} elseif (isset($_GET['anneecentrale']) && $_GET['anneecentrale'] == 1) {
    $subtitle = TXT_NBPROJET . ' : <b>' . $nbtotalprojet . '</b>';
} elseif (isset($_GET['anneecentrale']) && $_GET['anneecentrale'] == 1 && isset($_GET['idcentrale']) && !empty($_GET['idcentrale'])) {//CHOIX TOUS POUR L'ANNEE
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_GET['idcentrale']);
    $subtitle = TXT_NBPROJETALA . ' <b>' . $libellecentrale . '</b> ' . ': <b>' . $nbtotalprojet . '</b>';
} elseif (isset($_GET['anneecentrale']) && $_GET['anneecentrale'] != 1) {
    $subtitle = TXT_NBPROJETSAU . ' ' . $_GET['anneecentrale'] . ': <b>' . $nbtotalprojet . '</b>';
} elseif (isset($_GET['idcentrale']) && isset($_GET['l']) && $_GET['idcentrale'] != 99) {
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_GET['idcentrale']);
    $subtitle = TXT_NBPROJETALA . ' <b>' . $libellecentrale . '</b> ' . ': <b>' . $nbtotalprojet . '</b>';
} else {
    $subtitle = TXT_NBPROJET . '  <b>' . $nbtotalprojet . '</b>';
}
$title = TXT_PROJETPARCENTRALETYPE;
?>
<table>
    <tr>
        <td>
            <select  id="datecentrale" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'annee',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                     style="width: 250px;margin-left:10px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueProjetCentraledate/<?php echo $lang ?>/' + this.value)" >
                <?php
                for ($i = 0; $i < count($row); $i++) {
                    echo '<option value="' . ($row[$i]['anneecentrale']) . '">' . $row[$i]['anneecentrale'] . '</option>';
                }echo '<option value="' . 1 . '">' . TXT_TOUS . '</option>';
                ?>
            </select>
        </td> 
    </tr>
<?php if (isset($_GET['anneecentrale']) && !empty($_GET['anneecentrale'])){ ?>
    <tr>
        <td>
            <select  id="centrale" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'centrale',value: '',required:false,placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'" 
                     style="width: 250px;margin-left:10px" 
                     onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueProjetCentrale/<?php echo $lang . '/' . $_GET['anneecentrale'] . '/'; ?>' + this.value)" >
                <?php
                for ($i = 0; $i < $nblibelle; $i++) {
                    echo '<option value="' . ($arraylibellecentrale[$i]['idcentrale']) . '">' . $arraylibellecentrale[$i]['libellecentrale'] . '</option>';
                }echo '<option value="' . 99 . '">' . TXT_TOUS . '</option>';
                ?>
            </select>
        </td>
    </tr>   
<?php } else { ?>    
    <tr>
        <td>
            <select  id="centrale" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'centrale',value: '',required:false,placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'" 
                     style="width: 250px;margin-left:10px" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueProjetCentraleid/<?php echo $lang ?>/' + this.value)" >
                <?php
                for ($i = 0; $i < $nblibelle; $i++) {
                    echo '<option value="' . ($arraylibellecentrale[$i]['idcentrale']) . '">' . $arraylibellecentrale[$i]['libellecentrale'] . '</option>';
                }echo '<option value="' . 99 . '">' . TXT_TOUS . '</option>';
                ?>
            </select>
        </td>
    </tr>
<?php }?> 
</table>
<script>
    $(function () {
        // Set up the chart
        var chart = new Highcharts.Chart({
            chart: {
                renderTo: 'ChartDivAutrescentraleBar',
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
                text: '<?php echo $title; ?>',
            },
            subtitle: {
                text: "<?php echo $subtitle; ?>"
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: <?php echo $xaxis ?>
            },
            yAxis: {
                opposite: true,
                title: {
                    text: "<?php echo TXT_NOMBREOCCURRENCE; ?>"
                }

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
                    data: <?php echo $yaxis; ?>, color: '#338099', showInLegend: false
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
<div id="ChartDivAutrescentraleBar" style="width: 1000px"></div>
<div id="sliders">
    <table>
        <tr><td style="font-size: 0.8em;">Alpha Angle</td><td><input id="R0" type="range" min="0" max="45" value="0"/> <span id="R0-value" class="value"></span></td></tr>
        <tr><td style="font-size: 0.8em;">Beta Angle</td><td><input id="R1" type="range" min="0" max="45" value="0"/> <span id="R1-value" class="value"></span></td></tr>
    </table>
</div>
<hr>