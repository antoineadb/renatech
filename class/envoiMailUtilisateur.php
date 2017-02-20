<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of envoiMailUtilisateur
 *
 * @author antoineadb
 */
class envoiMailUtilisateur {

    private $_mailUser;

    public function __construct($mailUser) {
        $this->setEnvoiMailUtilisateur($mailUser);
    }

    public function setEnvoiMailUtilisateur($param) {
        $this->_mailUser = $param;
    }

    public function getEnvoiMailUtilisateur() {
        return $this->_mailUser;
    }

}
