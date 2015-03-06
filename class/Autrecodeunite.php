<?php

class Autrecodeunite {

    private $_idautrecodeunite;
    private $_libelleautrecodeunite;

    public function __construct($idautrecodeunite, $libelleautrecodeunite) {
        $this->setIdautrecodeunite($idautrecodeunite);
        $this->setLibelleautrecodeunite($libelleautrecodeunite);
    }

    public function getIdautrecodeunite() {
        return $this->_idautrecodeunite;
    }

    public function setIdautrecodeunite($id) {
        $this->_idautrecodeunite = (int) $id;
    }

    public function getLibelleautrecodeunite() {
        return $this->_libelleautrecodeunite;
    }

    public function setLibelleautrecodeunite($libelleautrecodeunite) {
        $this->_libelleautrecodeunite = $libelleautrecodeunite;
    }

}