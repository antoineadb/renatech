<?php

class Projetattachement {

    private $_idprojet;
    private $_attachement;

    public function __construct($idprojet, $attachement) {
        $this->setIdprojet($idprojet);
        $this->setAttachement($attachement);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getAttachement() {
        return $this->_attachement;
    }

    public function setAttachement($attachement) {
        $this->_attachement = $attachement;
    }

}