<?php

class Projettypeprojet {

    private $_idtypeformation;
    private $_idprojet;

    public function __construct($idtypeformation, $idprojet) {
        $this->setIdtypeformation($idtypeformation);
        $this->setIdprojet($idprojet);
    }

    public function getIdtypeformation() {
        return $this->_idtypeformation;
    }

    public function setIdtypeformation($id) {
        $this->_idtypeformation = (int) $id;
    }
    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($id) {
        $this->_idprojet = (int) $id;
    }

}
