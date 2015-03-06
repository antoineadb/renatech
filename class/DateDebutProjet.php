<?php

class DateDebutProjet {

    private $_idprojet;
    private $_datedebutprojet;

    public function __construct($idprojet, $datedebutprojet) {

        $this->setIdprojet($idprojet);
        $this->setDatedebutprojet($datedebutprojet);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getDatedebutprojet() {
        return $this->_datedebutprojet;
    }

    public function setDatedebutprojet($datedebutprojet) {
        $this->_datedebutprojet = $datedebutprojet;
    }

}