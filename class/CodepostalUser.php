<?php

class CodepostalUser {
private  $_codePostal;
    public function __construct($codepostal) {
        $this->setCodePostal($codepostal);
    }

    public function setCodePostal($param) {
        $this->_codePostal = $param;
    }

    public function getCodePostal() {
        return $this->_codePostal;
    }

}