<?php

class Projetsourcefinancement {

    private $_idprojet;
    private $_idsourcefinancement;

    public function __construct($idprojet, $idsourcefinancement) {
        $this->setIdprojet($idprojet);
        $this->setIdsourcefinancement($idsourcefinancement);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getIdsourcefinancement() {
        return $this->_idsourcefinancement;
    }

    public function setIdsourcefinancement($id) {
        $this->_idsourcefinancement = (int) $id;
    }

}
