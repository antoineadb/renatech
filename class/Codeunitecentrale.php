<?php

class Codeunitecentrale {

    private $_codeunite;

    public function __construct($codeunite) {
        $this->setCodeunite($codeunite);
    }

    public function getCodeunite() {
        return $this->_codeunite;
    }

    public function setCodeunite($codeunite) {
        $this->_codeunite = $codeunite;
    }

}