<?php

class Sourcefinancement {

    private $_idsourcefinancement;
    private $_libellesourcefinancement;
    private $_masquesourcefinancement;
    private $_libellesourcefinancementen;

    public function __construct($idsourcefinancement, $libellesourcefinancement,$masquesourcefinancement,$libellesourcefinancementen) {
        $this->setIdsourcefinancement($idsourcefinancement);
        $this->setLibellesourcefinancement($libellesourcefinancement);
        $this->setMasquesourcefinancement($masquesourcefinancement);
        $this->setLibellesourcefinancementen($libellesourcefinancementen);
    }

    public function getIdsourcefinancement() {
        return $this->_idsourcefinancement;
    }

    public function setIdsourcefinancement($id) {
        $this->_idsourcefinancement = $id;
    }

    public function getLibellesourcefinancement() {
        return $this->_libellesourcefinancement;
    }

    public function setLibellesourcefinancement($libellesourcefinancement) {
        $this->_libellesourcefinancement = $libellesourcefinancement;
    }
    public function getMasquesourcefinancement() {
        return $this->_masquesourcefinancement;
    }

    public function setMasquesourcefinancement($value) {
        $this->_masquesourcefinancement = $value;
    }
    public function getLibellesourcefinancementen() {
        return $this->_libellesourcefinancementen;
    }

    public function setLibellesourcefinancementen($libellesourcefinancement) {
        $this->_libellesourcefinancementen = $libellesourcefinancement;
    }
}