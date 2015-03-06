<?php

class Tutelle {

    private $_idtutelle;
    private $_libelletutelle;
    private $_masquetutelle;
    private $_libelletutelleen;
    public function __construct($idtutelle, $libelletutelle,$masquetutelle,$libelletutelleen) {
        $this->setIdtutelle($idtutelle);
        $this->setLibelletutelle($libelletutelle);
        $this->setMasquetutelle($masquetutelle);
        $this->setLibelletutelleen($libelletutelleen);
    }

    public function getIdtutelle() {
        return $this->_idtutelle;
    }

    public function setIdtutelle($id) {
        $this->_idtutelle = (int) $id;
    }

    public function getLibelletutelle() {
        return $this->_libelletutelle;
    }
  
    public function setLibelletutelle($param) {
            $this->_libelletutelle = $param;
    }
    
     public function getMasquetutelle() {
        return $this->_masquetutelle;
    }

    public function setMasquetutelle($value) {
        $this->_masquetutelle = $value;
    }
    
     public function getLibelletutelleen() {
        return $this->_libelletutelleen;
    }
  
    public function setLibelletutelleen($param) {
            $this->_libelletutelleen = $param;
    }

}
