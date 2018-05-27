<?php

class Projetresponsablecentrale {

    private $_numero;
    private $_titre;
    private $_dateprojet;
    private $_libellecentrale;
    private $_libellestatutprojet;
    private $_idprojet;
    private $_nom;
    private $_nomentreprise;
    private $_entrepriselaboratoire;

    public function __construct($numero, $titre, $dateprojet, $libellecentrale, $libellestatutprojet, $idprojet, $nom, $nomentreprise, $entrepriselaboratoire) {
        $this->setNumero($numero);
        $this->setTitre($titre);
        $this->setDateprojet($dateprojet);
        $this->setLibellecentrale($libellecentrale);
        $this->setLibellestatutprojet($libellestatutprojet);
        $this->setIdprojet($idprojet);
        $this->setNom($nom);
        $this->setNomentreprise($nomentreprise);
        $this->setEntrepriselaboratoire($entrepriselaboratoire);
    }

    public function getNumero() {
        return $this->_numero;
    }

    public function setNumero($numero) {
        $this->_numero = $numero;
    }

    public function getTitre() {
        return $this->_titre;
    }

    public function setTitre($titre) {
        $this->_titre = $titre;
    }

    public function getDateprojet() {
        return $this->_dateprojet;
    }

    public function setDateprojet($dateprojet) {
        $this->_dateprojet = $dateprojet;
    }

    public function getLibellecentrale() {
        return $this->_libellecentrale;
    }

    public function setLibellecentrale($libellecentrale) {
        $this->_libellecentrale = $libellecentrale;
    }

    public function getLibellestatutprojet() {
        return $this->_libellestatutprojet;
    }

    public function setLibellestatutprojet($libellestatutprojet) {
        $this->_libellestatutprojet = $libellestatutprojet;
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($id) {
        $this->_idprojet = (int) $id;
    }

    public function setNom($param) {
        $this->_nom = $param;
    }

    public function getNom() {
        return $this->_nom;
    }

    public function setNomentreprise($param) {
        $this->_nomentreprise = $param;
    }

    public function getNomentreprise() {
        return $this->_nomentreprise;
    }

    public function setEntrepriselaboratoire($param) {
        $this->_entrepriselaboratoire = $param;
    }

    public function getEntrepriselaboratoire() {
        return $this->_entrepriselaboratoire;
    }

}