<?php

class Partenaireprojet {

    private $_idpartenaire;    
    private $_nomlaboentreprise;
    
    public function __construct($idpartenaire,$nomlaboentreprise) {
        $this->setIdpartenaire($idpartenaire);        
        $this->setNomlaboentreprise($nomlaboentreprise);
    }

    public function getIdpartenaire() {
        return $this->_idpartenaire;
    }

    public function setIdpartenaire($id) {
        $this->_idpartenaire = (int) $id;
    }    
    
    
    public function getNomlaboentreprise() {
        return $this->_nomlaboentreprise;
    }

    public function setNomlaboentreprise($libellediscipline) {
        $this->_nomlaboentreprise = $libellediscipline;
    }

}