<?php

class Projetpartenaire {

    private $_idpartenaire_partenaireprojet;
    private $_idprojet_projet;
    private $_idtypepartenaire_typepartenaire;

    public function __construct($idpartenaire_partenaireprojet, $idprojet_projet,$idtypepartenaire_typepartenaire) {
        $this->setIdpartenaire_partenaireprojet($idpartenaire_partenaireprojet);
        $this->setIdprojet_projet($idprojet_projet);
        $this->setIdtypepartenaire_typepartenaire($idtypepartenaire_typepartenaire);
    }

    public function getIdtypepartenaire_typepartenaire() {
        return $this->_idtypepartenaire_typepartenaire;
    }

    public function setIdtypepartenaire_typepartenaire($id) {
        $this->_idtypepartenaire_typepartenaire =(int) $id;
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
