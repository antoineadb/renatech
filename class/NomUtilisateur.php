<?php

class NomUtilisateur {

    private $_nom;

    public function __construct($nom) {
        $this->setNom($nom);
    }

    public function setNom($param) {
        $this->_nom = $param;
    }

    public function getNom() {
        return $this->_nom;
    }

}
