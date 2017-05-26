<?php

class ProjetTypePartenaire {

    private $_idtypepartenaire_typepartenaire;
    private $_idprojet_projet;

    function __construct($idtypepartenaire_typepartenaire, $idprojet_projet) {
        $this->setIdtypepartenaire_typepartenaire($idtypepartenaire_typepartenaire);
        $this->setIdprojet_projet($idprojet_projet);
    }

    function getIdtypepartenaire_typepartenaire() {
        return $this->_idtypepartenaire_typepartenaire;
    }

    function getIdprojet_projet() {
        return $this->_idprojet_projet;
    }

    function setIdtypepartenaire_typepartenaire($idtypepartenaire_typepartenaire) {
        $this->_idtypepartenaire_typepartenaire = $idtypepartenaire_typepartenaire;
    }

    function setIdprojet_projet($idprojet_projet) {
        $this->_idprojet_projet = $idprojet_projet;
    }

}
