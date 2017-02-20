<?php

class Utilisateuracademique {

    private $_idutilisateur;
    private $_nom;
    private $_prenom;
    private $_adresse;
    private $_codePostal;
    private $_ville;
    private $_date;
    private $_tel;
    private $_fax;
    private $_nomresponsable;
    private $_mailresponsable;
    private $_entrepriselaboratoire;
    private $_idtypeutilisateur_typeutilisateur;
    private $_idpays_pays;
    private $_idlogin_loginpassword;
    private $_iddiscipline_disciplinescientifique;
    private $_idcentrale_centrale;
    private $_idqualitedemandeuraca_qualitedemandeuraca;
    private $_idtutelle_tutelle;
    private $_idemployeur_nomemployeur;
    private $_idautrestutelle_autrestutelle;
    private $_idautrediscipline_autredisciplinescientifique;
    private $_idautrenomemployeur_autrenomemployeur;
    private $_idqualitedemandeurindust_qualitedemandeurindust;
    private $_idautrecodeunite_autrecodeunite;
    private $_acronymelaboratoire;

    public function __construct($nom, $prenom, $entrepriselaboratoire, $adresse, $codepostal, $ville, $date, $telephone, $fax, $nomresponsable, $mailresponsable, $idtypeutilisateur_typeutilisateur, $idpays_pays, $idlogin_loginpassword, $iddiscipline_disciplinescientifique, $idcentrale_centrale, $idqualitedemandeuraca_qualitedemandeuraca, $idtutelle_tutelle, $idemployeur_nomemployeur, $idautrestutelle_autrestutelle, $idautrediscipline_autredisciplinescientifique, $idautrenomemployeur_autrenomemployeur, $idautrecodeunite_autrecodeunite, $acronymelaboratoire) {

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
        $this->setIdtypeutilisateur_typeutilisateur($idtypeutilisateur_typeutilisateur);
        $this->setIdpays_pays($idpays_pays);
        $this->setIdlogin_loginpassword($idlogin_loginpassword);
        $this->setIddiscipline_disciplinescientifique($iddiscipline_disciplinescientifique);
        $this->setIdcentrale_centrale($idcentrale_centrale);
        $this->setIdqualitedemandeuraca_qualitedemandeuraca($idqualitedemandeuraca_qualitedemandeuraca);
        $this->setIdtutelle_tutelle($idtutelle_tutelle);
        $this->setIdemployeur_nomemployeur($idemployeur_nomemployeur);
        $this->setIdautrestutelle_autrestutelle($idautrestutelle_autrestutelle);
        $this->setIdautrediscipline_autredisciplinescientifique($idautrediscipline_autredisciplinescientifique);
        $this->setIdautrenomemployeur_autrenomemployeur($idautrenomemployeur_autrenomemployeur);
        $this->setIdautrecodeunite_autrecodeunite($idautrecodeunite_autrecodeunite);
        $this->setAcronymelaboratoire($acronymelaboratoire);   
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
        $this->_codePostal = $param;
    }

    public function getCodePostal() {
        return $this->_codePostal;
    }

    public function setVille($param) {
        $this->_ville = $param;
    }

    public function getVille() {
        return $this->_ville;
    }

    public function setDate($param) {
        $this->_date = $param;
    }

    public function getDate() {
        return $this->_date;
    }

    public function setTel($param) {
        $this->_tel = $param;
    }

    public function getTel() {
        return $this->_tel;
    }

    public function setFax($param) {
        $this->_fax = $param;
    }

