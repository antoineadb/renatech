<?php

class Projetcontextedescriptif {

    private $_idprojet;
    private $_description;
    private $_contexte;
    private $_confidentiel;
    private $_titre;
    private $_acronyme;
    private $_attachement;

    function __construct($idprojet, $description, $contexte,$confidentiel,$titre,$acronyme,$attachement) {
        $this->setIdprojet($idprojet);
        $this->setDescription($description);
        $this->setContexte($contexte);
        $this->setConfidentiel($confidentiel);
        $this->setTitre($titre);
        $this->setAcronyme($acronyme);
        $this->setAttachement($attachement);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function setDescription($description) {
        $this->_description = $description;
    }

    public function getContexte() {
        return $this->_contexte;
    }

    public function setContexte($contexte) {
        $this->_contexte = $contexte;
    }
     public function getConfidentiel() {
        return $this->_confidentiel;
    }

    public function setConfidentiel($confidentiel) {
        $this->_confidentiel = $confidentiel;
    }
    
     public function getTitre() {
        return $this->_titre;
    }

    public function setTitre($param) {
        $this->_titre = $param;
    }
    
    public function getAcronyme() {
        return $this->_acronyme;
    }

    public function setAcronyme($param) {
        $this->_acronyme = $param;
    }
    
    public function getAttachement() {
        return $this->_attachement;
    }

    public function setAttachement($param) {
        $this->_attachement = $param;
    }

}
