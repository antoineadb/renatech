<?php

class Projetattachementdesc {

    private $_idprojet;
    private $_attachementdesc;

    public function __construct($idprojet, $attachementdesc) {
        $this->setIdprojet($idprojet);
        $this->setattachementdesc($attachementdesc);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getattachementdesc() {
        return $this->_attachementdesc;
    }

    public function setattachementdesc($attachementdesc) {
        $this->_attachementdesc = $attachementdesc;
    }

}