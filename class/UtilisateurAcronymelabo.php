<?php

class UtilisateurAcronymelabo {

    private $_acronymelaboratoire;
    private $_idutilisateur;
    

    public function __construct($acronymelaboratoire,$idutilisateur) {
        $this->setAcronymelaboratoire($acronymelaboratoire);
        $this->setIdutilisateur($idutilisateur);
    }

    public function setAcronymelaboratoire($param) {
        $this->_acronymelaboratoire = $param;
    }

    public function getAcronymelaboratoire() {
        return $this->_acronymelaboratoire;
    }
    public function setIdutilisateur($param) {
        $this->_idutilisateur = (int) $param;
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

}