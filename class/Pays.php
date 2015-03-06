<?php

class Pays {

    private $_idpays;
    private $_nompays;
    private $_nompaysen;
    private $_idsituation_situationgeographique;
    private $_masquepays;

    public function __construct($idpays, $nompays, $idsituation_situationgeographique, $nompaysen, $masquepays) {
        $this->setIdpays($idpays);
        $this->setNompays($nompays);
        $this->setIdsituation_situationgeographique($idsituation_situationgeographique);
        $this->setNompaysen($nompaysen);
        $this->setMasquepays($masquepays);
    }

    public function getIdpays() {
        return $this->_idpays;
    }

    public function setIdpays($id) {
        $this->_idpays = (int) $id;
    }

    public function getNompays() {
        return $this->_nompays;
    }

    public function setNompays($param) {
        $this->_nompays = $param;
    }

    public function getIdsituation_situationgeographique() {
        return $this->_idsituation_situationgeographique;
    }

    public function setIdsituation_situationgeographique($idsituation_situationgeographique) {
        $this->_idsituation_situationgeographique = (int) $idsituation_situationgeographique;
    }

    public function getNompaysen() {
        return $this->_nompaysen;
    }

    public function setNompaysen($param) {
        $this->_nompaysen = $param;
    }

    public function getMasquepays() {
        return $this->_masquepays;
    }

    public function setMasquepays($value) {
        $this->_masquepays = $value;
    }

}