<?php

include	'../class/Manager.php';
$db	=	BD::connecter();	//CONNEXION A LA BASE DE DONNEE
$manager	=	new	Manager($db);	//CREATION D'UNE INSTANCE DU MANAGER

	$arrayrefprojet	=	$manager->getList2("SELECT numero FROM projet,utilisateurporteurprojet WHERE  idprojet_projet = idprojet AND idutilisateur_utilisateur=?",	122);
					
					foreach	($arrayrefprojet	as	$key	=>	$value)	{
										$refprojet.=$value[0].' ; ';
					}
					echo '<pre>';print_r($arrayrefprojet);echo '</pre>';
					echo $refprojet;

?>
