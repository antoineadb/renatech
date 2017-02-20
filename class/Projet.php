<?php

class Projet {

    private $_titre;
    private $_acronyme;
    private $_numero;
    private $_confidentiel;
    private $_description;
    private $_dateprojet;
    private $_contexte;
    private $_commentaire;
    private $_attachement;
    private $_attachementdesc;
    private $_nomcontactprojet;
    private $_prenomcontactprojet;
    private $_debuttravaux;
    private $_dureeprojet;
    private $_centralepartenaire;
    private $_verrouidentifiee;
    private $_nbplaque;
    private $_nbrun;
    private $_envoidevis;
    private $_emailrespdevis;
    private $_reussite;
    private $_succes;
    private $_frequencedemande;
    private $_refinterneprojet;
    private $_idtypeprojet_typeprojet;
    private $_idthematique_thematique;
    private $_idstatutprojet_statutprojet;
    private $_idsourcefinancement_sourcefinancement;
    private $_idpersonneaccueilcentrale_personneaccueilcentrale;

    public function __construct($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12, $p13, $p14, $p15, $p16, $p17, $p18, $p19, $p20
    , $p21, $p22, $p23, $p24, $p25, $p26, $p27, $p28, $p29) {

        $this->setTitre($p1);
        $this->setAcronyme($p2);
        $this->setNumero($p3);
        $this->setConfidentiel($p4);
        $this->setDescription($p5);
        $this->setDateprojet($p6);
        $this->setContexte($p7);
        $this->setCommentaire($p8);
        $this->setAttachement($p9);
        $this->setAttachementdesc($p10);
        $this->setNomcontactprojet($p11);
        $this->setPrenomcontactprojet($p12);
        $this->setDebuttravaux($p13);
        $this->setDureeprojet($p14);
        $this->setCentralepartenaire($p15);
        $this->setVerrouidentifiee($p16);
        $this->setNbplaque($p17);
        $this->setNbrun($p18);
        $this->setEnvoidevis($p19);
        $this->setEmailrespdevis($p20);
        $this->setReussite($p21);
        $this->setSucces($p22);
        $this->setFrequencedemande($p23);
        $this->setRefinterneprojet($p24);
        $this->setIdtypeprojet_typeprojet($p25);
        $this->setIdthematique_thematique($p26);
        $this->setIdstatutprojet_statutprojet($p28);
        $this->setIdsourcefinancement_sourcefinancement($p28);
        $this->setIdpersonneaccueilcentrale_personneaccueilcentrale($p29);
    }

    public function getTitre() {
        return $this->_titre;
    }

    public function setTitre($titre) {
        $this->_titre = $titre;
    }

    public function getAcronyme() {
        return $this->_acronyme;
    }

