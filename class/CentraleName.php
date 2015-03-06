<?php

class CentraleName {

    private $_idcentrale;
    private $_libellecentrale;
    private $_masquecentrale;

    public function __construct($idcentrale, $libellecentrale, $masquecentrale) {
        $this->setIdcentrale($idcentrale);
        $this->setLibellecentrale($libellecentrale);
        $this->setMasquecentrale($masquecentrale);
    }

    public function getIdcentrale() {
        return $this->_idcentrale;
    }

    public function setIdcentrale($id) {
        $this->_idcentrale = (int) $id;
    }

    public function getLibellecentrale() {
        return $this->_libellecentrale;
    }

    public function setLibellecentrale($param) {
        $this->_libellecentrale = $param;
    }

    public function getMasquecentrale() {
        return $this->_masquecentrale;
    }

    public function setMasquecentrale($value) {
        $this->_masquecentrale = $value;
    }

}