<?php

class UtilisateurNomEquipe {

    private $_nomEquipe;
    private $_idlogin;

    public function __construct($nomEquipe, $idlogin) {
        $this->setnomEquipe($nomEquipe);
        $this->setIdlogin($idlogin);
    }

    public function setnomEquipe($param) {
        $this->_nomEquipe = $param;
    }

    public function getnomEquipe() {
        return $this->_nomEquipe;
    }

    public function setIdlogin($param) {
        $this->_idlogin = (int) $param;
    }

    public function getIdlogin() {
        return $this->_idlogin;
    }

}
