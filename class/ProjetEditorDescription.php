<?php

class ProjetEditorDescription {

    private $_idprojet;
    private $_description;

    function __construct($idprojet, $description) {
        $this->setIdprojet($idprojet);
        $this->setDescription($description);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function setDescription($description) {
        $this->_description = $description;
    }

}
