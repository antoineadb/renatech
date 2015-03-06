<?php

class UtilisateurPorteurProjet {

    private $_idutilisateur;
    private $_idprojet;
    private $_dateaffectation;

    public function __construct($idutilisateur, $idprojet, $dateaffectation) {
        $this->setIdutilisateur($idutilisateur);
        $this->setIdprojet($idprojet);
        $this->setDateaffectation($dateaffectation);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getDateaffectation() {
        return $this->_dateaffectation;
    }

    public function setDateaffectation($dateaffectation) {
        $this->_dateaffectation = $dateaffectation;
    }

}