<?php

class	Projetformation	{

					private	$_idprojet_projet;
					private	$_idformationeleve_formationeleve;

					public	function	__construct($idprojet_projet,	$idformationeleve_formationeleve)	{
										$this->setIdprojet_projet($idprojet_projet);
										$this->setIdformationeleve_formationeleve($idformationeleve_formationeleve);
					}

					public	function	setIdprojet_projet($id)	{
										$idprojet	=	(int)	$id;
										if	($idprojet	>=	1)	{
															$this->_idprojet_projet	=	$id;
										}	else	{
															echo	"<br>Erreur la valeur de l'id du projet doit être supérieur à 1<br>";
										}
					}

					public	function	getIdprojet_projet()	{
										return	$this->_idprojet_projet;
					}

					public	function	setIdformationeleve_formationeleve($id)	{
										$idformation	=	(int)	$id;
										if	($idformation	>=	1)	{
															$this->_idformationeleve_formationeleve	=	$id;
										}	else	{
															echo	"<br>Erreur la valeur de l'id de la formation eleve doit être supérieur à 1<br>";
										}
					}

					public	function	getIdformationeleve_formationeleve()	{
										return	$this->_idformationeleve_formationeleve;
					}

}

?>
