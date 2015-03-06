<?php

class AdresseUser {

    private $_adresse;

    public function __construct($adresse) {
        $this->setAdresse($adresse);
    }

    public function setAdresse($param) {
        $this->_adresse = $param;
    }

    public function getAdresse() {
        return $this->_adresse;
    }

}