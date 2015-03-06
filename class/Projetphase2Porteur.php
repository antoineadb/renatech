<?php

class Projetphase2Porteur {
    private $_idprojet;
    private $_porteur;

    public function __construct($idprojet, $porteur) {
        $this->setIdprojet($idprojet);
        $this->setPorteur($porteur);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getPorteur() {
        return $this->_porteur;
    }

    public function setPorteur($param) {
        $this->_porteur = $param;
    }
}
