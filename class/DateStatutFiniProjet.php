<?php

class DateStatutFiniProjet {

    private $_idprojet;
    private $_dateStatutFiniProjet;

    public function __construct($idprojet, $dateStatutFiniProjet) {

        $this->setIdprojet($idprojet);
        $this->setDateStatutFiniProjet($dateStatutFiniProjet);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getDateStatutFiniProjet() {
        return $this->_dateStatutFiniProjet;
    }

    public function setDateStatutFiniProjet($dateStatutFiniProjet) {
        $this->_dateStatutFiniProjet = $dateStatutFiniProjet;
    }

}
