<?php

class MailUtilisateur {

    private $_mailUtilisateur;

    function __construct($mailUtilisateur) {
        $this->setMailUtilisateur($mailUtilisateur);
    }

    public function getMailUtilisateur() {
        return $this->_mailUtilisateur;
    }

    public function setMailUtilisateur($mailUtilisateur) {
        $this->_mailUtilisateur = $mailUtilisateur;
    }

}