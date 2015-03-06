<?php

class Disciplinescientifique {

    private $_iddiscipline;
    private $_libellediscipline;
    private $_masquediscipline;
    private $_libelledisciplineen;

    public function __construct($iddiscipline, $libellediscipline, $masquediscipline, $libelledisciplineen) {
        $this->setIddiscipline($iddiscipline);
        $this->setLibellediscipline($libellediscipline);
        $this->setMasquediscipline($masquediscipline);
        $this->setLibelledisciplineen($libelledisciplineen);
    }

    public function getIddiscipline() {
        return $this->_iddiscipline;
    }

    public function setIddiscipline($id) {
        $this->_iddiscipline = (int) $id;
    }

    public function getLibellediscipline() {
        return $this->_libellediscipline;
    }

    public function setLibellediscipline($param) {
        $this->_libellediscipline = $param;
    }

    public function getMasquediscipline() {
        return $this->_masquediscipline;
    }

    public function setMasquediscipline($value) {
        $this->_masquediscipline = $value;
    }

    public function getLibelledisciplineen() {
        return $this->_libelledisciplineen;
    }

    public function setLibelledisciplineen($param) {
        $this->_libelledisciplineen = $param;
    }

}