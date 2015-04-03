<?php

class Personneaccueilcentrale {

    private $_idpersonneaccueilcentrale;
    private $_nomaccueilcentrale;
    private $_prenomaccueilcentrale;
    private $_idqualitedemandeuraca_qualitedemandeuraca;
    private $_mailaccueilcentrale;
    private $_telaccueilcentrale;
    private $_connaissancetechnologiqueaccueil;
   /* private $_idpersonneQualite;
    private $_idautresqualite;*/

    public function __construct($idpersonneaccueilcentrale, $nomAccueilcentrale, $prenomAccueilcentrale, $idqualitedemandeuraca_qualitedemandeuraca, $emailAccueilcentrale, $telAccueilcentrale, 
            $connaissancetechnologiqueAccueil){//,$idpersonneQualite,$idautresqualite) {
   
        $this->setIdpersonneaccueilcentrale($idpersonneaccueilcentrale);
        $this->setNomaccueilcentrale($nomAccueilcentrale);
        $this->setPrenomaccueilcentrale($prenomAccueilcentrale);
        $this->setIdqualitedemandeuraca_qualitedemandeuraca($idqualitedemandeuraca_qualitedemandeuraca);
        $this->setMailaccueilcentrale($emailAccueilcentrale);
        $this->setTelaccueilcentrale($telAccueilcentrale);
        $this->setConnaissancetechnologiqueaccueil($connaissancetechnologiqueAccueil);
        /*$this->setIdpersonneQualite($idpersonneQualite);
        $this->setIdautresqualite($idautresqualite);*/
    }

    /*public function getIdautresqualite() {
        return $this->_idautresqualite;
    }

    public function setIdautresqualite($id) {
        $this->_idautresqualite = (int) $id;
    }
    public function getIdpersonneQualite() {
        return $this->_idpersonneQualite;
    }

    public function setIdpersonneQualite($id) {
        $this->_idpersonneQualite = (int) $id;
    }*/
    
    public function getIdpersonneaccueilcentrale() {
        return $this->_idpersonneaccueilcentrale;
    }

    public function setIdpersonneaccueilcentrale($id) {
        $this->_idpersonneaccueilcentrale = (int) $id;
    }

    public function getNomaccueilcentrale() {
        return $this->_nomaccueilcentrale;
    }

    public function setNomaccueilcentrale($nom) {
        $this->_nomaccueilcentrale = $nom;
    }

    public function getPrenomaccueilcentrale() {
        return $this->_prenomaccueilcentrale;
    }

    public function setPrenomaccueilcentrale($prenom) {
        $this->_prenomaccueilcentrale = $prenom;
    }

    public function getIdqualitedemandeuraca_qualitedemandeuraca() {
        return $this->_idqualitedemandeuraca_qualitedemandeuraca;
    }

    public function setIdqualitedemandeuraca_qualitedemandeuraca($id) {
        $idqualite = (int) $id;
        if ($idqualite >= 1) {
            $this->_idqualitedemandeuraca_qualitedemandeuraca = $id;
        } else {
            $this->_idqualitedemandeuraca_qualitedemandeuraca = null;
        }
    }

    public function getMailaccueilcentrale() {
        return $this->_mailaccueilcentrale;
    }

    public function setMailaccueilcentrale($mail) {
        $this->_mailaccueilcentrale = $mail;
    }

    public function getTelaccueilcentrale() {
        return $this->_telaccueilcentrale;
    }

    public function setTelaccueilcentrale($param) {
        $this->_telaccueilcentrale = $param;
    }

    public function getConnaissancetechnologiqueaccueil() {
        return $this->_connaissancetechnologiqueaccueil;
    }

    public function setConnaissancetechnologiqueaccueil($connaissance) {
        $this->_connaissancetechnologiqueaccueil = $connaissance;
    }

}
