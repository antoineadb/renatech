<?php

class AutreThematiques {

    private $_idprojet;
    private $_idautrethematique;

    function __construct($idprojet, $idautrethematique) {
        $this->setIdprojet($idprojet);
        $this->setIdautrethematique($idautrethematique);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getIdautrethematique() {
        return $this->_idautrethematique;
    }

    public function setIdautrethematique($idautrethematique) {
        $this->_idautrethematique = $idautrethematique;
    }

}
