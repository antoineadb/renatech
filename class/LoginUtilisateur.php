<?php

class LoginUtilisateur {

    private $_loginUtilisateur;

    function __construct($loginUtilisateur) {
        $this->setLoginUtilisateur($loginUtilisateur);
    }

    public function getLoginUtilisateur() {
        return $this->_loginUtilisateur;
    }

    public function setLoginUtilisateur($loginUtilisateur) {
        $this->_loginUtilisateur = $loginUtilisateur;
    }

}