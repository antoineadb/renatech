<?php

class Emailcentrale {

    private $_emailCentrale1;
    private $_emailCentrale2;
    private $_emailCentrale3;
    private $_emailCentrale4;
    private $_emailCentrale5;

    function __construct($emailCentrale1, $emailCentrale2, $emailCentrale3, $emailCentrale4, $emailCentrale5) {
        $this->setEmailCentrale1($emailCentrale1);
        $this->setEmailCentrale2($emailCentrale2);
        $this->setEmailCentrale3($emailCentrale3);
        $this->setEmailCentrale4($emailCentrale4);
        $this->setEmailCentrale5($emailCentrale5);
    }

    public function getEmailCentrale1() {
        return $this->_emailCentrale1;
    }

    public function setEmailCentrale1($mail) {
        $this->_emailCentrale1 = $mail;
    }

    public function getEmailCentrale2() {
        return $this->_emailCentrale2;
    }

    public function setEmailCentrale2($mail) {
        $this->_emailCentrale2 = $mail;
    }

    public function getEmailCentrale3() {
        return $this->_emailCentrale3;
    }

    public function setEmailCentrale3($mail) {
        $this->_emailCentrale3 = $mail;
    }

    public function getEmailCentrale4() {
        return $this->_emailCentrale4;
    }

    public function setEmailCentrale4($mail) {
        $this->_emailCentrale4 = $mail;
    }

    public function getEmailCentrale5() {
        return $this->_emailCentrale5;
    }

    public function setEmailCentrale5($mail) {
        $this->_emailCentrale5 = $mail;
    }

}