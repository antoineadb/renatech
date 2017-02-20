<?php

class UtilisateurNomresponsable {

    private $_idutilisateur;
    private $_nomresponsable;

    function __construct($idutilisateur, $nomresponsable) {
        $this->setIdutilisateur($idutilisateur);
        $this->setNomresponsable($nomresponsable);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function setNomresponsable($param) {
        $this->_nomresponsable = $param;
    }

    public function getNomresponsable() {
        return $this->_nomresponsable;
    }

}
