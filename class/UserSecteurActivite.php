<?php

class UserSecteurActivite {
    private $_idsecteuractivite;
    private $_idutilisateur;

    public function __construct($secteuractivite, $idutilisateur) {
        $this->setIdsecteuractivite($secteuractivite);
        $this->setIdutilisateur($idutilisateur);
    }

    public function setIdsecteuractivite($param) {
        $this->_idsecteuractivite = (int)$param;
    }

    public function getIdsecteuractivite() {
        return $this->_idsecteuractivite;
    }

    public function setIdutilisateur($param) {
        $this->_idutilisateur = (int) $param;
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }
}
