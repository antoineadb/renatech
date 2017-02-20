<?php

class ProjetAcrosourcefinancement {

    private $_idprojet;
    private $_acronymesource;
    private $_idsourcefinancement;

    public function __construct($idprojet, $acronymesource, $idsourcefinancement) {
        $this->setIdprojet($idprojet);
        $this->setAcronymesource($acronymesource);
        $this->setIdsourcefinancement($idsourcefinancement);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getAcronymesource() {
        return $this->_acronymesource;
    }

    public function setAcronymesource($param) {
        $this->_acronymesource = $param;
    }

    public function getIdsourcefinancement() {
        return $this->_idsourcefinancement;
    }

    public function setIdsourcefinancement($id) {
        $this->_idsourcefinancement = (int) $id;
    }

}
