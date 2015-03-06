<?php

class UtilisateurQualiteindust {
     private $_idqualitedemandeurindust;
    private $_idutilisateur;

    public function __construct($idqualitedemandeurindust, $idutilisateur) {
        $this->setIdqualitedemandeurindust($idqualitedemandeurindust);
        $this->setIdutilisateur($idutilisateur);
    }

    public function getIdqualitedemandeurindust() {
        return $this->_idqualitedemandeurindust;
    }

    public function setIdqualitedemandeurindust($idqualitedemandeurindust) {
            $this->_idqualitedemandeurindust = (int)$idqualitedemandeurindust;
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($idutilisateur) {
        $this->_idutilisateur = $idutilisateur;
    }
}
