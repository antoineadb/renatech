<?php

class Partenairefromprojet {

    private $_centralepartenaireprojet;
    private $_partenaire1;

    public function __construct($centralepartenaireprojet, $partenaire1) {
        $this->setCentralepartenaireprojet($centralepartenaireprojet);
        $this->setPartenaire1($partenaire1);
    }

    public function getCentralepartenaireprojet() {
        return $this->_centralepartenaireprojet;
    }

    public function setCentralepartenaireprojet($centralepartenaireprojet) {
        $this->_centralepartenaireprojet = $centralepartenaireprojet;
    }

    public function getPartenaire1() {
        return $this->_partenaire1;
    }

    public function setPartenaire1($param) {
        $this->_partenaire1 = $param;
    }
}
