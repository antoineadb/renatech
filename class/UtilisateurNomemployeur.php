<?php

class UtilisateurNomemployeur {

    private $_idutilisateur;
    private $_idemployeur_nomemployeur;
    private $_idautrenomemployeur_autrenomemployeur;

    public function __construct($idutilisateur, $idemployeur_nomemployeur, $idautrenomemployeur_autrenomemployeur) {
        $this->setIdutilisateur($idutilisateur);
        $this->setIdemployeur_nomemployeur($idemployeur_nomemployeur);
        $this->setIdautrenomemployeur_autrenomemployeur($idautrenomemployeur_autrenomemployeur);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function getIdemployeur_nomemployeur() {
        return $this->_idemployeur_nomemployeur;
    }

    public function setIdemployeur_nomemployeur($idemployeur_nomemployeur) {
        $this->_idemployeur_nomemployeur = (int) $idemployeur_nomemployeur;
    }

    public function getIdautrenomemployeur_autrenomemployeur() {
        return $this->_idautrenomemployeur_autrenomemployeur;
    }

    public function setIdautrenomemployeur_autrenomemployeur($idautrenomemployeur_autrenomemployeur) {
        $this->_idautrenomemployeur_autrenomemployeur = (int) $idautrenomemployeur_autrenomemployeur;
    }

}
