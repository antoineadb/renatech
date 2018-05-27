<?php

class	Formationeleve	{

					private	$_idformationeleve;
					private	$_nomeleve;
					private	$_prenomeleve;
					private	$_entrepriselabo;
					private	$_villeeleve;
					private	$_idpays_pays;
					private	$_idqualitedemandeuraca_qualitedemandeuraca;

					public	function	__construct($idformationeleve,	$nomeleve,	$prenomeleve,	$entrepriselabo,	$villeeleve,	$idpays_pays,	$idqualitedemandeuraca_qualitedemandeuraca)	{

										$this->setIdformationeleve($idformationeleve);
										$this->setNomeleve($nomeleve);
										$this->setPrenomeleve($prenomeleve);
										$this->setEntrepriselabo($entrepriselabo);
										$this->setVilleeleve($villeeleve);
										$this->setIdpays_pays($idpays_pays);
										$this->setIdqualitedemandeuraca_qualitedemandeuraca($idqualitedemandeuraca_qualitedemandeuraca);
					}

					public	function	setIdformationeleve($id)	{
										$idformation	=	(int)	$id;
										if	($idformation	>=	1)	{
															$this->_idformationeleve	=	$id;
										}	else	{
															echo	"<br>Erreur la valeur de l'id de la formation de l\'élève doit être supérieur à 1<br>";
										}
					}

					public	function	getIdformationeleve()	{
										return	$this->_idformationeleve;
					}


					public	function	setNomeleve($nomeleve)	{
										$this->_nomeleve	=	$nomeleve;
					}

					public	function	getNomeleve()	{
										return	$this->_nomeleve;
					}

					public	function	setPrenomeleve($nomeleve)	{
										$this->_prenomeleve	=	$nomeleve;
					}

					public	function	getPrenomeleve()	{
										return	$this->_prenomeleve;
					}

					public	function	setEntrepriselabo($entrepriselabo)	{
										$this->_entrepriselabo	=	$entrepriselabo;
					}

					public	function	getEntrepriselabo()	{
										return	$this->_entrepriselabo;
					}

					public	function	setVilleeleve($villeeleve)	{
										$this->_villeeleve	=	$villeeleve;
					}

					public	function	getVilleeleve()	{
										return	$this->_villeeleve;
					}

					public	function	setIdpays_pays($id)	{
										$idpays	=	(int)	$id;
										if	($idpays	>=	1)	{
															$this->_idpays_pays	=	$id;
										}	else	{
															echo	"<br>".TXT_SELECTPAYS."<br>";
															?><a href="http://www.rtb.cnrs.fr/projet-dev/phase2.php?lang=fr&numProjet=<?php echo $_GET['numProjet'];?>&statut=3"><?php echo TXT_RETOURPAGE;?></a>
										<?php
															exit();
										}
					}

					public	function	getIdpays_pays()	{
										return	$this->_idpays_pays;
					}

//$_idqualitedemandeuraca_qualitedemandeuraca

					public	function	setIdqualitedemandeuraca_qualitedemandeuraca($id)	{
										$idqualite	=	(int)	$id;
										if	($idqualite	>=	1)	{
															$this->_idqualitedemandeuraca_qualitedemandeuraca	=	$id;
										}	else	{
															echo	"<br>".TXT_SELECT_QUALITE."<br>";
															?><a href="http://www.rtb.cnrs.fr/projet-dev/phase2.php?lang=fr&numProjet=<?php echo $_GET['numProjet'];?>&statut=3"><?php echo TXT_RETOURPAGE;?></a>
										<?php
															exit();
										}
					}

					public	function	getIdqualitedemandeuraca_qualitedemandeuraca()	{
										return	$this->_idqualitedemandeuraca_qualitedemandeuraca;
					}

}

?>
