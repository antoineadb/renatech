<?php

class Thematique {

    private $_idthematique;
    private $_libellethematique;
    private $_masquethematique;
    private $_libellethematiqueen;

    public function __construct($idthematique, $libellethematique, $masquethematique, $libellethematiqueen) {
        $this->setIdthematique($idthematique);
        $this->setLibellethematique($libellethematique);
        $this->setMasquethematique($masquethematique);
        $this->setLibellethematiqueen($libellethematiqueen);
    }

    public function getIdthematique() {
        return $this->_idthematique;
    }

    public function setIdthematique($id) {
        $this->_idthematique = (int) $id;
    }

    public function getLibellethematique() {
        return $this->_libellethematique;
    }

    public function setLibellethematique($param) {
        $this->_libellethematique = $param;
    }

    public function getMasquethematique() {
        return $this->_masquethematique;
    }

    public function setMasquethematique($value) {
        $this->_masquethematique = $value;
    }

    public function getLibellethematiqueen() {
        return $this->_libellethematiqueen;
    }

    public function setLibellethematiqueen($param) {
        $this->_libellethematiqueen = $param;
    }

}