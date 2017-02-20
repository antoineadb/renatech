<?php

class DateStatutCloturerProjet {

    private $_idprojet;
    private $_dateStatutCloturerProjet;

    public function __construct($idprojet, $dateStatutCloturerProjet) {
        $this->setIdprojet($idprojet);
        $this->setDateStatutCloturerProjet($dateStatutCloturerProjet);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getDateStatutCloturerProjet() {
        return $this->_dateStatutCloturerProjet;
    }

    public function setDateStatutCloturerProjet($dateStatutCloturerProjet) {
        $this->_dateStatutCloturerProjet = $dateStatutCloturerProjet;
    }

}