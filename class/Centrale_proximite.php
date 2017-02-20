<?php

class Centrale_proximite {

    private $_idcentrale_proximite;
    private $_nom_centrale_proximite;

    function __construct($idcentrale_proximite, $nom_centrale_proximite) {
        $this->setIdcentrale_proximite($idcentrale_proximite);
        $this->setNom_centrale_proximite($_nom_centrale_proximite);
    }

    function getIdcentrale_proximite() {
        return $this->_idcentrale_proximite;
    }

    function getNom_centrale_proximite() {
        return $this->_nom_centrale_proximite;
    }

    function setIdcentrale_proximite($idcentrale_proximite) {
        $this->_idcentrale_proximite = $idcentrale_proximite;
    }

    function setNom_centrale_proximite($nom_centrale_proximite) {
        $this->_nom_centrale_proximite = $nom_centrale_proximite;
    }

}
