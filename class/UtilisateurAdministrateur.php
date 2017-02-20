<?php

class UtilisateurAdministrateur {

    private $_idutilisateur;
    private $_administrateur;

    public function __construct($idutilisateur, $administrateur) {
        $this->setIdutilisateur($idutilisateur);
        $this->setAdministrateur($administrateur);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function getAdministrateur() {
        return $this->_administrateur;
    }

    public function setAdministrateur($id) {
        $this->_administrateur = (int) $id;
    }

}
