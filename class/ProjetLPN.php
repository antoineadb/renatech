<?php

class	ProjetLPN	{

					private	$_idprojet;
					private	$_titre;
					private	$_numero;
					private	$_idstatutprojet;
					private	$_idtypeprojet;
					private	$_idsourcefinancement;
					private	$_libelleautresourcefinancement;
					private	$_idthematique;
					private	$_libelleautrethematique;
					private	$_dateprojet;
					private	$_contexte;
					private	$_description;
					private	$_idcentrale;
					private	$_confidentiel;
					private	$_devtechnologique;
					private	$_dateDebutProjet;

					function	__construct($idprojet,	$titre,	$numero,	$idcentrale,	$idthematique,	$libelleautrethematique,	$idstatutprojet,	$idtypeprojet,	$idsourcefinancement,	$libelleautresourcefinancement,	$confidentiel,	$description,	$dateprojet,	$contexte,	$devtechnologique,	$dateDebutProjet)	{
										$this->setIdprojet($idprojet);
										$this->setTitre($titre);
										$this->setNumero($numero);
										$this->setIdcentrale($idcentrale);
										$this->setIdthematique($idthematique);
										$this->setLibelleautrethematique($libelleautrethematique);
										$this->setIdstatutprojet($idstatutprojet);
										$this->setIdtypeprojet($idtypeprojet);
										$this->setIdsourcefinancement($idsourcefinancement);
										$this->setLibelleautresourcefinancement($libelleautresourcefinancement);
										$this->setConfidentiel($confidentiel);
										$this->setDescription($description);
										$this->setDateprojet($dateprojet);
										$this->setContexte($contexte);
										$this->setDevtechnologique($devtechnologique);
										$this->setDateDebutProjet($dateDebutProjet);
					}

					public	function	getIdprojet()	{
										return	$this->_idprojet;
					}

					public	function	setIdprojet($idprojet)	{
										$this->_idprojet	=	(int)	$idprojet;
					}

					public	function	setTitre($param)	{
										$this->_titre	=	$param;
					}
					
					public	function	getTitre()	{
										return	$this->_titre;
					}

					public	function	getNumero()	{
										return	$this->_numero;
					}

					public	function	setNumero($numero)	{
										$this->_numero	=	$numero;
					}

					public	function	getIcentrale()	{
										return	$this->_idcentrale;
					}

					public	function	setIdcentrale($idcentrale)	{
										$this->_idcentrale	=	(int)	$idcentrale;
					}

					public	function	getIdthematique()	{
										return	$this->_idthematique;
					}

					public	function	setIdthematique($idthematique)	{
										$this->_idthematique	=	(int)	$idthematique;
					}

					public	function	getLibelleautrethematique()	{
										return	$this->_libelleautrethematique;
					}

					public	function	setLibelleautrethematique($libelleautrethematique)	{
										$this->_libelleautrethematique	=	$libelleautrethematique;
					}

					public	function	getIdstatutprojet()	{
										return	$this->_idstatutprojet;
					}

					public	function	setIdstatutprojet($idstatutprojet)	{
										$this->_idstatutprojet	=	(int)	$idstatutprojet;
					}

					public	function	getIdtypeprojet()	{
										return	$this->_idtypeprojet;
					}

					public	function	setIdtypeprojet($idtypeprojet)	{
										$this->_idtypeprojet	=	(int)	$idtypeprojet;
					}

					public	function	getIdsourcefinancement()	{
										return	$this->_idsourcefinancement;
					}

					public	function	setIdsourcefinancement($idsourcefinancement)	{
										$this->_idsourcefinancement	=	(int)	$idsourcefinancement;
					}

					public	function	getLibelleautresourcefinancement()	{
										return	$this->_libelleautresourcefinancement;
					}

					public	function	setLibelleautresourcefinancement($libelleautresourcefinancement)	{
										$this->_libelleautresourcefinancement	=	$libelleautresourcefinancement;
					}

					public	function	getConfidentiel()	{
										return	$this->_confidentiel;
					}

					public	function	setConfidentiel($confidentiel)	{
										$this->_confidentiel	=	$confidentiel;
					}

					public	function	getDescription()	{
										return	$this->_description;
					}

					public	function	setDescription($description)	{
										$this->_description	=	$description;
					}

					public	function	getDateprojet()	{
										return	$this->_dateprojet;
					}

					public	function	setDateprojet($dateprojet)	{
										$this->_dateprojet	=	$dateprojet;
					}

					public	function	getContexte()	{
										return	$this->_contexte;
					}

					public	function	setContexte($contexte)	{
										$this->_contexte	=	$contexte;
					}

					public	function	getDevtechnologique()	{
										return	$this->_devtechnologique;
					}

					public	function	setDevtechnologique($devtechnologique)	{
										$this->_devtechnologique	=	$devtechnologique;
					}

					public	function	setDateDebutProjet($debutprojet)	{
										$this->_dateDebutProjet	=	$debutprojet;
					}

					public	function	getDateDebutProjet()	{
										return	$this->_dateDebutProjet;
					}

}
