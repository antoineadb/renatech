<?php

class DescriptionCentraleProximiteProjet {

 private $_idprojet;
    private $_description;
    private $_centraleproximite;

    function __construct($idprojet, $description,$centraleproximite) {
        $this->setIdprojet($idprojet);
        $this->setDescription($description);
        $this->setCentraleproximite($centraleproximite);        
    }

    function getIdprojet() {
        return $this->_idprojet;
    }
    function setIdprojet($idprojet) {
        $this->_idprojet = $idprojet;
    }

    function getDescription() {
        return $this->_description;
    }

    function getCentraleproximite() {
        return $this->_centraleproximite;
    }

    function setDescription($_description) {
        $this->_description = $_description;
    }

    function setCentraleproximite($_centraleproximite) {
        $this->_centraleproximite = $_centraleproximite;
    }
}
