<?php

class Projetpartenaire {

    private $_idpartenaire_partenaireprojet;
    private $_idprojet_projet;

    public function __construct($idpartenaire_partenaireprojet, $idprojet_projet) {
        $this->setIdpartenaire_partenaireprojet($idpartenaire_partenaireprojet);
        $this->setIdprojet_projet($idprojet_projet);
    }

    public function getIdpartenaire_partenaireprojet() {
        return $this->_idpartenaire_partenaireprojet;
    }

    public function setIdpartenaire_partenaireprojet($id) {
        $this->_idpartenaire_partenaireprojet =(int) $id;
    }

    public function getIdprojet_projet() {
        return $this->_idprojet_projet;
    }

    public function setIdprojet_projet($id) {
        $this->_idprojet_projet = (int) $id;
    }

}
