<?php

class Porteurprojet {

    private $_porteurprojet;
    private $_numero;

    public function __construct($porteurprojet, $numero) {
        $this->setPorteurprojet($porteurprojet);
        $this->setNumero($numero);
    }

    public function getPorteurprojet() {
        return $this->_porteurprojet;
    }

    public function setPorteurprojet($porteurprojet) {
        $this->_porteurprojet = $porteurprojet;
    }

    public function getNumero() {
        return $this->_numero;
    }

    public function setNumero($numero) {
        $this->_numero = $numero;
    }

}
