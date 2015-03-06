<?php

class Autrethematique {

    private $_idautrethematique;
    private $_libelleautrethematique;

    public function __construct($idautrethematique, $libelleautrethematique) {
        $this->setIdautrethematique($idautrethematique);
        $this->setLibelleautrethematique($libelleautrethematique);
    }

    public function getIdautrethematique() {
        return $this->_idautrethematique;
    }

    public function setIdautrethematique($id) {
        $this->_idautrethematique = (int) $id;
    }

    public function getLibelleautrethematique() {
        return $this->_libelleautrethematique;
    }

    public function setLibelleautrethematique($libelleautrethematique) {
        $this->_libelleautrethematique = $libelleautrethematique;
    }

}