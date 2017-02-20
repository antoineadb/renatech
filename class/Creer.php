<?php

class Creer {

    private $_idprojet_projet;
    private $_idutilisateur_utilisateur;

    public function __construct($idutilisateur_utilisateur, $idprojet_projet) {
        $this->setIdutilisateur_utilisateur($idutilisateur_utilisateur);
        $this->setIdprojet_projet($idprojet_projet);
    }

    public function getIdutilisateur_utilisateur() {
        return $this->_idutilisateur_utilisateur;
    }

    public function setIdutilisateur_utilisateur($id) {
        $this->_idutilisateur_utilisateur = (int) $id;
    }

    public function getIdprojet_projet() {
        return $this->_idprojet_projet;
    }

    public function setIdprojet_projet($id) {
        $this->_idprojet_projet = (int) $id;
    }

}