<?php

class LAASupdate {

    private $_idprojet;
    private $_idtypeprojet_typeprojet;
    private $_dureeprojet;
    private $_idperiodicite_periodicite;
    private $_idsourcefinancement_sourcefinancement;
    private $_idthematique_thematique;

    function __construct($idprojet, $idtypeprojet_typeprojet, $dureeprojet, $idperiodicite_periodicite, $idsourcefinancement_sourcefinancement, $idthematique_thematique) {
        $this->setIdprojet($idprojet);
        $this->setIdtypeprojet_typeprojet($idtypeprojet_typeprojet);
        $this->setDureeprojet($dureeprojet);
        $this->setIdperiodicite_periodicite($idperiodicite_periodicite);
        $this->setIdsourcefinancement_sourcefinancement($idsourcefinancement_sourcefinancement);
        $this->setIdthematique_thematique($idthematique_thematique);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($idutilisateur) {
        $this->_idutilisateur = (int) $idutilisateur;
    }

    public function setTitre($param) {
        $this->_titre = $param;
    }

    public function getIdtypeprojet_typeprojet() {
        return $this->_idtypeprojet_typeprojet;
    }

    public function setIdtypeprojet_typeprojet($idtypeprojet_typeprojet) {
        $this->_idtypeprojet_typeprojet = (int) $idtypeprojet_typeprojet;
    }

    public function getDureeprojet() {
        return $this->_dureeprojet;
    }

    public function setDureeprojet($dureeprojet) {
        $this->_dureeprojet = (int) $dureeprojet;
    }

    public function getIdperiodicite_periodicite() {
        return $this->_idperiodicite_periodicite;
    }

    public function setIdperiodicite_periodicite($id) {
        $periodicite = (int) $id;
        if ($periodicite >= 1) {
            $this->_idperiodicite_periodicite = $id;
        } else {
            $this->_idperiodicite_periodicite = 1;
        }
    }

    public function getIdsourcefinancement_sourcefinancement() {
        return $this->_idsourcefinancement_sourcefinancement;
    }

    public function setIdsourcefinancement_sourcefinancement($id) {
        $this->_idsourcefinancement_sourcefinancement = (int) $id;
    }

    public function getIdthematique_thematique() {
        return $this->_idthematique_thematique;
    }

    public function setIdthematique_thematique($id) {
        $this->_idthematique_thematique = $id;
    }

}
