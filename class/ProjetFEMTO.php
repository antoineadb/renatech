<?php

class	ProjetFEMTO	{

					private	$_idprojet;
					private	$_titre;
					private	$_numero;
					private	$_acronyme;
					private	$_datedebutprojet;
					private	$_dateprojet;
					private	$_dureeprojet;
					private	$_idperiodicite;
					private	$_objectif;
					private	$_descriptif;
					private	$_idsourcefinancement;
					private	$_centralepartenaireprojet;
					private	$_nomPartenaire01;
					private	$_confidentiel;
					private	$_idtypeprojet_typeprojet;

					function	__construct($idprojet,	$titre,	$numero,	$acronyme,	$datedebutprojet,	$dateprojet,	$dureeprojet,	$idperiodicite,	$objectif,	$descriptif,	$idsourcefinancement,	$centralepartenaireprojet,	$nomPartenaire01,$confidentiel,$idtypeprojet)	{

										$this->setIdprojet($idprojet);
										$this->setTitre($titre);
										$this->setNumero($numero);
										$this->setAcronyme($acronyme);
										$this->setDatedebutprojet($datedebutprojet);
										$this->setDateprojet($dateprojet);
										$this->setDureeprojet($dureeprojet);
										$this->setIdperiodicite($idperiodicite);
										$this->setContexte($objectif);
										$this->setDescription($descriptif);
										$this->setIdsourcefinancement($idsourcefinancement);
										$this->setCentralepartenaireprojet($centralepartenaireprojet);
										$this->setPartenaire1($nomPartenaire01);
										$this->setConfidentiel($confidentiel);
										$this->setIdtypeprojet($idtypeprojet);
					}


					public	function	getIdprojet()	{
										return	$this->_idprojet;
					}

					public	function	setIdprojet($idprojet)	{
										$this->_idprojet	=	(int)	$idprojet;
					}

					public	function	getTitre()	{
										return	$this->_titre;
					}

					public	function	setTitre($param)	{
										$this->_titre	=	$param;
					}

					public	function	getNumero()	{
										return	$this->_numero;
					}

					public	function	setNumero($numero)	{
										$this->_numero	=	$numero;
					}

					public	function	getAcronyme()	{
										return	$this->_acronyme;
					}

					public	function	setAcronyme($param)	{
										$this->_acronyme	=	$param;
					}

					public	function	getDatedebutprojet()	{
										return	$this->_datedebutprojet;
					}

					public	function	setDatedebutprojet($datedebutprojet)	{
										$this->_datedebutprojet	=	$datedebutprojet;
					}

					public	function	getDateprojet()	{
										return	$this->_dateprojet;
					}

					public	function	setDateprojet($dateprojet)	{
										$this->_dateprojet	=	$dateprojet;
					}

					public	function	getDureeprojet()	{
										return	$this->_dureeprojet;
					}

					public	function	setDureeprojet($dureeprojet)	{
										$this->_dureeprojet	=	(int)	$dureeprojet;
					}

					public	function	getIdperiodicite()	{
										return	$this->_idperiodicite;
					}

					public	function	setIdperiodicite($id)	{
										$this->_idperiodicite	=	(int)	$id;
					}

					public	function	getContexte()	{
										return	$this->_objectif;
					}

					public	function	setContexte($contexte)	{
										$this->_objectif	=	$contexte;
					}

					public	function	getDescription()	{
										return	$this->_descriptif;
					}

					public	function	setDescription($description)	{
										$this->_descriptif	=	$description;
					}

					public	function	getIdsourcefinancement()	{
										return	$this->_idsourcefinancement;
					}

					public	function	setIdsourcefinancement($id)	{
										$this->_idsourcefinancement	=	(int)	$id;
					}

					public	function	getCentralepartenaireprojet()	{
										return	$this->_centralepartenaireprojet;
					}

					public	function	setCentralepartenaireprojet($centralepartenaireprojet)	{
										$this->_centralepartenaireprojet	=	$centralepartenaireprojet;
					}

					public	function	getPartenaire1()	{
										return	$this->_nomPartenaire01;
					}

					public	function	setPartenaire1($param)	{
										$this->_nomPartenaire01	=	$param;
					}
					 public function getConfidentiel() {
        return $this->_confidentiel;
    }

    public function setConfidentiel($confidentiel) {
        $this->_confidentiel = $confidentiel;
    }

				 public function getIdtypeprojet() {
        return $this->_idtypeprojet_typeprojet;
    }

    public function setIdtypeprojet($idtypeprojet) {
        $this->_idtypeprojet_typeprojet = (int) $idtypeprojet;
    }

}