    public function setAcronyme($acronyme) {
        if (empty($acronyme)) {
            $this->_acronyme = "";
        } else {
            $this->_acronyme = $acronyme;
        }
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

    public function getCommentaire() {
        return $this->_commentaire;
    }

    public function setCommentaire($commentaire) {
        $this->_commentaire = $commentaire;
    }

    public function getAttachement() {
        return $this->_attachement;
    }

    public function setAttachement($attachement) {
        $this->_attachement = $attachement;
    }

    public function getAttachementdesc() {
        return $this->_attachementdesc;
    }

    public function setAttachementdec($attachementdesc) {
        $this->_attachementdesc = $attachementdesc;
    }

    public function getNomcontactprojet() {
        return $this->_nomcontactprojet;
    }

    public function setNomcontactprojet($nomcontactprojet) {
        $this->_nomcontactprojet = $nomcontactprojet;
    }

    public function getPrenomcontactprojet() {
        return $this->_prenomcontactprojet;
    }

    public function setprenomcontactprojet($prenomcontactprojet) {
        $this->_prenomcontactprojet = $prenomcontactprojet;
    }

    public function getDebuttravaux() {
        return $this->_debuttravaux;
    }

    public function setDebuttravaux($debuttravaux) {
        $this->_debuttravaux = $debuttravaux;
    }

    public function getDureeprojet() {
        return $this->_dureeprojet;
    }

    public function setDureeprojet($dureeprojet) {
        $this->_dureeprojet = $dureeprojet;
    }

    public function getCentralepartenaire() {
        return $this->_centralepartenaire;
    }

    public function setCentralepartenaire($centralepartenaire) {
        $this->_centralepartenaire = $centralepartenaire;
    }

    public function getVerrouidentifiee() {
        return $this->_verrouidentifiee;
    }

    public function setVerrouidentifiee($verrouidentifiee) {
        $this->_verrouidentifiee = $verrouidentifiee;
    }

    public function getNbplaque() {
        return $this->_nbplaque;
    }

    public function setNbplaque($nbplaque) {
        $this->_nbplaque = $nbplaque;
    }

    public function getNbrun() {
        return $this->_nbrun;
    }

    public function setNbrun($nbrun) {
        $this->_nbrun = $nbrun;
    }

    public function getEnvoidevis() {
        return $this->_envoidevis;
    }

    public function setEnvoidevis($envoidevis) {
        $this->_envoidevis = $envoidevis;
    }

    public function getEmailrespdevis() {
        return $this->_emailrespdevis;
    }

    public function setEmailrespdevis($emailrespdevis) {
        $Syntaxe = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
        if (preg_match($Syntaxe, $emailrespdevis)) {
            $this->_emailrespdevis = $emailrespdevis;
        } else {
            trigger_error(utf8_decode('L\'adresse email saisie n\'est pas valide!'));
        }
    }

    public function getReussite() {
        return $this->_reussite;
    }

    public function setReussite($reussite) {
        $this->_reussite = $reussite;
    }

    public function getSucces() {
        return $this->_succes;
    }

    public function setSucces($succes) {
        $this->_succes = $succes;
    }

    public function getFrequencedemande() {
        return $this->_frequencedemande;
    }

    public function setFrequencedemande($frequencedemande) {
        $this->_frequencedemande = $frequencedemande;
    }

    public function getRefinterneprojet() {
        return $this->_refinterneprojet;
    }

    public function setRefinterneprojet($refinterneprojet) {
        $this->_refinterneprojet = $refinterneprojet;
    }

    public function getIdtypeprojet_typeprojet() {
        return $this->_idtypeprojet_typeprojet;
    }

    public function setIdtypeprojet_typeprojet($idtypeprojet_typeprojet) {
        if (is_int($idtypeprojet_typeprojet)) {
            $this->_idtypeprojet_typeprojet = $idtypeprojet_typeprojet;
        } else {
            trigger_error(utf8_decode('La valeur pour le login n\'est pas un entier'));
        }
    }

    public function getIdthematique_thematique() {
        return $this->_idthematique_thematique;
    }

    public function setIdthematique_thematique($idthematique_thematique) {
        if (is_int($idthematique_thematique)) {
            $this->_idthematique_thematique = $idthematique_thematique;
        } else {
            trigger_error(utf8_decode('La valeur pour l\'identifiant de la thematique n\'est pas un entier'));
        }
    }

    public function getIdstatutprojet_statutprojet() {
        return $this->_idstatutprojet_statutprojet;
    }

    public function setIdstatutprojet_statutprojet($idstatutprojet_statutprojet) {
        if (is_int($idstatutprojet_statutprojet)) {
            $this->_idstatutprojet_statutprojet = $idstatutprojet_statutprojet;
        } else {
            trigger_error(utf8_decode('La valeur pour l\'identifiant du statut du projet n\'est pas un entier'));
        }
    }

    public function getIdsourcefinancement_sourcefinancement() {
        return $this->_idsourcefinancement_sourcefinancement;
    }

    public function setIdsourcefinancement_sourcefinancement($idsourcefinancement_sourcefinancement) {
        if (is_int($idsourcefinancement_sourcefinancement)) {
            $this->_idsourcefinancement_sourcefinancement = $idsourcefinancement_sourcefinancement;
        } else {
            trigger_error(utf8_decode('La valeur pour l\'identifiant de la sources de financement n\'est pas un entier'));
        }
    }

    public function getIdpersonneaccueilcentrale_personneaccueilcentrale() {
        return $this->_idpersonneaccueilcentrale_personneaccueilcentrale;
    }

    public function setIdpersonneaccueilcentrale_personneaccueilcentrale($idpersonneaccueilcentrale_personneaccueilcentrale) {
        $this->_idpersonneaccueilcentrale_personneaccueilcentrale = $idpersonneaccueilcentrale_personneaccueilcentrale;
    }

}

?>
