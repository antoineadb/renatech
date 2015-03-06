<?php

class ProjetEditorContexte {

    private $_idprojet;
    private $_contexte;

    function __construct($idprojet, $contexte) {
        $this->setIdprojet($idprojet);
        $this->setContexte($contexte);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getContexte() {
        return $this->_contexte;
    }

    public function setContexte($contexte) {
        $this->_contexte = $contexte;
    }

}
