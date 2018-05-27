<?php

class Autresourcefinancement {

    private $_idautresourcefinancement;
    private $_libelleautresourcefinancement;

    public function __construct($idautresourcefinancement, $libelleautresourcefinancement) {
        $this->setIdautresourcefinancement($idautresourcefinancement);
        $this->setLibelleautresourcefinancement($libelleautresourcefinancement);
    }

    public function getIdautresourcefinancement() {
        return $this->_idautresourcefinancement;
    }

    public function setIdautresourcefinancement($id) {
        $this->_idautresourcefinancement = (int) $id;
    }

    public function getLibelleautresourcefinancement() {
        return $this->_libelleautresourcefinancement;
    }

    public function setLibelleautresourcefinancement($libelleautresourcefinancement) {
        $this->_libelleautresourcefinancement = $libelleautresourcefinancement;
    }

}