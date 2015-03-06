<?php

class Projetautrecentrale {

    private $_idcentrale;
    private $_idprojet;

    public function __construct($idcentrale, $idprojet) {
        $this->setIdcentrale($idcentrale);
        $this->setIdprojet($idprojet);
    }

    public function getIdcentrale() {
        return $this->_idcentrale;
    }

    public function setIdcentrale($id) {
        $this->_idcentrale = (int) $id;
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }
}
