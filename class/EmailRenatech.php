<?php

class EmailRenatech {

    private $_idemail;
    private $_email;

    public function __construct($idemail, $email) {
        $this->setIdEmail($idemail);
        $this->setEmail($email);
    }

    public function getIdEmail() {
        return $this->_idemail;
    }

    public function setIdEmail($id) {
        $this->_idemail = (int) $id;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function setEmail($email) {
        $this->_email = $email;
    }

}
