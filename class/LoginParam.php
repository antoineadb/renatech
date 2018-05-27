<?php

class LoginParam {

    private $_pseudo;
    private $_tmpcx;

    public function __construct($pseudo, $tmpcx) {
        $this->setPseudo($pseudo);
        $this->setTmpcx($tmpcx);
    }

    public function setTmpcx($tmpcx) {
        $this->_tmpcx = (int) $tmpcx;
    }

    public function getTmpcx() {
        return $this->_tmpcx;
    }

     public function setPseudo($pseudo) {
        $this->_pseudo = $pseudo;
    }

    public function getPseudo() {
        return $this->_pseudo;
    }

}
