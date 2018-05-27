<?php

class Acronyme {

    private $_acronyme;

    public function __construct($acronyme) {
        $this->setAcronyme($acronyme);
    }

    public function getAcronyme() {
        return $this->_acronyme;
    }

    public function setAcronyme($param) {

        $this->_acronyme = $param;
    }

}
