<?php

class Qualitedemandeuraca {

    private $_idqualitedemandeuraca;
    private $_libellequalitedemandeuraca;

    public function __construct($idqualitedemandeuraca, $libellequalitedemandeuraca) {
        $this->setIdqualitedemandeuraca($idqualitedemandeuraca);
        $this->setLibellequalitedemandeuraca($libellequalitedemandeuraca);
    }

    public function getIdqualitedemandeuraca() {
        return $this->_idqualitedemandeuraca;
    }

    public function setIdqualitedemandeuraca($idqualitedemandeuraca) {
            $this->_idqualitedemandeuraca = (int)$idqualitedemandeuraca;
    }

    public function getLibellequalitedemandeuraca() {
        return $this->_libellequalitedemandeuraca;
    }

    public function setLibellequalitedemandeuraca($libellequalitedemandeuraca) {
        $this->_libellequalitedemandeuraca = $libellequalitedemandeuraca;
    }

}
