<?php

class UtilisateurTypeadmin {

    private $_idutilisateur;
    private $_idtypeutilisateur_typeutilisateur;
    private $_idcentrale_centrale;

    function __construct($idutilisateur, $idtypeutilisateur_typeutilisateur, $idcentrale_centrale) {
        $this->setIdutilisateur($idutilisateur);
        $this->setIdtypeutilisateur_typeutilisateur($idtypeutilisateur_typeutilisateur);
        $this->setIdcentrale_centrale($idcentrale_centrale);
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

    public function getIdcentrale_centrale() {
        return $this->_idcentrale_centrale;
    }

    public function setIdcentrale_centrale($idcentrale) {
        $this->_idcentrale_centrale = (int) $idcentrale;
    }

}