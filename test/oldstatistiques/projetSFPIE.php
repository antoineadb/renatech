<?php
include_once	'class/Manager.php';
$db	=	BD::connecter();
$manager	=	new	Manager($db);
$arraySourcefinancement	=	$manager->getList("select libellesourcefinancement,idsourcefinancement from sourcefinancement");
$nbsf	=	count($arraySourcefinancement);
$string0	=	'';
$typeUser	=	$manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?",	$_SESSION['pseudo']);
$idcentrale	=	$manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?",	$_SESSION['pseudo']);
if	($typeUser	==	ADMINNATIONNAL)	{
					if	(isset($_GET['anneeprojetsf'])	&&	$_GET['anneeprojetsf']	!=	1)	{
										$nbtotalsource	=	$manager->getSinglebyArray("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet and EXTRACT(YEAR from dateprojet)=? ",	array($_GET['anneeprojetsf']));
										for	($i	=	0;	$i	<	$nbsf;	$i++)	{
															$nbsource	=	$manager->getSinglebyArray("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet "
																							.	"and EXTRACT(YEAR from dateprojet)=? and idsourcefinancement=?",	array($_GET['anneeprojetsf'],	$arraySourcefinancement[$i]['idsourcefinancement']));
															$string0.='{y:'	.	$nbsource	.	',text:"'	.	$arraySourcefinancement[$i]['libellesourcefinancement']	.	'",stroke:"black",tooltip:"'	.	round((($nbsource	/	$nbtotalsource)	*	100),	1)	.	'%"},';
										}
					}	else	{
										$nbtotalsource	=	$manager->getSingle("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet ");
										for	($i	=	0;	$i	<	$nbsf;	$i++)	{
															$nbsource	=	$manager->getSingle2("SELECT count(idsourcefinancement) FROM projetsourcefinancement,sourcefinancement,projet WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet = idprojet_projet and idsourcefinancement=?",	$arraySourcefinancement[$i]['idsourcefinancement']);
															$string0.='{y:'	.	$nbsource	.	',text:"'	.	$arraySourcefinancement[$i]['libellesourcefinancement']	.	'",stroke:"black",tooltip:"'	.	round((($nbsource	/	$nbtotalsource)	*	100),	1)	.	'%"},';
										}
					}
}	elseif	($typeUser	==	ADMINLOCAL)	{
					if	(isset($_GET['anneeprojetsf'])	&&	$_GET['anneeprojetsf']	!=	1)	{
										$nbtotalsource	=	$manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? ",	array($idcentrale,	$_GET['anneeprojetsf']));
										for	($i	=	0;	$i	<	$nbsf;	$i++)	{
															$nbsource	=	$manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and ps.idsourcefinancement_sourcefinancement=?",	array($idcentrale,	$_GET['anneeprojetsf'],	$arraySourcefinancement[$i]['idsourcefinancement']));
															$string0.='{y:'	.	$nbsource	.	',text:"'	.	$arraySourcefinancement[$i]['libellesourcefinancement']	.	'",stroke:"black",tooltip:"'	.	round((($nbsource	/	$nbtotalsource)	*	100),	1)	.	'%"},';
										}
					}	else	{
										$nbtotalsource	=	$manager->getSingle2("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? ",	$idcentrale);
										for	($i	=	0;	$i	<	$nbsf;	$i++)	{
															$nbsource	=	$manager->getSinglebyArray("SELECT count(ps.idsourcefinancement_sourcefinancement) FROM concerne c,projet p,projetsourcefinancement ps
WHERE c.idprojet_projet = p.idprojet AND ps.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and ps.idsourcefinancement_sourcefinancement=?",	array($idcentrale,$arraySourcefinancement[$i]['idsourcefinancement']));
															$string0.='{y:'	.	$nbsource	.	',text:"'	.	$arraySourcefinancement[$i]['libellesourcefinancement']	.	'",stroke:"black",tooltip:"'	.	round((($nbsource	/	$nbtotalsource)	*	100),	1)	.	'%"},';
										}
					}
}

$string	=	substr($string0,	0,	-1);
?><div style="width:1000px;text-align:center;font-size:15px;margin-top: 65px;"><?php	echo	'<b>Répartition en %</b>';	?></div>
<div id="chartNodeSFPIE" style="width:1000px;"></div>
<script>
					require(["dojox/charting/Chart", "dojox/charting/themes/Claro", "dojox/charting/plot2d/Pie", "dojox/charting/action2d/Tooltip", "dojox/charting/action2d/MoveSlice", "dojox/charting/plot2d/Markers",
										"dojox/charting/axis2d/Default", "dojo/domReady!"
					], function(Chart, theme, Pie, Tooltip, MoveSlice) {
										var chart = new Chart("chartNodeSFPIE");
										chart.setTheme(theme);
										chart.addPlot("default", {type: Pie, markers: true, radius: 120});
										chart.addAxis("x");
										chart.addAxis("y", {min: 5000, max: 30000, vertical: true, fixLower: "major", fixUpper: "major"});
										chart.addSeries("toto", [<?php	echo	$string;	?>]);
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