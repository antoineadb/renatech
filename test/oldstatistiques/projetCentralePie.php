<?php
include_once	'class/Manager.php';
$db	=	BD::connecter();
$manager	=	new	Manager($db);
$arraylibellecentrale	=	$manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$idcentrale	=	$manager->getSingle2("SELECT idcentrale_centrale FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?",	$_SESSION['pseudo']);
$datay	=	array();
$arraylibelle	=	array();
$string0	=	'';
$typeUser	=	$manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?",	$_SESSION['pseudo']);
$arraystatutprojet	=	$manager->getList2("select libellestatutprojet,libellestatutprojeten,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet asc",	TRANSFERERCENTRALE);
if	($typeUser	==	ADMINNATIONNAL)	{
					if	(isset($_GET['anneeprojet'])	&&	$_GET['anneeprojet']	!=	1)	{
										$nbtotalprojet	=	$manager->getSingle2("SELECT count(idprojet_projet) FROM projet,concerne WHERE idprojet = idprojet_projet and EXTRACT(YEAR from dateprojet)=?",	$_GET['anneeprojet']);
										for	($i	=	0;	$i	<	count($arraylibellecentrale);	$i++)	{
															$donneeProjet	=	$manager->getSinglebyArray("SELECT count(idprojet_projet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=? and EXTRACT(YEAR from dateprojet)=?
    ",	array($arraylibellecentrale[$i]['libellecentrale'],	$_GET['anneeprojet']));
															$centrale	=	$arraylibellecentrale[$i]['libellecentrale'];
															if	($nbtotalprojet	!=	0)	{
																				$string0.='{y:'	.	$donneeProjet	.	',text:"'	.	$centrale	.	'",stroke:"black",tooltip:"'	.	round((($donneeProjet	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
															}
										}
					}	elseif	(isset($_GET['statut'])	&&	$_GET['statut']	!=	99)	{
										$nbtotalprojet	=	$manager->getSingle2("SELECT count(idprojet_projet) FROM projet,concerne WHERE idprojet = idprojet_projet and idstatutprojet_statutprojet=?",	$_GET['statut']);
										for	($i	=	0;	$i	<	count($arraylibellecentrale);	$i++)	{
															$donneeProjet	=	$manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=? and idstatutprojet_statutprojet=?
    ",	array($arraylibellecentrale[$i]['libellecentrale'],	$_GET['statut']));
															$centrale	=	$arraylibellecentrale[$i]['libellecentrale'];
															if	($nbtotalprojet	!=	0)	{
																				$string0.='{y:'	.	$donneeProjet	.	',text:"'	.	$centrale	.	'",stroke:"black",tooltip:"'	.	round((($donneeProjet	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
															}
										}
					}	else	{
										$nbtotalprojet	=	$manager->getSingle("select count(idprojet_projet) from concerne");
										for	($i	=	0;	$i	<	count($arraylibellecentrale);	$i++)	{
															$donneeProjet	=	$manager->getSingle2("SELECT count(idprojet_projet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND libellecentrale=?
    ",	$arraylibellecentrale[$i]['libellecentrale']);
															$centrale	=	$arraylibellecentrale[$i]['libellecentrale'];
															if	($nbtotalprojet	!=	0)	{
																				$string0.='{y:'	.	$donneeProjet	.	',text:"'	.	$centrale	.	'",stroke:"black",tooltip:"'	.	round((($donneeProjet	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
															}
										}
					}
}	elseif	($typeUser	==	ADMINLOCAL)	{
					if	(isset($_GET['anneeprojet'])	&&	$_GET['anneeprojet']	!=	1)	{
										$nbtotalprojet	=	$manager->getSinglebyArray("SELECT count(idprojet_projet) FROM projet,concerne WHERE idprojet = idprojet_projet and EXTRACT(YEAR from dateprojet)=? and idcentrale_centrale=?",	array($_GET['anneeprojet'],	$idcentrale));
										for	($i	=	0;	$i	<	count($arraystatutprojet);	$i++)	{
															$donneeProjet	=	$manager->getSinglebyArray("SELECT count(idprojet_projet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale_centrale=? and EXTRACT(YEAR from dateprojet)=? and idstatutprojet_statutprojet=?
    ",	array($idcentrale,	$_GET['anneeprojet'],	$arraystatutprojet[$i]['idstatutprojet']));
															if	($nbtotalprojet	!=	0)	{
																				$string0.='{y:'	.	$donneeProjet	.	',text:"'	.	stripslashes(str_replace("''","'",$arraystatutprojet[$i]['libellestatutprojet']))	.	'",stroke:"black",tooltip:"'	.	round((($donneeProjet	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
															}
										}
					}else{
										$nbtotalprojet	=	$manager->getSingle2("SELECT count(idprojet_projet) FROM projet,concerne WHERE idprojet = idprojet_projet and idcentrale_centrale=?",	$idcentrale);
										for	($i	=	0;	$i	<	count($arraystatutprojet);	$i++)	{
															$donneeProjet	=	$manager->getSinglebyArray("SELECT count(idprojet_projet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale_centrale=? and idstatutprojet_statutprojet=?
    ",	array($idcentrale,	$arraystatutprojet[$i]['idstatutprojet']));
															if	($nbtotalprojet	!=	0)	{
																				$string0.='{y:'	.	$donneeProjet	.	',text:"'	.	stripslashes(str_replace("''","'",$arraystatutprojet[$i]['libellestatutprojet']))	.	'",stroke:"black",tooltip:"'	.	round((($donneeProjet	/	$nbtotalprojet)	*	100),	1)	.	'%"},';
															}
										}
					}
}


$string	=	substr($string0,	0,	-1);
?><div style="width:1000px;text-align:center;font-size:15px;margin-top: 65px;"><?php	echo	'<b>Répartition en %</b>';	?></div>
<div id="chartNode2"></div>
<script>
					require(["dojox/charting/Chart", "dojox/charting/themes/Claro", "dojox/charting/plot2d/Pie", "dojox/charting/action2d/Tooltip", "dojox/charting/action2d/MoveSlice", "dojox/charting/plot2d/Markers",
										"dojox/charting/axis2d/Default", "dojo/domReady!"
					], function(Chart, theme, Pie, Tooltip, MoveSlice) {
										var chart = new Chart("chartNode2");
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