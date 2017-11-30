<?php

class CentraleProximite {

    private $_idCentraleProximite;
    private $_libelleCentraleProximite;
    private $_masqueCentraleProximite;
    private $_idRegion;    
    private $_id_responsable_centrale_proximite;

    function __construct($idCentraleProximite, $libelleCentraleProximite, $masqueCentraleProximite, $idRegion,$id_responsable_centrale_proximite) {
        $this->setIdCentraleProximite($idCentraleProximite);
        $this->setLibelleCentraleProximite($libelleCentraleProximite);
        $this->setMasqueCentraleProximite($masqueCentraleProximite);
        $this->setIdRegion($idRegion);
        $this->setIdResponsableCentraleProximite($id_responsable_centrale_proximite);
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
    function setIdResponsableCentraleProximite($id_responsable_centrale_proximite){
        $this->_id_responsable_centrale_proximite = $id_responsable_centrale_proximite ;
    }
    
    function getIdResponsableCentraleProximite(){
        return $this->_id_responsable_centrale_proximite;
    }

}
