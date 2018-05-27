<?php

class Centrale {

    private $_idcentrale;
    private $_libellecentrale;
    private $_villecentrale;
    private $_codeunite;
    private $_email1;
    private $_email2;
    private $_email3;
    private $_email4;
    private $_email5;

    public function __construct($idcentrale, $libellecentrale, $villecentrale, $codeunite, $email1, $email2, $email3, $email4, $email5) {
        $this->setIdcentrale($idcentrale);
        $this->setLibellecentrale($libellecentrale);
        $this->setVillecentrale($villecentrale);
        $this->setCodeunite($codeunite);
        $this->setEmail1($email1);
        $this->setEmail2($email2);
        $this->setEmail3($email3);
        $this->setEmail4($email4);
        $this->setEmail5($email5);
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

    public function getVillecentrale() {
        return $this->_villecentrale;
    }

    public function setVillecentrale($villecentrale) {
        $this->_villecentrale = $villecentrale;
    }

    public function getCodeunite() {
        return $this->_codeunite;
    }

    public function setCodeunite($codeunite) {
        $this->_codeunite = $codeunite;
    }

    public function getEmail1() {
        return $this->_email1;
    }

    public function setEmail1($email1) {
        $this->_email1 = $email1;
    }

    public function getEmail2() {
        return $this->_email2;
    }

    public function setEmail2($email2) {
        $this->_email2 = $email2;
    }

    public function getEmail3() {
        return $this->_email3;
    }

    public function setEmail3($email3) {
        $this->_email3 = $email3;
    }

    public function getEmail4() {
        return $this->_email4;
    }

    public function setEmail4($email4) {
        $this->_email4 = $email4;
    }

    public function getEmail5() {
        return $this->_email5;
    }

    public function setEmail5($email5) {
        $this->_email5 = $email5;
    }

}
