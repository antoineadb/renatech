<?php

class Typeprojet {

    private $_idtypeprojet;
    private $_libelletypeprojet;
    private $_masquetypeprojet;
    private $_libelletypeprojeten;

    public function __construct($idtypeprojet, $libelletypeprojet, $masquetypeprojet, $libelletypeprojeten) {
        $this->setIdtypeprojet($idtypeprojet);
        $this->setLibelletypeprojet($libelletypeprojet);
        $this->setMasquetypeprojet($masquetypeprojet);
        $this->setLibelletypeprojeten($libelletypeprojeten);
    }

    public function getIdtypeprojet() {
        return $this->_idtypeprojet;
    }

    public function setIdtypeprojet($id) {
        $this->_idtypeprojet = (int) $id;
    }

    public function getLibelletypeprojet() {
        return $this->_libelletypeprojet;
    }

    public function setLibelletypeprojet($param) {
        $this->_libelletypeprojet = $param;
    }

    public function getMasquetypeprojet() {
        return $this->_masquetypeprojet;
    }

    public function setMasquetypeprojet($value) {
        $this->_masquetypeprojet = $value;
    }

    public function getLibelletypeprojeten() {
        return $this->_libelletypeprojeten;
    }

    public function setLibelletypeprojeten($param) {
        $this->_libelletypeprojeten = $param;
    }

}