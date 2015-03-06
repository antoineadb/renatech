<?php
include_once	'class/Manager.php';
$db	=	BD::connecter();
$manager	=	new	Manager($db);
$arrayRessource	=	$manager->getList("select libelleressource,idressource from ressource");
$totalressource	=	count($arrayRessource);
$string0	=	'';
$typeUser	=	$manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?",	$_SESSION['pseudo']);
$idcentrale	=	$manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?",	$_SESSION['pseudo']);
if	($typeUser	==	ADMINNATIONNAL)	{
					if	(isset($_GET['anneeprojetRessource'])	&&	$_GET['anneeprojetRessource']	!=	1)	{
										$nbtotalressource	=	$manager->getSinglebyArray("SELECT count(idressource) FROM ressourceprojet,ressource,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet and EXTRACT(YEAR from dateprojet)=? ",	array($_GET['anneeprojetRessource']));
										if	(!empty($nbtotalressource))	{
															for	($i	=	0;	$i	<	$totalressource;	$i++)	{
																				$nbressource	=	$manager->getSinglebyArray("SELECT count(idressource) FROM ressourceprojet,ressource,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet "
																												.	"and EXTRACT(YEAR from dateprojet)=? and idressource=?",	array($_GET['anneeprojetRessource'],	$arrayRessource[$i]['idressource']));
																				$string0.='{y:'	.	$nbressource	.	',text:"'	.	$arrayRessource[$i]['libelleressource']	.	'",stroke:"black",tooltip:"'	.	round((($nbressource	/	$nbtotalressource)	*	100),	1)	.	'%"},';
															}
										}
					}	else	{
										$nbtotalressource	=	$manager->getSingle("SELECT count(idressource) FROM ressourceprojet,ressource,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet ");
										for	($i	=	0;	$i	<	$totalressource;	$i++)	{
															$nbressource	=	$manager->getSingle2("SELECT count(idressource) FROM ressourceprojet,ressource,projet WHERE idressource_ressource = idressource AND idprojet = idprojet_projet  and idressource=?",	$arrayRessource[$i]['idressource']);
															$string0.='{y:'	.	$nbressource	.	',text:"'	.	$arrayRessource[$i]['libelleressource']	.	'",stroke:"black",tooltip:"'	.	round((($nbressource	/	$nbtotalressource)	*	100),	1)	.	'%"},';
										}
					}
}	elseif	($typeUser	==	ADMINLOCAL)	{
					if	(isset($_GET['anneeprojetRessource'])	&&	$_GET['anneeprojetRessource']	!=	1)	{
										$nbtotalressource	=	$manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? ",	array($idcentrale,$_GET['anneeprojetRessource']));
										if	(!empty($nbtotalressource))	{
															for	($i	=	0;	$i	<	$totalressource;	$i++)	{
																				$nbressource	=	$manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and idressource=?",	array($idcentrale,$_GET['anneeprojetRessource'],	$arrayRessource[$i]['idressource']));
																				$string0.='{y:'	.	$nbressource	.	',text:"'	.	$arrayRessource[$i]['libelleressource']	.	'",stroke:"black",tooltip:"'	.	round((($nbressource	/	$nbtotalressource)	*	100),	1)	.	'%"},';
															}
										}
					}	else	{
										$nbtotalressource	=	$manager->getSingle2("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? ",	$idcentrale);
										if	(!empty($nbtotalressource))	{
															for	($i	=	0;	$i	<	$totalressource;	$i++)	{
																				$nbressource	=	$manager->getSinglebyArray("SELECT count(idressource_ressource) FROM  concerne c, projet p, ressourceprojet rp, ressource r WHERE c.idprojet_projet = p.idprojet AND rp.idressource_ressource = r.idressource AND  rp.idprojet_projet = p.idprojet and c.idcentrale_centrale=? and idressource=?",	array($idcentrale,$arrayRessource[$i]['idressource']));
																				$string0.='{y:'	.	$nbressource	.	',text:"'	.	$arrayRessource[$i]['libelleressource']	.	'",stroke:"black",tooltip:"'	.	round((($nbressource	/	$nbtotalressource)	*	100),	1)	.	'%"},';
															}
										}
					}
}
$string	=	substr($string0,	0,	-1);
?><div style="width:1000px;text-align:center;font-size:15px;margin-top: 5px;"><?php	echo	'<b>Répartition en %</b>';	?></div>
<div id="chartNodeRESSOURCEPIE" style="width:1000px;"></div>
<script>
					require(["dojox/charting/Chart", "dojox/charting/themes/Claro", "dojox/charting/plot2d/Pie", "dojox/charting/action2d/Tooltip", "dojox/charting/action2d/MoveSlice", "dojox/charting/plot2d/Markers",
										"dojox/charting/axis2d/Default", "dojo/domReady!"
					], function(Chart, theme, Pie, Tooltip, MoveSlice) {
										var chart = new Chart("chartNodeRESSOURCEPIE");
										chart.setTheme(theme);
										chart.addPlot("default", {type: Pie, markers: true, radius: 130});
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