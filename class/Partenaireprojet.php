<?php

class Partenaireprojet {

    private $_idpartenaire;
    private $_nompartenaire;
    private $_nomlaboentreprise;

    public function __construct($idpartenaire, $nompartenaire, $nomlaboentreprise) {
        $this->setIdpartenaire($idpartenaire);
        $this->setNompartenaire($nompartenaire);
        $this->setNomlaboentreprise($nomlaboentreprise);
    }

    public function getIdpartenaire() {
        return $this->_idpartenaire;
    }

    public function setIdpartenaire($id) {
        $this->_idpartenaire = (int) $id;
    }

    public function getNompartenaire() {
        return $this->_nompartenaire;
    }

    public function setNompartenaire($libellediscipline) {
        $this->_nompartenaire = $libellediscipline;
    }

    public function getNomlaboentreprise() {
        return $this->_nomlaboentreprise;
    }

    public function setNomlaboentreprise($libellediscipline) {
        $this->_nomlaboentreprise = $libellediscipline;
    }

}