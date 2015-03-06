<?php

class Statutprojet {

    private $_idstatutprojet;
    private $_libellestatutprojet;

    public function __construct($idstatutprojet, $libellestatutprojet) {
        $this->setIdstatutprojet($idstatutprojet);
        $this->setLibellestatutprojet($libellestatutprojet);
    }

    public function getIdstatutprojet() {
        return $this->_idstatutprojet;
    }

    public function setIdstatutprojet($id) {
        $this->_idstatutprojet = (int) $id;
    }

    public function getLibellestatutprojet() {
        return $this->_libellestatutprojet;
    }

    public function setLibellestatutprojet($param) {
        $this->_libellestatutprojet = $param;
    }

}
