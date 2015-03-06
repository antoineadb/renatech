<?php
include_once	'class/Manager.php';
$db	=	BD::connecter();
unset($_SESSION['anneeprojet']);
$manager	=	new	Manager($db);
$arraylibellecentrale	=	$manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$typeUser	=	$manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?",	$_SESSION['pseudo']);
$idcentrale	=	$manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?",	$_SESSION['pseudo']);
$nbtotalprojet	=	$manager->getSingle("select count(idprojet_projet) from concerne");
$nbprojet	=	0;
//TRAITEMENT PAR ANNEE
if	($typeUser	==	ADMINNATIONNAL)	{
					if	(isset($_GET['anneeprojetautres'])	&&	$_GET['anneeprojetautres']	!=	1)	{
										$nbtotalprojet	=	$manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne co where idprojet_projet = idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?",	array($_GET['anneeprojetautres'],	ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE));
										$nbprojetExogeneExterne	=	$manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? 
and p.idprojet not in(select idprojet_projet from projetpartenaire ) ",	array($_GET['anneeprojetautres'],	ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE));
										$nbprojetExogeneCollaboratif	=	$manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL AND EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? 
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?",	array($_GET['anneeprojetautres'],	ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE));
										$nbprojetInterne	=	$nbtotalprojet	-	$nbprojetExogeneExterne	-	$nbprojetExogeneCollaboratif;
										$projetinterne	=	'{y:'	.	$nbprojetInterne	.	',text:"'	.	TXT_PROJETINTERNE	.	'<br>('	.	$nbprojetInterne	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetInterne	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
										$projetExogeneExterne	=	'{y:'	.	$nbprojetExogeneExterne	.	',text:"'	.	TXT_PROJETEXOEXTERNE	.	'<br>('	.	$nbprojetExogeneExterne	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetExogeneExterne	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
										$projetExogeneCollaboratif	=	'{y:'	.	$nbprojetExogeneCollaboratif	.	',text:"'	.	TXT_PROJETEXOCOLLABORATIF	.	'<br>('	.	$nbprojetExogeneCollaboratif	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetExogeneCollaboratif	/	$nbtotalprojet)	*	100),	1)	.	'%"}';
					}	else	{
										$nbtotalprojet	=	$manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne where idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?",	array(ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE));
										$nbprojetExogeneExterne	=	$manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? 
and p.idprojet not in(select idprojet_projet from projetpartenaire ) ",	array(ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE));
										$nbprojetExogeneCollaboratif	=	$manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and idstatutprojet_statutprojet!=? 
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=?",	array(ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE));
										$nbprojetInterne	=	$nbtotalprojet	-	$nbprojetExogeneExterne	-	$nbprojetExogeneCollaboratif;
										$projetinterne	=	'{y:'	.	$nbprojetInterne	.	',text:"'	.	TXT_PROJETINTERNE	.	'<br>('	.	$nbprojetInterne	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetInterne	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
										$projetExogeneExterne	=	'{y:'	.	$nbprojetExogeneExterne	.	',text:"'	.	TXT_PROJETEXOEXTERNE	.	'<br>('	.	$nbprojetExogeneExterne	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetExogeneExterne	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
										$projetExogeneCollaboratif	=	'{y:'	.	$nbprojetExogeneCollaboratif	.	',text:"'	.	TXT_PROJETEXOCOLLABORATIF	.	'<br>('	.	$nbprojetExogeneCollaboratif	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetExogeneCollaboratif	/	$nbtotalprojet)	*	100),	1)	.	'%"}';
					}
}	elseif	($typeUser	==	ADMINLOCAL)	{
					if	(isset($_GET['anneeprojetautres'])	&&	$_GET['anneeprojetautres']	!=	1)	{
										$nbtotalprojet	=	$manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne co where idprojet_projet = idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?",	array($_GET['anneeprojetautres'],	ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE,$idcentrale));
										$nbprojetExogeneExterne	=	$manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale=? ",	array($_GET['anneeprojetautres'],	ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE,$idcentrale));
										$nbprojetExogeneCollaboratif	=	$manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL AND EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?",	array($_GET['anneeprojetautres'],	ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE,$idcentrale));
										$nbprojetInterne	=	$nbtotalprojet	-	$nbprojetExogeneExterne	-	$nbprojetExogeneCollaboratif;
										$projetinterne	=	'{y:'	.	$nbprojetInterne	.	',text:"'	.	TXT_PROJETINTERNE	.	'<br>('	.	$nbprojetInterne	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetInterne	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
										$projetExogeneExterne	=	'{y:'	.	$nbprojetExogeneExterne	.	',text:"'	.	TXT_PROJETEXOEXTERNE	.	'<br>('	.	$nbprojetExogeneExterne	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetExogeneExterne	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
										$projetExogeneCollaboratif	=	'{y:'	.	$nbprojetExogeneCollaboratif	.	',text:"'	.	TXT_PROJETEXOCOLLABORATIF	.	'<br>('	.	$nbprojetExogeneCollaboratif	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetExogeneCollaboratif	/	$nbtotalprojet)	*	100),	1)	.	'%"}';
					}else{
										$nbtotalprojet	=	$manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne co where idprojet_projet = idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?",	array(	ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE,$idcentrale));
										$nbprojetExogeneExterne	=	$manager->getSinglebyArray("SELECT count(distinct p.idprojet) FROM   creer cr,   projet p,utilisateur u,concerne co WHERE   cr.idprojet_projet = p.idprojet AND  u.idutilisateur = cr.idutilisateur_utilisateur
AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
and p.idprojet not in(select idprojet_projet from projetpartenaire ) and co.idcentrale_centrale=? ",	array(ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE,$idcentrale));
										$nbprojetExogeneCollaboratif	=	$manager->getSinglebyArray("SELECT count(idprojet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL  and idstatutprojet_statutprojet!=?
and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?  and idstatutprojet_statutprojet!=? and co.idcentrale_centrale=?",	array(ENATTENTE,	ENCOURSANALYSE,	ACCEPTE,	REFUSE,$idcentrale));
										$nbprojetInterne	=	$nbtotalprojet	-	$nbprojetExogeneExterne	-	$nbprojetExogeneCollaboratif;
										$projetinterne	=	'{y:'	.	$nbprojetInterne	.	',text:"'	.	TXT_PROJETINTERNE	.	'<br>('	.	$nbprojetInterne	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetInterne	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
										$projetExogeneExterne	=	'{y:'	.	$nbprojetExogeneExterne	.	',text:"'	.	TXT_PROJETEXOEXTERNE	.	'<br>('	.	$nbprojetExogeneExterne	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetExogeneExterne	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
										$projetExogeneCollaboratif	=	'{y:'	.	$nbprojetExogeneCollaboratif	.	',text:"'	.	TXT_PROJETEXOCOLLABORATIF	.	'<br>('	.	$nbprojetExogeneCollaboratif	.	')'	.	'",stroke:"black",tooltip:"'	.	round((($nbprojetExogeneCollaboratif	/	$nbtotalprojet)	*	100),	1)	.	'%"}';
					}
}
$string	=	$projetinterne	.	$projetExogeneExterne	.	$projetExogeneCollaboratif;
$manager->exeRequete("drop table if exists tmpdateprojetautres");
$manager->exeRequete("create table tmpdateprojetautres as select distinct  EXTRACT(YEAR from dateprojet)as anneeprojet  from projet order by EXTRACT(YEAR from dateprojet) asc;");
$row	=	$manager->getList("select anneeprojet from tmpdateprojetautres order by anneeprojet asc");
?>

<?php	if	(isset($_GET['anneeprojetautres'])	&&	$_GET['anneeprojetautres']	!=	1)	{	?>
					<div style="width:1000px;text-align:center;font-size:12px;margin-top: 65px;"><?php	echo	TXT_NBPROJET	.	' '	.	$_GET['anneeprojetautres']	.	': '	.	$nbtotalprojet;	?><br/></div>
<?php	}	else	{	?>
					<div style="width:1000px;text-align:center;font-size:12px;margin-top: 65px;"><?php	echo	TXT_NBPROJET	.	' '	.	$nbtotalprojet;	?><br/></div>
<?php	}	?>
<div id="ChartDivAutresPie" style="width:1000px;"></div>
<script>
					require(["dojox/charting/Chart", "dojox/charting/themes/Claro", "dojox/charting/plot2d/Pie", "dojox/charting/action2d/Tooltip", "dojox/charting/action2d/MoveSlice", "dojox/charting/plot2d/Markers",
										"dojox/charting/axis2d/Default", "dojo/domReady!"
					], function(Chart, theme, Pie, Tooltip, MoveSlice) {
										var chart = new Chart("ChartDivAutresPie");
										chart.setTheme(theme);
										chart.addPlot("default", {type: Pie, markers: true, radius: 120});
										chart.addAxis("x");
										chart.addAxis("y", {min: 5000, max: 30000, vertical: true, fixLower: "major", fixUpper: "major"});
										chart.addSeries("", [<?php	echo	$string;	?>]);
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
<?php
BD::deconnecter();
