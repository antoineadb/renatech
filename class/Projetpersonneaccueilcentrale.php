<?php

class Projetpersonneaccueilcentrale {

    private $_idprojet_projet;
    private $_idpersonneaccueilcentrale_personneaccueilcentrale;

    public function __construct($idprojet_projet, $idpersonneaccueilcentrale_personneaccueilcentrale) {
        $this->setIdprojet_projet($idprojet_projet);
        $this->setIdpersonneaccueilcentrale_personneaccueilcentrale($idpersonneaccueilcentrale_personneaccueilcentrale);
    }

    public function getIdprojet_projet() {
        return $this->_idprojet_projet;
    }

    public function setIdprojet_projet($id) {
        $this->_idprojet_projet = (int) $id;
    }

    public function getIdpersonneaccueilcentrale_personneaccueilcentrale() {
        return $this->_idpersonneaccueilcentrale_personneaccueilcentrale;
    }

    public function setIdpersonneaccueilcentrale_personneaccueilcentrale($id) {
        $this->_idpersonneaccueilcentrale_personneaccueilcentrale = (int) $id;
    }

}