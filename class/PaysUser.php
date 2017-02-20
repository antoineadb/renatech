<?php

class PaysUser {

    private $_idpays_pays;

    public function __construct($idpays_pays) {
        $this->setIdpays_pays($idpays_pays);
    }

    public function getIdpays_pays() {
        return $this->_idpays_pays;
    }

    public function setIdpays_pays($idpays_pays) {
        $this->_idpays_pays = (int) $idpays_pays;
    }

}
