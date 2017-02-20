<?php

class TelephoneUser {

    private $_telUser;

    function __construct($telephone) {
        $this->setTel($telephone);
    }

    public function setTel($param) {
        $this->_telUser = $param;
    }

    public function getTelUser() {
        return $this->_telUser;
    }

}
