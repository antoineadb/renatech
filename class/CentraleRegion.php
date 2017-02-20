<?php

class CentraleRegion {

    private $_idCentrale;
    private $_idRegion;

    function __construct($idCentrale, $idRegion) {
        $this->setIdCentrale($idCentrale);
        $this->setIdRegion($idRegion);
    }

    function getIdCentrale() {
        return $this->_idCentrale;
    }

    function getIdRegion() {
        return $this->_idRegion;
    }

    function setIdCentrale($idCentrale) {
        $this->_idCentrale =  (int) $idCentrale;
    }

    function setIdRegion($idRegion) {
        $this->_idRegion =  (int) $idRegion;
    }

}
