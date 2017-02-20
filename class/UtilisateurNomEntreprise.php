<?php

class UtilisateurNomEntreprise {

    private $_nomentreprise;
    private $_idutilisateur;

    public function __construct($nomentreprise, $idutilisateur) {
        $this->setNomentreprise($nomentreprise);
        $this->setIdutilisateur($idutilisateur);
    }

    public function setNomentreprise($param) {
        $this->_nomentreprise = $param;
    }

    public function getNomentreprise() {
        return $this->_nomentreprise;
    }

    public function setIdutilisateur($param) {
        $this->_idutilisateur = (int) $param;
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

}
