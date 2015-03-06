<?php

class Projetphase1 {

    private $_idprojet;
    private $_titre;
    private $_numero;
    private $_confidentiel;
    private $_description;
    private $_dateprojet;
    private $_contexte;
    private $_idtypeprojet_typeprojet;
    private $_attachement;
    private $_acronyme;

    public function __construct($idprojet, $titre, $numero, $confidentiel, $description, $dateprojet, $contexte, $idtypeprojet, $attachement, $acronyme) {

        $this->setIdprojet($idprojet);
        $this->setTitre($titre);
        $this->setNumero($numero);
        $this->setConfidentiel($confidentiel);
        $this->setDescription($description);
        $this->setDateprojet($dateprojet);
        $this->setContexte($contexte);
        $this->setIdtypeprojet_typeprojet($idtypeprojet);
        $this->setAttachement($attachement);
        $this->setAcronyme($acronyme);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getTitre() {
        return $this->_titre;
    }

    public function setTitre($param) {
        $this->_titre = $param;
    }

    public function getNumero() {
        return $this->_numero;
    }

    public function setNumero($numero) {
        $this->_numero = $numero;
    }

    public function getConfidentiel() {
        return $this->_confidentiel;
    }

    public function setConfidentiel($confidentiel) {
        $this->_confidentiel = $confidentiel;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function setDescription($description) {
        $this->_description = $description;
    }

    public function getDateprojet() {
        return $this->_dateprojet;
    }

    public function setDateprojet($dateprojet) {
        $this->_dateprojet = $dateprojet;
    }

    public function getContexte() {
        return $this->_contexte;
    }

    public function setContexte($contexte) {
        $this->_contexte = $contexte;
    }

    public function getIdtypeprojet_typeprojet() {
        return $this->_idtypeprojet_typeprojet;
    }

    public function setIdtypeprojet_typeprojet($idtypeprojet_typeprojet) {
        $this->_idtypeprojet_typeprojet = (int) $idtypeprojet_typeprojet;
    }

    public function getAttachement() {
        return $this->_attachement;
    }

    public function setAttachement($attachement) {
        $this->_attachement = $attachement;
    }

    public function getAcronyme() {
        return $this->_acronyme;
    }

    public function setAcronyme($param) {
        $this->_acronyme = $param;
    }

}
