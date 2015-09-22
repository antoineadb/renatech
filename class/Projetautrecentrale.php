<?php

class Projetautrecentrale {

    private $_idcentrale;
    private $_idprojet;
    private $_sendmail;

    public function __construct($idcentrale, $idprojet, $sendmail) {
        $this->setIdcentrale($idcentrale);
        $this->setIdprojet($idprojet);
        $this->setSendmail($sendmail);
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

    public function getSendmail() {
        return $this->_sendmail;
    }

    public function setSendmail($value) {
        $this->_sendmail = $value;
    }
}
