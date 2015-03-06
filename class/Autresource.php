<?php

class Autresource {

    private $_idprojet;
    private $_idautresourcefinancement;

    function __construct($idprojet, $idautresource) {
        $this->setIdprojet($idprojet);
        $this->setIdautresourcefinancement($idautresource);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getIdautresourcefinancement() {
        return $this->_idautresourcefinancement;
    }

    public function setIdautresourcefinancement($idautresourcefinancement) {
        $this->_idautresourcefinancement = $idautresourcefinancement;
    }

}
