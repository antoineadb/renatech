<?php

class Projet_centraleproximite {

    private $_idprojet;
    private $_idcentrale_proximite;

    function __construct($idprojet, $idcentrale_proximite) {
        $this->setIdprojet($idprojet);
        $this->setIdcentrale_proximite($idcentrale_proximite);
    }

    function getIdprojet() {
        return $this->_idprojet;
    }

    function getIdcentrale_proximite() {
        return $this->_idcentrale_proximite;
    }

    function setIdprojet($idprojet) {
        $this->_idprojet = $idprojet;
    }

    function setIdcentrale_proximite($idcentrale_proximite) {
        $this->_idcentrale_proximite = $idcentrale_proximite;
    }

}
