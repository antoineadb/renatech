<?php

class UtilisateurNomlabo {
    private $_idutilisateur;
    private $_nomlab;

    function __construct($idutilisateur, $nomlab) {
        $this->setIdutilisateur($idutilisateur);
        $this->setNomlab($nomlab);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function setNomlab($param) {
        $this->_nomlab = $param;
    }

    public function getNomlab() {
        return $this->_nomlab;
    }

}
