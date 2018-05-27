<?php

class UtilisateurTutelle {

    private $_idutilisateur;
    private $_idtutelle_tutelle;
    private $_idautrestutelle_autrestutelle;

    public function __construct($idutilisateur, $idtutelle_tutelle, $idautrestutelle_autrestutelle) {
        $this->setIdutilisateur($idutilisateur);
        $this->setIdtutelle_tutelle($idtutelle_tutelle);
        $this->setIdautrestutelle_autrestutelle($idautrestutelle_autrestutelle);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function getIdtutelle_tutelle() {
        return $this->_idtutelle_tutelle;
    }

    public function setIdtutelle_tutelle($idtutelle_tutelle) {
        if (empty($idtutelle_tutelle)) {
            $this->_idtutelle_tutelle = 1;
        } else {
            $this->_idtutelle_tutelle = (int) $idtutelle_tutelle;
        }
    }

    public function getIdautrestutelle_autrestutelle() {
        return $this->_idautrestutelle_autrestutelle;
    }

    public function setIdautrestutelle_autrestutelle($idautrestutelle_autrestutelle) {
        if (empty($idautrestutelle_autrestutelle)) {
            $this->_idautrestutelle_autrestutelle = 1;
        } else {
            $this->_idautrestutelle_autrestutelle = (int) $idautrestutelle_autrestutelle;
        }
    }

}