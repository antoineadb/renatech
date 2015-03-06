<?php

class UtilisateurCodeunite {

    private $_idutilisateur;
    private $_idcodeunite;

    function __construct($idutilisateur, $idcodeunite) {
        $this->setIdutilisateur($idutilisateur);
        $this->setIdcodeunite($idcodeunite);
    }

    function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    function getIdcodeunite() {
        return $this->_idcodeunite;
    }

    function setIdutilisateur($idutilisateur) {
        $this->_idutilisateur = (int)$idutilisateur;
    }

    function setIdcodeunite($idcodeunite) {
        if(!empty($idcodeunite)){
            $this->_idcodeunite = (int)$idcodeunite;
        }else{
            $this->_idcodeunite = null;
        }
    }

}
