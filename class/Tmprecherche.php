<?php

class Tmprecherche {

    private $_porteur;
    private $_numero;

    public function __construct($porteur,$numero) {
        $this->setPorteur($porteur);
        $this->setNumero($numero);
    }

    public function setPorteur($param) {
        $this->_porteur = $param;
    }

    public function getPorteur() {
        return $this->_porteur;
    }

    public function setNumero($numero) {
        $this->_numero = $numero;
}

    public function getNumero() {
        return $this->_numero;
    }

}
