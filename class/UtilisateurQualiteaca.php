<?php

class UtilisateurQualiteaca {
    private $_idqualitedemandeuraca;
    private $_idutilisateur;

    public function __construct($idqualitedemandeuraca, $idutilisateur) {
        $this->setIdqualitedemandeuraca($idqualitedemandeuraca);
        $this->setIdutilisateur($idutilisateur);
    }

    public function getIdqualitedemandeuraca() {
        return $this->_idqualitedemandeuraca;
    }

    public function setIdqualitedemandeuraca($idqualitedemandeuraca) {
            $this->_idqualitedemandeuraca = (int)$idqualitedemandeuraca;
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($idutilisateur) {
        $this->_idutilisateur = $idutilisateur;
    }
}
