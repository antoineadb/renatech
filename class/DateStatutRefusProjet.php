<?php

class DateStatutRefusProjet {

    private $_idprojet;
    private $_daterefusprojet;

    public function __construct($idprojet, $daterefusprojet) {
        $this->setIdprojet($idprojet);
        $this->setDaterefusprojet($daterefusprojet);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getDaterefusprojet() {
        return $this->_daterefusprojet;
    }

    public function setDaterefusprojet($daterefusprojet) {
        $this->_daterefusprojet = $daterefusprojet;
    }

}