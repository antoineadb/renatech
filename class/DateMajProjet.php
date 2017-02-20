<?php

class DateMajProjet {

    private $_idprojet;
    private $_datemajprojet;

    public function __construct($idprojet, $datemajprojet) {
        $this->setIdprojet($idprojet);
        $this->setDateMajProjet($datemajprojet);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getDateMajProjet() {
        return $this->_datemajprojet;
    }

    public function setDateMajProjet($datemajprojet) {
        $this->_datemajprojet = $datemajprojet;
    }

}
