<?php

session_start();
include_once	'../decide-lang.php';
include	'../class/Manager.php';
include	'../class/Securite.php';
include_once	'../outils/toolBox.php';
//include '../outils/donneeSession.php';exit();
$db	=	BD::connecter();	//CONNEXION A LA BASE DE DONNEE
$manager	=	new	Manager($db);	//CREATION D'UNE INSTANCE DU MANAGER
if	(isset($_POST['datemodifstatut']))	{
					$datemodifstatut	=	$_POST['datemodifstatut'];
}	else	{
					$datemodifstatut	=	date('Y-m-d');
}

//------------------------------------------------------------------------------
//                          CHAMPS  SANS TRAITEMENT

if	(isset($_POST['page_precedente'])	&&	$_POST['page_precedente']	==	'phase2.html')	{
					if	(isset($_GET['idprojet']))	{
										$idprojet	=	$_GET['idprojet'];
					}
					$rowprojet	=	$manager->getListbyArray("select contactscentraleaccueil,typeFormation,emailrespdevis,nbHeure,envoidevis,nbeleve,dateDebutTravaux,dureeprojet,centralepartenaireprojet,
					nbplaque,nbrun,nomformateur,refinterneprojet,partenaire1		from projet where idprojet=?",	array($idprojet));

//--------------------------------------------------------------------------------------
					if	(isset($_POST['contactCentralAccueil']))	{
										$contactCentralAccueil	=	stripslashes(Securite::bdd($_POST['contactCentralAccueil']));
					}	elseif	(!empty($rowprojet[0]['contactscentraleaccueil']))	{
										$contactCentralAccueil	=	$rowprojet[0]['contactscentraleaccueil'];
					}	else	{
										$contactCentralAccueil	=	'';
					}

					if	(isset($_POST['typeProjet'])	&&	!empty($_POST['typeProjet']))	{
										$idtypeprojet_typeprojet	=	substr($_POST['typeProjet'],	2);
										$idlibelleFormation	=	$manager->getSingle2("select idtypeprojet from typeprojet where libelletype=?",	"Formation");
										if	($idtypeprojet_typeprojet	==	$idlibelleFormation)	{// ON A AFFAIRE A UN TYPE DE PROJET FORMATION
															if	(isset($_POST['typeFormation']))	{
																				$typeFormation	=	stripslashes(Securite::bdd($_POST['typeFormation']));
																				if	(isset($_POST['nbHeure']))	{
																									$nbHeure	=	Securite::bdd($_POST['nbHeure']);
																				}	else	{
																									$nbHeure	=	0;
																				}
																				if	(isset($_POST['nbeleve']))	{
																									$nbeleve	=	Securite::bdd($_POST['nbeleve']);
																				}	else	{
																									$nbeleve	=	0;
																				}
															}
										}	else	{
															$nbeleve	=	0;
															$nbHeure	=	0;
															$typeFormation	=	null;
										}
					}	else	{
										$idtypeprojet_typeprojet	=	1;
										$typeFormation	=	null;
										$nbeleve	=	0;
										$nbHeure	=	0;
					}


					if	(isset($_POST['nomformateur']))	{
										$nomformateur	=	stripslashes(Securite::bdd($_POST['nomformateur']));
					}	elseif	(!empty($rowprojet[0]['nomformateur']))	{
										$nomformateur	=	$rowprojet[0]['nomformateur'];
					}	else	{
										$nomformateur	=	'';
					}

					if	(isset($_POST['dateDebutTravaux']))	{
										$dateDebutTravaux	=	pg_escape_string($_POST['dateDebutTravaux']);
					}	elseif	(!empty($rowprojet[0]['dateDebutTravaux']))	{
										$dateDebutTravaux	=	$rowprojet[0]['dateDebutTravaux'];
					}	else	{
										$dateDebutTravaux	=	date('Y-m-d');
					}

					if	(isset($_POST['dureeprojet']))	{
										$dureeprojet	=	stripslashes(Securite::bdd($_POST['dureeprojet']));
					}	elseif	(!empty($rowprojet[0]['dureeprojet'])	||	$rowprojet[0]['dureeprojet']	!=	0)	{
										$dureeprojet	=	$rowprojet[0]['dureeprojet'];
					}	else	{
										$dureeprojet	=	'';
					}

					if	(!empty($_POST['choix']))	{
										$idperiodicite_periodicite	=	substr($_POST['choix'],	2);
					}	else	{
										$idperiodicite_periodicite	=	$manager->getSingle2("select idperiodicite_periodicite from projet where idprojet = ?",	$idprojet);
					}

					if	(!empty($_POST['centralepartenaireprojet']))	{
										$centralepartenaireprojet	=	stripslashes(Securite::bdd($_POST['centralepartenaireprojet']));
					}	else	{
										$centralepartenaireprojet	=	'';
					}

					if	(isset($_POST['idautresourcefinancement_autresourcefinancement']))	{
										$idautresourcefinancement_autresourcefinancement	=	stripslashes(Securite::bdd($_POST['idautresourcefinancement_autresourcefinancement']));
					}	elseif	(!empty($rowprojet[0]['idautresourcefinancement_autresourcefinancement']))	{
										$idautresourcefinancement_autresourcefinancement	=	$rowprojet[0]['idautresourcefinancement_autresourcefinancement'];
					}
					if	(isset($_POST['desTechno'])	&&	!empty($_POST['desTechno']))	{
										$descriptifTechnologique	=	stripslashes(Securite::bdd($_POST['desTechno']));
					}	else	{
										$descriptifTechnologique	=	$manager->getSingle2("select descriptiftechnologique from projet where idprojet = ?",	$idprojet);
					}
					if	(isset($_POST['verrouide'])	&&	!empty($_POST['verrouide']))	{
										$verrouidentifie	=	stripslashes(Securite::bdd($_POST['verrouide']));
					}	else	{
										$verrouidentifie	=	$manager->getSingle2("select verrouidentifiee from projet where idprojet = ?",	$idprojet);
					}

					if	(!empty($_POST['nbPlaque'])	||	$_POST['nbPlaque']	!=	0)	{
										$nbPlaque	=	Securite::bdd($_POST['nbPlaque']);
					}	elseif	(!empty($rowprojet[0]['nbplaque'])	||	$rowprojet[0]['nbplaque']	!=	0)	{
										$nbPlaque	=	$rowprojet[0]['nbplaque'];
					}	else	{
										$nbPlaque	=	0;
					}

					if	(!empty($_POST['nbRun'])	||	$_POST['nbRun']	!=	0)	{
										$nbRun	=	Securite::bdd($_POST['nbRun']);
					}	elseif	(!empty($rowprojet[0]['nbrun']))	{
										$nbRun	=	$rowprojet[0]['nbrun'];
					}	else	{
										$nbRun	=	0;
					}

					if	(isset($_POST['devis']))	{
										$devis	=	Securite::bdd($_POST['devis']);
					}	elseif	(!empty($rowprojet[0]['envoidevis']))	{
										$devis	=	$rowprojet[0]['envoidevis'];
					}	else	{
										$devis	=	'';
					}

					if	(isset($_POST['mailresp']))	{
										$mailresp	=	Securite::bdd(($_POST['mailresp']));
					}	elseif	(!empty($rowprojet[0]['emailrespdevis']))	{
										$mailresp	=	$rowprojet[0]['emailrespdevis'];
					}	else	{
										$mailresp	=	'';
					}

					if	(isset($_POST['refinterne']))	{
										$refinterne	=	stripslashes(Securite::bdd(($_POST['refinterne'])));
					}	elseif	(!empty($rowprojet[0]['refinterneprojet']))	{
										$refinterne	=	$rowprojet[0]['refinterneprojet'];
					}	else	{
										$refinterne	=	'';
					}

					if	(isset($_POST['reussit'])	&&	!empty($_POST['reussit']))	{
										$reussite	=	stripslashes(Securite::bdd(($_POST['reussit'])));
					}	else	{
										$reussite	=	$manager->getSingle2("select reussite from projet where idprojet =?",	$idprojet);
					}
					if	(isset($_FILES['fichierphase2']['name'])	&&	!empty($_FILES['fichierphase2']['name']))	{
										$attachementdesc	=	$_FILES['fichierphase2']['name'];
					}	else	{
										$attachementdesc	=	$manager->getSingle2("select attachementdesc from projet where idprojet =?",	$idprojet);
										if	(empty($attachementdesc))	{
															$attachementdesc	=	'';
										}
					}
//------------------------------------------------------------------------------------------------------------------------
//			 MISE A JOUR DES FICHIERS UPLOADES ON VERIFIE L'ECART ENTRE LES NOMS INSCRIT DANS LA TABLE PROJET
//			 ET LES FICHIERS PRESENTS SUR LE SERVEUR, ON EFFACE CEUX QUI NE SONT PAS REFERENCES DANS LA TABLE
//			 PROJET
//------------------------------------------------------------------------------------------------------------------------
					$uploadProjet	=	$manager->getdataArray("select attachementdesc from projet where attachementdesc !=''");
					$listerepertoire	=	getDirectoryList("../uploaddesc");
//$listerepertoire = tableau contenant la liste des fichiers dans le répertoire upload
					$resultEcart	=	array_diff($listerepertoire,	$uploadProjet);

//$resultEcart = tableau contenant l'écart entre les fichiers du répertoire distant et le noms des fichier contenu dans la table projet
					for	($i	=	0;	$i	<	count($listerepertoire);	$i++)	{
										if	(in_array($listerepertoire[$i],	$resultEcart))	{	//Vérification si l'
															unlink('../uploaddesc/'	.	$listerepertoire[$i]);	//Suppression du fichier non référencés dans la table projet
										}
					}
//---------------------------------------------------------------------------------------------------------------------------				
//---------------------------------------------------------------------------------------------------------------------------


					$devtechnologique	=	stripslashes(Securite::bdd($_POST['devTechnologique']));
					if	($devtechnologique	==	FALSE)	{
										$verrouidentifie	=	'';
					}

//------------------------------------------------------------------------------------------------------------
//                              TRAITEMENT DES SOURCES DE FINANCEMENT (CADRE INSTITUTIONNEL)
//------------------------------------------------------------------------------------------------------------
					$nbsource	=	$manager->getList("select idsourcefinancement from sourcefinancement");
					if	($nbsource	>	0)	{
										$arraylibellesourcefinancement	=	array();
										$manager->deletesourcefinancementprojet($idprojet);	//EFFACEMENT AU PREALABLE DES REFERENCES SOURCE FINANCEMENT AUX PROJETS
										for	($i	=	0;	$i	<	count($nbsource);	$i++)	{
															$sf	=	'sf'	.	($i	+	1);
															if	(!empty($_POST[''	.	$sf	.	'']))	{
																				if	(strlen($sf)	<	4)	{
																									array_push($arraylibellesourcefinancement,	$manager->getSingle2("select libellesourcefinancement from sourcefinancement where idsourcefinancement=? ",	substr($sf,	-1)));
																				}	else	{
																									array_push($arraylibellesourcefinancement,	$manager->getSingle2("select libellesourcefinancement from sourcefinancement where idsourcefinancement=? ",	substr($sf,	-2)));
																				}
															}
										}
										for	($i	=	0;	$i	<	count($arraylibellesourcefinancement);	$i++)	{
															if	(!empty($arraylibellesourcefinancement[$i]))	{
																				$idsourcefinancement	=	$manager->getSingle2("select idsourcefinancement from sourcefinancement where libellesourcefinancement=? ",	$arraylibellesourcefinancement[$i]);
																				$projetSF	=	new	Projetsourcefinancement($idprojet,	$idsourcefinancement);
																				$manager->insertProjetSF($projetSF);	//AJOUT DES SOURCE DE FINANCEMENT DANS LA TABLE PROJETSOURCEFINANCEMENT AVEC L'IDPROJET
															}
										}
					}

					for	($i	=	1;	$i	<	7;	$i++)	{
										$champpost	=	'acronymesourcef'	.	$i;
										if	(!empty($_POST[''	.	$champpost	.	'']))	{
															$idsourcefinancementacro	=	substr($champpost,	-1);	//RECUPERATION DU DERNIER CARACTERE acronymesourcef1-9
															$projetacro	=	new	ProjetAcrosourcefinancement($idprojet,	$_POST[''	.	$champpost	.	''],	$idsourcefinancementacro);
															$manager->updateProjetacrosourcefinancement($projetacro,	$idprojet);
										}
					}


					//------------------------------------------------------------------------------------------------------------
					//                              TRAITEMENT DES THEMATIQUE
					//------------------------------------------------------------------------------------------------------------
					if	(!empty($_POST['thematique']))	{
										$idthematique	=	substr($_POST['thematique'],	2);
										$libellethematique	=	$manager->getSingle2("select libellethematique from thematique where idthematique =?",	$idthematique);
										if	($libellethematique	!=	TXT_AUTRES)	{//VALEUR DIFFERENTE DE "Autres"
															$idthematique_thematique	=	$manager->getSingle2("select idthematique from thematique where libellethematique =?",	$libellethematique);
															$idautrethematique_autrethematique	=	1;	//valeur n/a
										}	else	{
															//------------------------------------------------------------------------------------------------------------
															//                                       TRAITEMENT DES AUTRES THEMATIQUE
															//------------------------------------------------------------------------------------------------------------
															$autreThematique	=	stripslashes(Securite::bdd($_POST['autreThematique']));
															$idautrethematique_autrethematique	=	$manager->getSingle("select max(idautrethematique) from autrethematique")	+	1;
															$newautrethematique	=	new	autrethematique($idautrethematique_autrethematique,	$autreThematique);
															$manager->addautrethematique($newautrethematique);
															$idthematique_thematique	=	$manager->getSingle("select idthematique from thematique where libellethematique='Autres'");
										}
					}	else	{
										$idthematique_thematique	=	$manager->getSingle2("select idthematique_thematique from projet where idprojet=?",	$idprojet);
										if	(empty($idthematique_thematique))	{
															$idthematique_thematique	=	'1';
										}
										$idautrethematique_autrethematique	=	$manager->getSingle2("select idautrethematique_autrethematique from projet where idprojet=?",	$idprojet);
										if	(empty($idautrethematique_autrethematique))	{
															$idautrethematique_autrethematique	=	'1';
										}
					}

					if	(isset($_POST['nomPartenaire01']))	{
										$partenaire1	=	stripslashes(Securite::bdd(($_POST['nomPartenaire01'])));
					}	elseif	(!empty($rowprojet[0]['nomPartenaire01']))	{
										$partenaire1	=	$rowprojet[0]['nomPartenaire01'];
					}	else	{
										$partenaire1	=	'';
					}

					$porteurprojet	=	$_POST['porteurprojet'];


					//------------------------------------------------------------------------------------------------------------
					//                              TRAITEMENT DU PROJETPHASE2
					//------------------------------------------------------------------------------------------------------------
					$projetphase2	=	new	Projetphase2($contactCentralAccueil,	$idtypeprojet_typeprojet,	$typeFormation,	$nbHeure,	$dateDebutTravaux,	$dureeprojet,	$idperiodicite_periodicite,	$centralepartenaireprojet,	$idthematique_thematique,	$idautrethematique_autrethematique,	$descriptifTechnologique,	$attachementdesc,	$verrouidentifie,	$nbPlaque,	$nbRun,	$devis,	$mailresp,	$reussite,	$refinterne,	$devtechnologique,	$nbeleve,	$nomformateur,	$partenaire1,	$porteurprojet);
					$manager->updateProjetphase2($projetphase2,	$idprojet);

					//------------------------------------------------------------------------------
					//                          PARTENAIRE PROJET
					//------------------------------------------------------------------------------

					if	(isset($_POST['nombrePartenaire'])	&&	$_POST['nombrePartenaire']	!=	0)	{//SI LE NOMBRE DE PARTENAIRE EST >0
										//SUPPPRESSION DES PARTENAIRES DANS LA TABLE PROJETPARTENAIRE
										$nombrePartenaire	=	$_POST['nombrePartenaire'];
										$manager->deleteprojetpartenaire($idprojet);
										//RECUPERATION DU PROJET DANS LA TABLE PARTENAIREPROJET QUI N'AS PAS DE REFERENCE DANS LA TABLE PROJETPARTENAIRE-->SUPPRESSION ENREGISTEMENT VIDE
										$idpartenaire	=	$manager->getList("SELECT idpartenaire FROM  partenaireprojet where idpartenaire not in (select idpartenaire_partenaireprojet from projetpartenaire)");
										//SUPPRESSION DES LIGNES CORRESPONDANTES
										if	(count($idpartenaire)	>	0)	{
															for	($i	=	0;	$i	<	count($idpartenaire);	$i++)	{
																				$manager->deletepartenaireprojet($idpartenaire[$i]['idpartenaire']);
															}
										}
					}	else	{
										//include	'../outils/donneeSession.php';exit();
										//IL N'A PAS DE PARTENAIRE SELECTIONNE IL FAUT SUPPRIMER LES PARTENAIRES PROJET DANS LES TABLE PARTENAIREPROJET ET PROJETPARTENAIRE
										//------------------------------------------------------------------------------------------------------------------------------------------------------
										$nombrePartenaire	=	0;
										$manager->deleteprojetpartenaire($idprojet);
										//RECUPERATION DU PROJET DANS LA TABLE PARTENAIREPROJET QUI N'AS PAS DE REFERENCE DANS LA TABLE PROJETPARTENAIRE-->SUPPRESSION ENREGISTEMENT VIDE
										$idpartenaire	=	$manager->getList("SELECT idpartenaire FROM  partenaireprojet where idpartenaire not in (select idpartenaire_partenaireprojet from projetpartenaire)");
										//SUPPRESSION DES LIGNES CORRESPONDANTES
										if	(count($idpartenaire)	>	0)	{
															for	($i	=	0;	$i	<	count($idpartenaire);	$i++)	{
																				$manager->deletepartenaireprojet($idpartenaire[$i]['idpartenaire']);
															}
										}
										//EFFACAGE DU ER PARTENAIRE DANS LA TABLE PROJET
										$centralepartenaireprojet	=	$manager->getSingle2("select centralepartenaireprojet from projet where idprojet=?",	$idprojet);
										//$autrenomcentrale = $manager->getSingle2("select autrenomcentrale from projet where idprojet=?", $idprojet);
										$partenaire1	=	$manager->getSingle2("select partenaire1 from projet where idprojet=?",	$idprojet);
										$partenairefromprojet	=	new	Partenairefromprojet($centralepartenaireprojet,	$partenaire1);
										$manager->updatepartenairefromprojet($partenairefromprojet,	$idprojet);


										//------------------------------------------------------------------------------------------------------------------------------------------------------
										//------------------------------------------------------------------------------------------------------------------------------------------------------
					}
					$partenaires	=	'';
					for	($i	=	0;	$i	<	$nombrePartenaire;	$i++)	{
										$nomPartenaire	=	'nomPartenaire'	.	$i;
										$nomLaboEntreprise	=	'nomLaboEntreprise'	.	$i;

										if	(isset($_POST[''	.	$nomPartenaire	.	'']))	{
															$nomPartenaire	=	stripslashes(Securite::bdd(($_POST[''	.	$nomPartenaire	.	''])));
										}
										if	(isset($_POST[''	.	$nomLaboEntreprise	.	'']))	{
															$nomLaboEntreprise	=	stripslashes(Securite::bdd(($_POST[''	.	$nomLaboEntreprise	.	''])));
										}
										//TRAITEMENT AJOUT DANS LA TABLE PARTENAIREPROJET
										$idpartenaire	=	$manager->getSingle("select max (idpartenaire) from partenaireprojet")	+	1;
										$newpartenaireprojet	=	new	Partenaireprojet($idpartenaire,	$nomPartenaire,	$nomLaboEntreprise);
										$manager->addpartenaireprojet($newpartenaireprojet);
										$partenaires	.=$nomPartenaire	.	' - '	.	$nomLaboEntreprise	.	' - ';
										//TRAITEMENT AJOUT DANS LA TABLE PROJETPARTENAIRE

										$newprojetpartenaire	=	new	Projetpartenaire($idpartenaire,	$idprojet);
										$manager->addprojetpartenaire($newprojetpartenaire);
					}$_SESSION['partenaires']	=	substr(trim($partenaires),	0,	-1);

					//------------------------------------------------------------------------------------------------------------------------
					//			 MISE A JOUR DE LA TABLE RESSOURCEPROJET  ON EFFACE TOUTES LES RESSOURCES SELECTIONNEES
					//------------------------------------------------------------------------------------------------------------------------

					$ressources	=	'';
					if	(isset($_POST['ressource']))	{
										$manager->deleteressourceprojet($idprojet);
										$ressource	=	$_POST['ressource'];
										foreach	($ressource	as	$chkbx)	{
															$arrayressource	=	$manager->getListbyArray("SELECT idressource FROM ressource where libelleressource =?",	array($chkbx));
															$ressources	.=$chkbx	.	',';
															for	($i	=	0;	$i	<	count($arrayressource);	$i++)	{
																				$idressource_ressource	=	$arrayressource[$i]['idressource'];
																				//TRAITEMENT AJOUT DANS LA TABLE RESSOURCE PROJET
																				$ressourceprojet	=	new	Ressourceprojet($idprojet,	$idressource_ressource);
																				$manager->addressourceprojet($ressourceprojet);
															}
										}
					}	else	{
										$manager->deleteressourceprojet($idprojet);
					}

					$_SESSION['ressources']	=	substr($ressources,	0,	-1);	//ENLEVE LE DERNIER CARACTERE;
					//------------------------------------------------------------------------------
					//                          CONTROLES DES CHAMPS PERSONNECENTRALE
					//------------------------------------------------------------------------------

					if	($_POST['nombrePersonneCentrale']	>	0)	{
										$nombrePersonneCentrale	=	+($_POST['nombrePersonneCentrale']);
										$msgerr	=	'';
							
										for	($i	=	0;	$i	<	$nombrePersonneCentrale;	$i++)	{
															if	(!empty($_POST['nomaccueilcentrale'	.	$i	.	'']))	{
																				$_SESSION['nomaccueilcentrale'	.	$i	.	'']	=	$_POST['nomaccueilcentrale'	.	$i	.	''];
															}
															if	(!empty($_POST['prenomaccueilcentrale'	.	$i	.	'']))	{
																				$_SESSION['prenomaccueilcentrale'	.	$i	.	'']	=	$_POST['prenomaccueilcentrale'	.	$i	.	''];
															}
															if	(!empty($_POST['connaissancetechnologiqueaccueil'	.	$i	.	'']))	{
																				$_SESSION['connaissancetechnologiqueaccueil'	.	$i	.	'']	=	$_POST['connaissancetechnologiqueaccueil'	.	$i	.	''];
															}
															if	($lang	==	'fr')	{
																				if	(empty($_POST['qualiteaccueilcentrale'	.	$i	.	'']))	{
																									$msgerr	.=	(TXT_ERRQUALITEPERSONNE)	.	$i+1	.	''	.	'<br>';
																				}	elseif	(!empty($_SESSION['qualiteaccueilcentrale'	.	$i	.	'']))	{
																									$_SESSION['qualiteaccueilcentrale'	.	$i	.	'']	=	$_POST['qualiteaccueilcentrale'	.	$i	.	''];
																				}	else	{
																									$idpersonneaccueil	=	$manager->getSinglebyArray("select idqualitedemandeuraca_qualitedemandeuraca from personneaccueilcentrale where nomaccueilcentrale=? "
																																	.	"and prenomaccueilcentrale = ? and mailaccueilcentrale =?",	array($_POST['nomaccueilcentrale'	.	$i	.	''],	$_POST['prenomaccueilcentrale'	.	$i	.	'']),	$_POST['mailaccueilcentrale'	.	$i	.	'']);
																									$idpersonneaccueil	=	$_POST['qualiteaccueilcentrale'	.	$i	.	''];
																				}
															}	elseif	($lang	==	'en')	{
																				if	(empty($_POST['qualiteaccueilcentraleen'	.	$i	.	'']))	{
																									$msgerr	.=	(TXT_ERRQUALITEPERSONNE)	.	$i+1 .	''	.	'<br>';
																				}	else	{
																									$_SESSION['qualiteaccueilcentraleen'	.	$i	.	'']	=	$_POST['qualiteaccueilcentraleen'	.	$i	.	''];
																				}
															}

															if	(empty($_POST['mailaccueilcentrale'	.	$i	.	'']))	{
																				$msgerr	.=	(	TXT_ERREMAILPERSONNE)	.	$i+1	.	'<br>';
															}	else	{
																				$_SESSION['mailaccueilcentrale'	.	$i	.	'']	=	$_POST['mailaccueilcentrale'	.	$i	.	''];
															}
															if	(empty($_POST['nomaccueilcentrale'	.	$i	.	'']))	{
																				$msgerr	.=	TXT_ERRNOMPERSONNE	.	$i+1	.	'<br>';
															}	else	{
																				$_SESSION['nomaccueilcentrale'	.	$i	.	'']	=	$_POST['nomaccueilcentrale'	.	$i	.	''];
															}
															if	(empty($_POST['prenomaccueilcentrale'	.	$i	.	'']))	{
																				$msgerr	.=	TXT_ERRPRENOMPERSONNE	.	$i+1	.	'<br>';
															}	else	{
																				$_SESSION['prenomaccueilcentrale'	.	$i	.	'']	=	$_POST['prenomaccueilcentrale'	.	$i	.	''];
															}
										}
										//------------------------------------------------------------------------------
										//                          ACCUEIL CENTRALE
										//------------------------------------------------------------------------------
										//$nombrePersonneCentrale = $_POST['nombrePersonneCentrale'];
										//SUPPPRESSION DES PERSONNES DANS LA TABLE PROJETPERSONNEACCUEILCENTRALE
										$manager->deleteprojetpersonneaccueilcentrale($idprojet);

										//RECUPERATION DU PROJET DANS LA TABLE PERSONNEACCUEILCENTRALE QUI N'AS PAS DE REFERENCE DANS LA TABLE PROJETPERSONNEACCUEILCENTRALE
										$idpersonnecentrale	=	$manager->getList("SELECT idpersonneaccueilcentrale FROM  personneaccueilcentrale where idpersonneaccueilcentrale not in
	 (select idpersonneaccueilcentrale_personneaccueilcentrale from projetpersonneaccueilcentrale)");

										//SUPPRESSION DES LIGNES CORRESPONDANTES
										if	(count($idpersonnecentrale)	>	0)	{
															for	($i	=	0;	$i	<	count($idpersonnecentrale);	$i++)	{
																				$manager->deletepersonneaccueilcentrale($idpersonnecentrale[$i]['idpersonneaccueilcentrale']);
															}
															$personnecentrales	=	'';

															for	($i	=	1;	$i	<=	$nombrePersonneCentrale;	$i++)	{
																				$nomaccueilcentrale	=	'nomaccueilcentrale'	.	$i;
																				$prenomaccueilcentrale	=	'prenomaccueilcentrale'	.	$i;
																				$qualiteaccueilcentrale	=	'qualiteaccueilcentrale'	.	$i;
																				$qualiteaccueilcentraleen	=	'qualiteaccueilcentraleen'	.	$i;
																				$mailaccueilcentrale	=	'mailaccueilcentrale'	.	$i;
																				$telaccueilcentrale	=	'telaccueilcentrale'	.	$i;
																				$connaissancetechnologiqueaccueil	=	'connaissancetechnologiqueaccueil'	.	$i;

																				if	(isset($_POST[''	.	$nomaccueilcentrale	.	'']))	{
																									$nomaccueilcentrale	=	stripslashes(Securite::bdd($_POST[''	.	$nomaccueilcentrale	.	'']));
																				}
																				if	(isset($_POST[''	.	$prenomaccueilcentrale	.	'']))	{
																									$prenomaccueilcentrale	=	stripslashes(Securite::bdd($_POST[''	.	$prenomaccueilcentrale	.	'']));
																				}
																				if	($lang	==	'fr')	{
																									if	(isset($_POST[''	.	$qualiteaccueilcentrale	.	'']))	{
																														$idqualitedemandeuraca	=	(int)	substr($_POST[''	.	$qualiteaccueilcentrale	.	''],	-1);
																														//$idqualitedemandeuraca = $manager->getSingle2("select idqualitedemandeuraca from qualitedemandeuraca where libellequalitedemandeuraca =?", $_POST['' . $qualiteaccueilcentrale . '']);
																														$libellequalite	=	$manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca =?",	$idqualitedemandeuraca);
																									}
																				}	elseif	($lang	==	'en')	{
																									if	(isset($_POST[''	.	$qualiteaccueilcentraleen	.	'']))	{
																														$idqualitedemandeuraca	=	$manager->getSingle2("select idqualitedemandeuraca from qualitedemandeuraca where libellequalitedemandeuracaen =?",	$_POST[''	.	$qualiteaccueilcentraleen	.	'']);
																														$libellequalite	=	$manager->getSingle2("select libellequalitedemandeuracaen from qualitedemandeuraca where idqualitedemandeuraca =?",	$idqualitedemandeuraca);
																									}
																				}
																				if	(isset($_POST[''	.	$mailaccueilcentrale	.	'']))	{
																									$mailaccueilcentrale	=	Securite::bdd($_POST[''	.	$mailaccueilcentrale	.	'']);
																				}
																				if	(isset($_POST[''	.	$telaccueilcentrale	.	'']))	{
																									$telaccueilcentrale	=	$_POST[''	.	$telaccueilcentrale	.	''];
																				}	else	{
																									$telaccueilcentrale	=	'';
																				}
																				if	(isset($_POST[''	.	$connaissancetechnologiqueaccueil	.	'']))	{
																									$connaissancetechnologiqueAccueil	=	stripslashes(Securite::bdd($_POST[''	.	$connaissancetechnologiqueaccueil	.	'']));
																				}	else	{
																									$connaissancetechnologiqueAccueil	=	'';
																				}

																				$personnecentrales	.=$nomaccueilcentrale	.	' - '	.	$prenomaccueilcentrale	.	' - '	.	$libellequalite	.	' - '	.	$mailaccueilcentrale	.	' - '	.	$telaccueilcentrale	.	' - '	.	trim($connaissancetechnologiqueAccueil)	.	' - ';
																				//TRAITEMENT AJOUT DANS LA TABLE PERSONNEACCUEILCENTRALE
																				$idpersonneaccueilcentrale	=	$manager->getSingle("select max(idpersonneaccueilcentrale) from Personneaccueilcentrale")	+	1;
																				$personne	=	new	Personneaccueilcentrale($idpersonneaccueilcentrale,	$nomaccueilcentrale,	$prenomaccueilcentrale,	$idqualitedemandeuraca,	$mailaccueilcentrale,	$telaccueilcentrale,	trim($connaissancetechnologiqueAccueil));
																				$manager->addPersonneaccueilcentrale($personne);
																				//TRAITEMENT AJOUT DANS LA TABLE PROJETPERSONNEACCUEILCENTRALE
																				$projetpersonneaccueilcentrale	=	new	Projetpersonneaccueilcentrale($idprojet,	$idpersonneaccueilcentrale);
																				$manager->addprojetpersonneaccueilcentrale($projetpersonneaccueilcentrale);
															}$_SESSION['personnecentrales']	=	substr($personnecentrales,	0,	-1);
										}
					}	else	{
										for	($i	=	0;	$i	<	10;	$i++)	{
															unset($_POST['nomaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_POST['prenomaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_POST['qualiteaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_POST['mailaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_POST['telaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_POST['connaissancetechnologiqueaccueil'	.	''	.	$i	.	'']);

															unset($_SESSION['nomaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_SESSION['prenomaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_SESSION['qualiteaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_SESSION['mailaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_SESSION['telaccueilcentrale'	.	''	.	$i	.	'']);
															unset($_SESSION['connaissancetechnologiqueaccueil'	.	''	.	$i	.	'']);
															unset($_SESSION['personnecentrales']);
															unset($_POST['personnecentrales']);
										}
										$nombrePersonneCentrale	=	0;
					}
					if	(!empty($msgerr))	{
										echo	'<script>window.location.replace("../phase2.php?lang='	.	$lang	.	'&idprojet='	.	$idprojet	.	'&msgerr='	.	($msgerr)	.	'&nbpersonne='	.	$nombrePersonneCentrale	.	'")</script>';
										exit();
					}
					if	(isset($_POST['statutProjet']))	{
										$idcentrale	=	$manager->getsingle2("select idcentrale_centrale from concerne where idprojet_projet = ?",	$idprojet);
										$idstatutProjet	=	substr($_POST['statutProjet'],	2,	1);
										$concerne	=	new	Concerne($idcentrale,	$idprojet,	$idstatutProjet,	'');
										$manager->updateConcerne($concerne,	$idprojet);

										$idstatutEncoursrealisation	=	$manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet =? ",	'En cours de réalisation');
										$idFini	=	$manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet =? ",	'Fini');
										$idCloturer	=	$manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet =? ",	'Cloturé');
										$idRefuser	=	$manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet =? ",	'Refusée');

										if	($idstatutProjet	==	$idstatutEncoursrealisation	&&	empty($_POST['envoiemail']))	{//PROJET EN COURS DE REALISATION
															$datedebutprojet	=	$datemodifstatut;
															//MISE A JOUR DE LA TABLE PROJET --> DATEDEBUT DU PROJET = DATE DU JOUR DE CHANGEMENT DE STATUT
															$datedebut	=	new	DateDebutProjet($idprojet,	$datedebutprojet);
															$manager->updateDateDebutProjet($datedebut,	$idprojet);
															// ENVOI D'UN EMAIL
															include	'../EmailProjetEncoursrealisation.php';
										}	elseif	($idstatutProjet	==	$idFini)	{//PROJET FINI
															//VERIFICATION QUE LE PROJET A BIEN UNE DATE DE DEBUT DE PROJET,AFFECTATION DE LA DATE STAUTFINI DANS LE CAS CONTRAIRE
															$datedebutduprojet	=	$manager->getSingle2("select datedebutprojet from projet where idprojet=?",	$idprojet);
															if	(empty($datedebutduprojet))	{
																				$datedebutduprojet	=	$datemodifstatut;
																				$objetDate	=	new	DateDebutProjet($idprojet,	$datedebutduprojet);
																				$manager->updateDateDebutProjet($objetDate,	$idprojet);
															}
															$datestatutFini	=	$datemodifstatut;
															$datefini	=	new	DateStatutFiniProjet($idprojet,	$datestatutFini);
															$manager->updateDateStatutFini($datefini,	$idprojet);
										}	elseif	($idstatutProjet	==	$idCloturer)	{//PROJET CLOTURER
															$datestatutCloturer	=	$datemodifstatut;
															//VERIFICATION QUE LE PROJET A BIEN UNE DATE DE DEBUT DE PROJET,AFFECTATION DE LA DATE STATUTCLOTURER DANS LE CAS CONTRAIRE
															$dates	=	$manager->getList2("select datedebutprojet,datestatutfini from projet where idprojet=?",	$idprojet);
															if	(empty($dates[0]['datedebutprojet']))	{
																				$datedebutduprojet	=	$datemodifstatut;
																				$objetDate	=	new	DateDebutProjet($idprojet,	$datedebutduprojet);
																				$manager->updateDateDebutProjet($objetDate,	$idprojet);
															}
															if	(empty($dates[0]['datestatutfini']))	{
																				$datestatutfini	=	$datemodifstatut;
																				$objetDate	=	new	DateStatutFiniProjet($idprojet,	$datestatutfini);
																				$manager->updateDateStatutFini($objetDate,	$idprojet);
															}

															$datecloturer	=	new	DateStatutCloturerProjet($idprojet,	$datestatutCloturer);
															$manager->updateDateStatutCloturer($datecloturer,	$idprojet);
										}	elseif	($idstatutProjet	==	$idRefuser)	{//PROJET REFUSER
															$datestatutRefuser	=	date('Y-m-d');
															$daterefus	=	new	DateStatutRefusProjet($idprojet,	$datestatutRefuser);
															$manager->updateDateStatutRefuser($daterefus,	$idprojet);
										}
					}
					if	(!empty($_POST['statutProjet']))	{
										$_SESSION['idstatutprojet']	=	$_POST['statutProjet'];
					}	else	{
										$_SESSION['idstatutprojet']	=	$manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?",	$idprojet);
					}
					include	'../uploadphase2.php';
					BD::deconnecter();	//DECONNEXION A LA BASE DE DONNEE
}	else	{
					include_once	'../decide-lang.php';
					echo	'<script>window.location.replace("../erreurlogin.php?lang='	.	$lang	.	'")</script>';
}
?>
