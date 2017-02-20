<?php

class Autresqualite {

    private $_idautresqualite;
    private $_libelleautresqualite;

    function __construct($idautresqualite, $libelleautresqualite) {
        $this->setIdautresqualite($idautresqualite);
        $this->setLibelleautresqualite($libelleautresqualite);
    }

    public function getIdautresqualite() {
        return $this->_idautresqualite;
    }

    public function setIdautresqualite($idautresqualite) {
        $this->_idautresqualite = (int) $idautresqualite;
    }

    public function getLibelleautresqualite() {
        return $this->_libelleautresqualite;
    }

    public function setLibelleautresqualite($libelleautresqualite) {
        $this->_libelleautresqualite = $libelleautresqualite;
    }

}
