<?php

class FaxUser {

    private $_faxUser;

    function __construct($faxUser) {
        $this->setFaxUser($faxUser);
    }

    public function setFaxUser($param) {
        $this->_faxUser = $param;
    }

    public function getFaxUser() {
        return $this->_faxUser;
    }

}