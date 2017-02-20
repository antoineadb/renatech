<?php

class Compteur {

    private $_cfirstvisit;
    private $_clastvisit;
    private $_ctotal;
    private $_login;
    private $_ctime;

    public function __construct( $cfirstvisit, $clastvisit,  $ctotal, $login, $ctime) {        
        $this->setCfirstvisit($cfirstvisit);
        $this->setClastvisit($clastvisit);
        $this->setCtotal($ctotal);
        $this->setClogin($login);
        $this->setCtime($ctime);
    }
    
    public function getCfirstvisit() {
        return $this->_cfirstvisit;
    }

    public function setCfirstvisit($cfirstvisit) {
        $this->_cfirstvisit = $cfirstvisit;
    }

    public function getClastvisit() {
        return $this->_clastvisit;
    }

    public function setClastvisit($clastvisit) {
        $this->_clastvisit = $clastvisit;
    }

    public function getC_total() {
        return $this->_ctotal;
    }

    public function setCtotal($ctotal) {
        $this->_ctotal = $ctotal;
    }

    public function getClogin() {
        return $this->_login;
    }

    public function setClogin($login) {
        $this->_login = $login;
    }

    public function getCtime() {
        return $this->_ctime;
    }

    public function setCtime($chost) {
        $this->_ctime = $chost;
    }

}
