<?php

class ProjetTypePartenaire {

    private $_idtypepartenaire_typepartenaire;
    private $_idprojet_projet;
    private $_rang;

    function __construct($idtypepartenaire_typepartenaire, $idprojet_projet, $rang) {
        $this->setIdtypepartenaire_typepartenaire($idtypepartenaire_typepartenaire);
        $this->setIdprojet_projet($idprojet_projet);
        $this->setRang($rang);
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
    
    function getRang() {
        return $this->_rang;
    }

    function setRang($rang) {
        $this->_rang = $rang;
    }

}
