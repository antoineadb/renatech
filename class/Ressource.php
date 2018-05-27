<?php

class Ressource {

    private $_idressource;
    private $_libelleressource;
    private $_masqueressource;
    private $_libelleressourceen;

    public function __construct($idressource, $libelleressource, $masqueressource, $libelleressourceen) {
        $this->setIdressource($idressource);
        $this->setLibelleressource($libelleressource);
        $this->setMasqueressource($masqueressource);
        $this->setLibelleressourceen($libelleressourceen);
    }

    public function getIdressource() {
        return $this->_idressource;
    }

    public function setIdressource($id) {
        $this->_idressource = (int) $id;
    }

    public function getLibelleressource() {
        return $this->_libelleressource;
    }

    public function setLibelleressource($libelleressource) {
        $this->_libelleressource = $libelleressource;
    }

    public function getMasqueressource() {
        return $this->_masqueressource;
    }

    public function setMasqueressource($value) {
        $this->_masqueressource = $value;
    }

    public function getLibelleressourceen() {
        return $this->_libelleressourceen;
    }

    public function setLibelleressourceen($libelleressource) {
        $this->_libelleressourceen = $libelleressource;
    }

}
