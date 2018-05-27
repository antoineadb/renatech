<?php

class PrenomUtilisateur {

    private $_prenom;

    public function __construct($prenom) {
        $this->setPrenom($prenom);
    }

    public function setPrenom($param) {
        $this->_prenom = $param;
    }

    public function getPrenom() {
        return $this->_prenom;
    }

}
