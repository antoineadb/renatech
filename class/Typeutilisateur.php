<?php

class Typeutilisateur {

    private $_idtypeutilisateur;
    private $_libelletypeutilisateur;

    public function __construct($idtypeutilisateur, $libelletypeutilisateur) {
        $this->setIdtypeutilisateur($idtypeutilisateur);
        $this->setLibelletypeutilisateur($libelletypeutilisateur);
    }

    public function getIdtypeutilisateur() {
        return $this->_idtypeutilisateur;
    }

    public function setIdtypeutilisateur($id) {
        $this->_idtypeutilisateur = (int) $id;
    }

    public function getLibelletypeutilisateur() {
        return $this->_libelletypeutilisateur;
    }

    public function setLibelletypeutilisateur($libelletypeutilisateur) {
        $this->_libelletypeutilisateur = $libelletypeutilisateur;
    }

}