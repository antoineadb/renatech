<?php

class UtilisateurType {

    private $_idutilisateur;
    private $_idtypeutilisateur_typeutilisateur;

    function __construct($idutilisateur, $idtypeutilisateur_typeutilisateur) {
        $this->setIdutilisateur($idutilisateur);
        $this->setIdtypeutilisateur_typeutilisateur($idtypeutilisateur_typeutilisateur);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($idutilisateur) {
        $this->_idutilisateur = (int) $idutilisateur;
    }

    public function getIdtypeutilisateur_typeutilisateur() {
        return $this->_idtypeutilisateur_typeutilisateur;
    }

    public function setIdtypeutilisateur_typeutilisateur($idtypeutilisateur_typeutilisateur) {
        $this->_idtypeutilisateur_typeutilisateur = (int) $idtypeutilisateur_typeutilisateur;
    }

}