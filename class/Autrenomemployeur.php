<?php

class Autrenomemployeur {

    private $_idautrenomemployeur;
    private $_libelleautrenomemployeur;

    public function __construct($idautrenomemployeur, $libelleautrenomemployeur) {
        $this->setIdautrenomemployeur($idautrenomemployeur);
        $this->setLibelleautreautrenomemployeur($libelleautrenomemployeur);
    }

    public function getIdautrenomemployeur() {
        return $this->_idautrenomemployeur;
    }

    public function setIdautrenomemployeur($id) {
            $this->_idautrenomemployeur =  (int)$id;
    }

    public function getLibelleautrenomemployeur() {
        return $this->_libelleautrenomemployeur;
    }

    public function setLibelleautreautrenomemployeur($libelleautrenomemployeur) {
        $this->_libelleautrenomemployeur = $libelleautrenomemployeur;
    }

}