<?php

class ConcernePhase1 {

    private $_idprojet_projet;
    private $_idstatutprojet_statutprojet;

    public function __construct($idprojet_projet, $idstatutprojet_statutprojet) {
        $this->setIdprojet_projet($idprojet_projet);
        $this->setIdstatutprojet_statutprojet($idstatutprojet_statutprojet);
    }

    public function getIdprojet_projet() {
        return $this->_idprojet_projet;
    }

    public function setIdprojet_projet($id) {
        $this->_idprojet_projet = (int) $id;
    }

    public function getIdstatutprojet_statutprojet() {
        return $this->_idstatutprojet_statutprojet;
    }

    public function setIdstatutprojet_statutprojet($id) {
        $this->_idstatutprojet_statutprojet = (int) $id;
    }

}