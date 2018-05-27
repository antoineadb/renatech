<?php

class Nomemployeur {

    private $_idemployeur;
    private $_libelleemployeur;
    private $_masquenomemployeur;
    private $_libelleemployeuren;

    public function __construct($idemployeur, $libelleemployeur, $masquenomemployeur, $libelleemployeuren) {
        $this->setIdemployeur($idemployeur);
        $this->setLibelleemployeur($libelleemployeur);
        $this->setMasquenomemployeur($masquenomemployeur);
        $this->setLibelleemployeuren($libelleemployeuren);
    }

    public function getIdemployeur() {
        return $this->_idemployeur;
    }

    public function setIdemployeur($id) {
        $this->_idemployeur = (int) $id;
    }

    public function getLibelleemployeur() {
        return $this->_libelleemployeur;
    }

    public function setLibelleemployeur($param) {
        $this->_libelleemployeur = $param;
    }

    public function getLibelleemployeuren() {
        return $this->_libelleemployeuren;
    }

    public function setLibelleemployeuren($param) {
        $this->_libelleemployeuren = $param;
    }

    public function getMasquenomemployeur() {
        return $this->_masquenomemployeur;
    }

    public function setMasquenomemployeur($value) {
        $this->_masquenomemployeur = $value;
    }

}