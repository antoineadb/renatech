<?php

class Intervient {

    private $_idsecteuractivite_secteuractivite;
    private $_idutilisateur_utilisateur;

    public function __construct($idsecteuractivite_secteuractivite, $idutilisateur_utilisateur) {
        $this->setIdsecteuractivite_secteuractivite($idsecteuractivite_secteuractivite);
        $this->setIdutilisateur_utilisateur($idutilisateur_utilisateur);
    }

    public function getIdsecteuractivite_secteuractivite() {
        return $this->_idsecteuractivite_secteuractivite;
    }

    public function setIdsecteuractivite_secteuractivite($idsecteuractivite_secteuractivite) {
        $this->_idsecteuractivite_secteuractivite = (int) $idsecteuractivite_secteuractivite;
    }

    public function getIdutilisateur_utilisateur() {
        return $this->_idutilisateur_utilisateur;
    }

    public function setIdutilisateur_utilisateur($utilisateur_utilisateur) {
        $this->_idutilisateur_utilisateur = (int) $utilisateur_utilisateur;
    }

}
