<?php

class Libelleapplication {

    private $_libellefrancais;
    private $_libelleanglais;
    private $_reflibelle;

    function __construct($libellefrancais, $libelleanglais, $reflibelle) {
        $this->setLibellefrancais($libellefrancais);
        $this->setLibelleanglais($libelleanglais);
        $this->setReflibelle($reflibelle);
    }

    public function setLibellefrancais($libellefrancais) {
        $this->_libellefrancais = $libellefrancais;
    }

    public function getLibellefrancais() {
        return $this->_libellefrancais;
    }

    public function setLibelleanglais($libelleanglais) {
        $this->_libelleanglais = $libelleanglais;
    }

    public function getLibelleanglais() {
        return $this->_libelleanglais;
    }

    public function setReflibelle($libellefrancais) {
        $this->_reflibelle = $libellefrancais;
    }

    public function getReflibelle() {
        return $this->_reflibelle;
    }

}
