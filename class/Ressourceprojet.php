<?php

class Ressourceprojet {

    private $_idprojet_projet;
    private $_idressource_ressource;

    public function __construct($idprojet_projet, $idressource_ressource) {
        $this->setIdprojet_projet($idprojet_projet);
        $this->setIdressource_ressource($idressource_ressource);
    }

    public function getIdprojet_projet() {
        return $this->_idprojet_projet;
    }

    public function setIdprojet_projet($id) {
        $this->_idprojet_projet = (int) $id;
    }

    public function getIdressource_ressource() {
        return $this->_idressource_ressource;
    }

    public function setIdressource_ressource($id) {
        $this->_idressource_ressource = (int) $id;
    }

}
