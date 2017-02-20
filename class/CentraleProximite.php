<?php

class CentraleProximite {

    private $_idCentraleProximite;
    private $_libelleCentraleProximite;
    private $_masqueCentraleProximite;
    private $_idRegion;

    function __construct($idCentraleProximite, $libelleCentraleProximite, $masqueCentraleProximite, $idRegion) {
        $this->setIdCentraleProximite($idCentraleProximite);
        $this->setLibelleCentraleProximite($libelleCentraleProximite);
        $this->setMasqueCentraleProximite($masqueCentraleProximite);
        $this->setIdRegion($idRegion);
    }

    function getIdRegion() {
        return $this->_idRegion;
    }

    function setIdRegion($idRegion) {
        $this->_idRegion = $idRegion;
    }

    function getIdCentraleProximite() {
        return $this->_idCentraleProximite;
    }

    function getMasqueCentraleProximite() {
        return $this->_masqueCentraleProximite;
    }

    function setMasqueCentraleProximite($masqueCentraleProximite) {
        $this->_masqueCentraleProximite = $masqueCentraleProximite;
    }

    function setIdCentraleProximite($idCentraleProximite) {
        $this->_idCentraleProximite = (int) $idCentraleProximite;
    }

    function getLibelleCentraleProximite() {
        return $this->_libelleCentraleProximite;
    }

    function setLibelleCentraleProximite($libelleCentraleProximite) {
        $this->_libelleCentraleProximite = $libelleCentraleProximite;
    }

}