    public function getFax() {
        return $this->_fax;
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

    public function getIddiscipline_disciplinescientifique() {
        return $this->_iddiscipline_disciplinescientifique;
    }

    public function setIddiscipline_disciplinescientifique($iddiscipline_disciplinescientifique) {
        if (empty($iddiscipline_disciplinescientifique)) {
            $this->_iddiscipline_disciplinescientifique = 1;
        } else {
            $this->_iddiscipline_disciplinescientifique = (int) $iddiscipline_disciplinescientifique;
        }
    }

    public function getIdcentrale_centrale() {
        return $this->_idcentrale_centrale;
    }

    public function setIdcentrale_centrale($idcentrale_centrale) {
        if (!is_null($idcentrale_centrale)) {
            $this->_idcentrale_centrale = (int) $idcentrale_centrale;
        }
    }

    public function getIdqualitedemandeuraca_qualitedemandeuraca() {
        return $this->_idqualitedemandeuraca_qualitedemandeuraca;
    }

    public function setIdqualitedemandeuraca_qualitedemandeuraca($idqualitedemandeuraca_qualitedemandeuraca) {
        if (empty($idqualitedemandeuraca_qualitedemandeuraca)) {
            $this->_idqualitedemandeuraca_qualitedemandeuraca = 1;
        } else {
            $this->_idqualitedemandeuraca_qualitedemandeuraca = (int) $idqualitedemandeuraca_qualitedemandeuraca;
        }
    }

    public function getIdtutelle_tutelle() {
        return $this->_idtutelle_tutelle;
    }

    public function setIdtutelle_tutelle($idtutelle_tutelle) {
        if (empty($idtutelle_tutelle)) {
            $this->_idtutelle_tutelle = 1;
        } else {
            $this->_idtutelle_tutelle = (int) $idtutelle_tutelle;
        }
    }

    public function getIdemployeur_nomemployeur() {
        return $this->_idemployeur_nomemployeur;
    }

    public function setIdemployeur_nomemployeur($idemployeur_nomemployeur) {
        if (empty($idemployeur_nomemployeur)) {
            $this->_idemployeur_nomemployeur = 1;
        } else {
            $this->_idemployeur_nomemployeur = (int) $idemployeur_nomemployeur;
        }
    }

    public function getIdautrestutelle_autrestutelle() {
        return $this->_idautrestutelle_autrestutelle;
    }

    public function setIdautrestutelle_autrestutelle($idautrestutelle_autrestutelle) {
        if (empty($idautrestutelle_autrestutelle)) {
            $this->_idautrestutelle_autrestutelle = 1;
        } else {
            $this->_idautrestutelle_autrestutelle = (int) $idautrestutelle_autrestutelle;
        }
    }

    public function getIdautrediscipline_autredisciplinescientifique() {
        return $this->_idautrediscipline_autredisciplinescientifique;
    }

    public function setIdautrediscipline_autredisciplinescientifique($idautrediscipline_autredisciplinescientifique) {
        if (empty($idautrediscipline_autredisciplinescientifique)) {
            $this->_idautrediscipline_autredisciplinescientifique = 1;
        } else {
            $this->_idautrediscipline_autredisciplinescientifique = (int) $idautrediscipline_autredisciplinescientifique;
        }
    }

    public function getIdautrenomemployeur_autrenomemployeur() {
        return $this->_idautrenomemployeur_autrenomemployeur;
    }

    public function setIdautrenomemployeur_autrenomemployeur($idautrenomemployeur_autrenomemployeur) {
        if (empty($idautrenomemployeur_autrenomemployeur)) {
            $this->_idautrenomemployeur_autrenomemployeur = 1;
        } else {
            $this->_idautrenomemployeur_autrenomemployeur = (int) $idautrenomemployeur_autrenomemployeur;
        }
    }

    public function getIdqualitedemandeurindust_qualitedemandeurindust() {
        return $this->_idqualitedemandeurindust_qualitedemandeurindust;
    }

    public function setIdqualitedemandeurindust_qualitedemandeurindust($idqualitedemandeurindust_qualitedemandeurindust) {
        $this->_idqualitedemandeurindust_qualitedemandeurindust = (int) $idqualitedemandeurindust_qualitedemandeurindust;
    }

    public function getIdautrecodeunite_autrecodeunite() {
        return $this->_idautrecodeunite_autrecodeunite;
    }

    public function setIdautrecodeunite_autrecodeunite($idautrecodeunite_autrecodeunite) {
        if (empty($idautrecodeunite_autrecodeunite)) {
            $this->_idautrecodeunite_autrecodeunite = 1;
        } else {
            $this->_idautrecodeunite_autrecodeunite = (int) $idautrecodeunite_autrecodeunite;
        }
    }

    public function setAcronymelaboratoire($param) {
        $this->_acronymelaboratoire = $param;
    }

    public function getAcronymelaboratoire() {
        return $this->_acronymelaboratoire;
    }

}