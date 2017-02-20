<?php

class Typeentreprise {

    private $_idtypeentreprise;
    private $_libelletypeentreprise;
    private $_libelletypeentrepriseen;
    private $_masquetypeentreprise;

    public function __construct($idtypeentreprise, $libelletypeentreprise, $masquetypeentreprise, $libelletypeentrepriseen) {
        $this->setIdtypeentreprise($idtypeentreprise);
        $this->setLibelletypeentreprise($libelletypeentreprise);
        $this->setLibelletypeentrepriseen($libelletypeentrepriseen);
        $this->setMasquetypeentreprise($masquetypeentreprise);
    }

    public function getIdtypeentreprise() {
        return $this->_idtypeentreprise;
    }

    public function setIdtypeentreprise($id) {
        $this->_idtypeentreprise = (int) $id;
    }

    public function getLibelletypeentreprise() {
        return $this->_libelletypeentreprise;
    }

    public function setLibelletypeentreprise($param) {
        $this->_libelletypeentreprise = $param;
    }

    public function getLibelletypeentrepriseen() {
        return $this->_libelletypeentrepriseen;
    }

    public function setLibelletypeentrepriseen($param) {
        $this->_libelletypeentrepriseen = $param;
    }

    public function getMasquetypeentreprise() {
        return $this->_masquetypeentreprise;
    }

    public function setMasquetypeentreprise($value) {
        $this->_masquetypeentreprise = $value;
    }
}