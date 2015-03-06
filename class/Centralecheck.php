<?php

class Centralecheck {

    private $_idcentrale;
    private $_libellecentrale;

    public function __construct($idcentrale, $libellecentrale) {
        $this->setIdcentrale($idcentrale);
        $this->setLibellecentrale($libellecentrale);
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

    public function setLibellecentrale($libellecentrale) {
        $this->_libellecentrale = $libellecentrale;
    }

}