<?php

class Typeformation {

    private $_idtypeformation;
    private $_libelletypeformation;
    private $_libelletypeformationen;
    private $_masquetypeformation;

    public function __construct($idtypeformation, $libelletypeformation, $libelletypeformationen,$masquetypeformation) {
        $this->setIdtypeformation($idtypeformation);
        $this->setLibelletypeformation($libelletypeformation);
        $this->setLibelletypeformationen($libelletypeformationen);
        $this->setMasquetypeformation($masquetypeformation);
    }

    public function getIdtypeformation() {
        return $this->_idtypeformation;
    }

    public function setIdtypeformation($id) {
        $this->_idtypeformation = (int) $id;
    }

    public function getLibelletypeformation() {
        return $this->_libelletypeformation;
    }

    public function setLibelletypeformation($libelletypeformation) {
        $this->_libelletypeformation = $libelletypeformation;
    }

    public function getLibelletypeformationen() {
        return $this->_libelletypeformationen;
    }

    public function setLibelletypeformationen($libelletypeformationen) {
        $this->_libelletypeformationen = $libelletypeformationen;
    }
    
    public function getMasquetypeformation() {
        return $this->_masquetypeformation;
    }

    public function setMasquetypeformation($value) {
        $this->_masquetypeformation = $value;
    }

}
