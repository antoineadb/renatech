<?php

class UtilisateurAutreCodeunite {

    private $_idutilisateur;    
    private $_idautrecodeunite;

    public function __construct($idutilisateur,  $idautrecodeunite) {
        $this->setIdutilisateur($idutilisateur);
        $this->setIdautrecodunite($idautrecodeunite);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }


    public function getIdautrecodunite() {
        return $this->_idautrecodeunite;
    }

    public function setIdautrecodunite($idautrecodeunite) {
        if(!empty($idautrecodeunite)){
            $this->_idautrecodeunite = (int) $idautrecodeunite;
        }else{
            $this->_idautrecodeunite = null;
        }
    }

}
