<?php

class Region {

    private $_idRegion;
    private $_libelleRegion;
    private $_masqueRegion;

    function __construct($idRegion, $libelleRegion, $masqueregion) {
        $this->setIdRegion($idRegion);
        $this->setLibelleRegion($libelleRegion);
        $this->setMasqueRegion($masqueregion);
    }

    function getIdRegion() {
        return $this->_idRegion;
    }

    function getMasqueRegion() {
        return $this->_masqueRegion;
    }

    function setMasqueRegion($masqueRegion) {
        $this->_masqueRegion = $masqueRegion;
    }

    function getLibelleRegion() {
        return $this->_libelleRegion;
    }

    function setIdRegion($idRegion) {
        $this->_idRegion = (int) $idRegion;
    }

    function setLibelleRegion($libelleRegion) {
        $this->_libelleRegion = $libelleRegion;
    }

}
