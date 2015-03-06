<?php

class Appartient {

    private $_idtypeentreprise_typeentreprise;
    private $_idutilisateur_utilisateur;

    public function __construct($idtypeentreprise_typeentreprise, $idutilisateur_utilisateur) {
        $this->setIdtypeentreprise_typeentreprise($idtypeentreprise_typeentreprise);
        $this->setIdutilisateur_utilisateur($idutilisateur_utilisateur);
    }

    public function getIdtypeentreprise_typeentreprise() {
        return $this->_idtypeentreprise_typeentreprise;
    }

    public function setIdtypeentreprise_typeentreprise($idtypeentreprise_typeentreprise) {
        $this->_idtypeentreprise_typeentreprise = (int) $idtypeentreprise_typeentreprise;
    }

    public function getIdutilisateur_utilisateur() {
        return $this->_idutilisateur_utilisateur;
    }

    public function setIdutilisateur_utilisateur($utilisateur_utilisateur) {
        $this->_idutilisateur_utilisateur = (int) $utilisateur_utilisateur;
    }

}