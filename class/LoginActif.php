<?php

class LoginActif {

    private $_idlogin;
    private $_actif;

    public function __construct($actif, $idlogin) {
        $this->setActif($actif);
        $this->setIdlogin($idlogin);
    }

    public function setActif($actif) {
        $this->_actif = $actif;
    }

    public function getActif() {
        return $this->_actif;
    }

    public function setIdlogin($id) {
        $this->_idlogin = (int) $id;
    }

    public function getIdlogin() {
        return $this->_idlogin;
    }

}