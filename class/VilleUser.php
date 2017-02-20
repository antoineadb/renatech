<?php

class VilleUser {

    private $_villeUser;

    function __construct($villeUser) {
        $this->setVilleUser($villeUser);
    }

    public function setVilleUser($param) {
        $this->_villeUser = $param;
    }

    public function getVilleUser() {
        return $this->_villeUser;
    }

}