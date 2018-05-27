<?php

class UtilisateurVueProjets {

    private $_idutilisateur;
    private $_vueProjet;

    public function __construct($idutilisateur, $vueProjet) {
        $this->setIdutilisateur($idutilisateur);
        $this->setVueProjet($vueProjet);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function getVueProjet() {
        return $this->_vueProjet;
    }

    public function setVueProjet($id) {
        $this->_vueProjet = $id;
    }

}
