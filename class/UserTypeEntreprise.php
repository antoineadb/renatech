<?php

class UserTypeEntreprise {

    private $_idtypeentreprise;
    private $_idutilisateur;

    public function __construct($idtypeentreprise, $idutilisateur) {
        $this->setIdtypeentreprise($idtypeentreprise);
        $this->setIdutilisateur($idutilisateur);
    }

    public function setIdtypeentreprise($param) {
        $this->_idtypeentreprise = (int) $param;
    }

    public function getIdtypeentreprise() {
        return $this->_idtypeentreprise;
    }

    public function setIdutilisateur($param) {
        $this->_idutilisateur = (int) $param;
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

}
