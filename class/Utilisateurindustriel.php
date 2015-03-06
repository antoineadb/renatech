<?php

class Utilisateurindustriel {

    private $_idutilisateur;
    private $_nom;
    private $_prenom;
    private $_entrepriselaboratoire;
    private $_adresse;
    private $_codepostal;
    private $_ville;
    private $_datecreation;
    private $_telephone;
    private $_fax;
    private $_nomresponsable;
    private $_mailresponsable;
    private $_nomentreprise;
    private $_idtypeutilisateur_typeutilisateur;
    private $_idpays_pays;
    private $_idlogin_loginpassword;
    private $_idqualitedemandeurindust_qualitedemandeurindust;

    public function __construct($nom, $prenom, $entrepriselaboratoire, $adresse, $codepostal, $ville, $date, $telephone, $fax, $nomresponsable, $mailresponsable, $nomentreprise, $idtypeutilisateur_typeutilisateur, $idpays_pays, $idlogin_loginpassword, $idqualitedemandeurindust_qualitedemandeurindust) {

        $this->setNom($nom);
        $this->setPrenom($prenom);
        $this->setEntrepriselaboratoire($entrepriselaboratoire);
        $this->setAdresse($adresse);
        $this->setCodePostal($codepostal);
        $this->setVille($ville);
        $this->setDate($date);
        $this->setTel($telephone);
        $this->setFax($fax);
        $this->setNomresponsable($nomresponsable);
        $this->setMailresponsable($mailresponsable);
        $this->setNomentreprise($nomentreprise);
        $this->setIdtypeutilisateur_typeutilisateur($idtypeutilisateur_typeutilisateur);
        $this->setIdpays_pays($idpays_pays);
        $this->setIdlogin_loginpassword($idlogin_loginpassword);
        $this->setIdqualitedemandeurindust_qualitedemandeurindust($idqualitedemandeurindust_qualitedemandeurindust);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function setNom($param) {
        $this->_nom = $param;
    }

    public function getNom() {
        return $this->_nom;
    }

    public function setPrenom($param) {
        $this->_prenom = $param;
    }

    public function getPrenom() {
        return $this->_prenom;
    }

    public function setAdresse($param) {
        $this->_adresse = $param;
    }

    public function getAdresse() {
        return $this->_adresse;
    }

    public function setCodePostal($param) {
        $this->_codepostal = $param;
    }

    public function getCodePostal() {
        return $this->_codepostal;
    }

    public function setVille($param) {
        $this->_ville = $param;
    }

    public function getVille() {
        return $this->_ville;
    }

    public function setDate($param) {
        $this->_datecreation = $param;
    }

    public function getDate() {
        return $this->_datecreation;
    }

    public function setTel($param) {
        $this->_telephone = $param;
    }

    public function getTel() {
        return $this->_telephone;
    }

    public function getFax() {
        return $this->_fax;
    }

    public function setFax($param) {
        $this->_fax = $param;
    }

    public function setNomresponsable($param) {
        $this->_nomresponsable = $param;
    }

    public function getNomresponsable() {
        return $this->_nomresponsable;
    }

    public function setMailresponsable($mail) {
        $this->_mailresponsable = $mail;
    }

    public function getMailresponsable() {
        return $this->_mailresponsable;
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

    public function getIdtypeutilisateur_typeutilisateur() {
        return $this->_idtypeutilisateur_typeutilisateur;
    }

    public function setIdtypeutilisateur_typeutilisateur($idtypeutilisateur_typeutilisateur) {
        $this->_idtypeutilisateur_typeutilisateur = (int) $idtypeutilisateur_typeutilisateur;
    }

    public function getIdpays_pays() {
        return $this->_idpays_pays;
    }

    public function setIdpays_pays($idpays_pays) {
        $this->_idpays_pays = (int) $idpays_pays;
    }

    public function getIdlogin_loginpassword() {
        return $this->_idlogin_loginpassword;
    }

    public function setIdlogin_loginpassword($idlogin_loginpassword) {
        $this->_idlogin_loginpassword = (int) $idlogin_loginpassword;
    }

    public function getIdqualitedemandeurindust_qualitedemandeurindust() {
        return $this->_idqualitedemandeurindust_qualitedemandeurindust;
    }

    public function setIdqualitedemandeurindust_qualitedemandeurindust($idqualitedemandeurindust_qualitedemandeurindust) {
        $this->_idqualitedemandeurindust_qualitedemandeurindust = (int) $idqualitedemandeurindust_qualitedemandeurindust;
    }

}