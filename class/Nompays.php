<?php

class Nompays {
    private $_nompays;
    public function __construct($nompays) {
       $this->setNompays($nompays);
    }
    public function getNompays() {
        return $this->_nompays;
    }

    public function setNompays($nompays) {
        $this->_nompays = $nompays;
    }

}