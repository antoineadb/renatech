<?php

class Secteuractivite {

    private $_idsecteuractivite;
    private $_libellesecteuractivite;
    private $_libellesecteuractiviteen;
    private $_masquesecteuractivite;

    public function __construct($idsecteuractivite, $libellesecteuractivite, $libellesecteuractiviteen, $masquesecteuractivite) {
        $this->setIdsecteuractivite($idsecteuractivite);
        $this->setLibellesecteuractivite($libellesecteuractivite);
        $this->setLibellesecteuractiviteen($libellesecteuractiviteen);
        $this->setMasquesecteuractivite($masquesecteuractivite);
    }

    public function getIdsecteuractivite() {
        return $this->_idsecteuractivite;
    }

    public function setIdsecteuractivite($id) {
        $this->_idsecteuractivite = (int) $id;
    }

    public function getLibellesecteuractivite() {
        return $this->_libellesecteuractivite;
    }

    public function setLibellesecteuractivite($param) {
        $this->_libellesecteuractivite = $param;
    }

    public function getLibellesecteuractiviteen() {
        return $this->_libellesecteuractiviteen;
    }

    public function setLibellesecteuractiviteen($param) {
        $this->_libellesecteuractiviteen = $param;
    }

    public function getMasquesecteuractivite() {
        return $this->_masquesecteuractivite;
    }

    public function setMasquesecteuractivite($value) {
        $this->_masquesecteuractivite = $value;
    }
}

