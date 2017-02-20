<?php

class Adminnational {

    private $_idadminnational;
    private $_emailadminnational;

    public function __construct($idadminnational, $emailadminnational) {
        $this->setIdautrecodenite($idadminnational);
        $this->setEmailadminnational($emailadminnational);
    }

    public function getIdadminnational() {
        return $this->_idadminnational;
    }

    public function setIdautrecodenite($id) {
        $this->_idadminnational = (int) $id;
    }

    public function getEmailadminnational() {
        return $this->_emailadminnational;
    }

    public function setEmailadminnational($email) {
        $this->_mailresponsable = $email;
    }

}
