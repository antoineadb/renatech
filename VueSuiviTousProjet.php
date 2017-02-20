<?php
session_start();
include('decide-lang.php');
include	'html/header.html';
if	(!isset($_SESSION['nom']))	{
					$_SESSION['nom']	=	$_SESSION['nomConnect'];
}
include	'outils/toolBox.php';
include_once	'class/Manager.php';
if	(isset($_SESSION['pseudo']))	{
					check_authent($_SESSION['pseudo']);
}	else	{
					echo	'<script>window.location.replace("erreurlogin.php")</script>';
}
$db	=	BD::connecter();	//CONNEXION A LA BASE DE DONNEE
$manager	=	new	Manager($db);	//CREATION D'UNE INSTANCE DU MANAGER
$centrale	=	$manager->getSingle2("SELECT libellecentrale FROM  loginpassword,utilisateur,centrale WHERE idlogin = idlogin_loginpassword "
								.	"AND idcentrale = idcentrale_centrale and pseudo =?",	$_SESSION['pseudo']);
?>
<div id="global">
					<?php	include	'html/entete.html';	?>
					<div style="width: 752px;">
										<div data-dojo-type="dijit/layout/AccordionContainer"   style="height: 350px;">
															<div data-dojo-type="dijit/layout/ContentPane"  title="<?php	echo	TXT_PROJETACCEPTE;	?>" >
																				<?php	require_once	'html/vueSuiviTousProjet.html';	?>
															</div>
										</div>
										<p style="color: darkblue;font-size:12px;font-family:sans-serif "><?php	echo	TXT_SUIVITOUTPROJET;	?></p>
										<p>
															<button data-dojo-type="dijit/form/Button" type="button"onclick="
																									var items = grid.selection.getSelected();
																									for (i = 0; i < items.length; i++) {
																														var numero = items[i]['col2'];
																														window.location.replace('modifBase/updateStatutProjetph2.php?lang=<?php	echo	$lang;	?>&numProjet=' + numero + '&page_precedente=VueSuiviTousProjet');
																									}
                       "><?php	echo	TXT_VALIDER;	?></button>

										</p><?php 
										if	(isset($_GET['messg']))	{
															if	(isset($_GET['idprojet']))	{
																				$numero	=	$manager->getSingle2("select numero from projet where idprojet=?",	$_GET['idprojet']);
																				?>
										<table><tr>
												<td>
															<fieldset id='droit' style="border-color: #5D8BA2;width:530px;padding-top: 10px;padding-left: 15px;">
																		<legend><?php echo TXT_ERR;?></legend>
																		<?php 	echo stripslashes($_GET['messg']);?>
																			<a style="text-decoration: none" href="controler/controlestatutprojet.php?lang=<?php echo $_GET['lang'];?>&numProjet=<?php echo $numero;?>&centrale=<?php echo $centrale;?>"	><?php echo 	$numero;?></a><?php echo 	TXT_CHPXOBLIGATOIRE1;?>
															</fieldset>

												</td>
									</tr>
									</table>
										<?php
															}	else	{
																				echo	'<p style="color: red;font-size:12px;font-family:sans-serif ">'	.	$_GET['messg']	.	TXT_CHPXOBLIGATOIRE1	.	'</p>';
															}
										}
										?>
					</div>
					<br><br><br><br><?php	include	'html/footer.html';	?>
</div>
</body>
</html>
<?php	$db	=	BD::deconnecter();	?>