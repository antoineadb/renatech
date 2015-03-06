<?php

class UtilisateurMailresponsable {

    private $_idutilisateur;
    private $_mailresponsable;

    function __construct($idutilisateur, $mailresponsable) {
        $this->setIdutilisateur($idutilisateur);
        $this->setMailresponsable($mailresponsable);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function getMailresponsable() {
        return $this->_mailresponsable;
    }

    public function setMailresponsable($mail) {
        $this->_mailresponsable = $mail;
    }

}
