<?php

class CentraleProximiteProjet {

    private $_idCentraleProximite;
    private $_idProjet;

    function __construct($idCentraleProximite, $idProjet) {
        $this->setIdCentraleProximite($idCentraleProximite);
        $this->setIdProjet($idProjet);
    }

    function getIdCentraleProximite() {
        return $this->_idCentraleProximite;
    }

    function getIdProjet() {
        return $this->_idProjet;
    }

    function setIdCentraleProximite($idCentraleProximite) {
        $this->_idCentraleProximite = $idCentraleProximite;
    }

    function setIdProjet($idProjet) {
        $this->_idProjet = $idProjet;
    }

}
