<?php

class Autredisciplinescientifique {

    private $_idautrediscipline;
    private $_libelleautrediscipline;

    public function __construct($idautrediscipline, $libelleautrediscipline) {
        $this->setIdautrediscipline($idautrediscipline);
        $this->setLibelleautrediscipline($libelleautrediscipline);
    }

    public function getIdautrediscipline() {
        return $this->_idautrediscipline;
    }

    public function setIdautrediscipline($id) {
            $this->_idautrediscipline = (int) $id;
    }

    public function getLibelleautrediscipline() {
        return $this->_libelleautrediscipline;
    }

    public function setLibelleautrediscipline($libelleautrediscipline) {
        $this->_libelleautrediscipline = $libelleautrediscipline;
    }

}
