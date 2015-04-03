<?php

class Projetphase2 {

    private $_idprojet;
    private $_contactscentralaccueil;
    private $_idtypeprojet_typeprojet;
    private $_nbHeure;
    private $_dateDebutTravaux;
    private $_dureeprojet;
    private $_idperiodicite_periodicite;
    private $_centralepartenaireprojet;
    private $_idthematique_thematique;
    private $_idautrethematique_autrethematique;
    private $_descriptifTechnologique;
    private $_attachementdesc;
    private $_verrouidentifiee;
    private $_nbplaque;
    private $_nbrun;
    private $_envoidevis;
    private $_emailrespdevis;
    private $_reussite;
    private $_refinterneprojet;
    private $_devtechnologique;
    private $_nbeleve;
    private $_nomformateur;
    private $_partenaire1;
    private $_porteurprojet;
    private $_dureeestime;
    private $_periodestime;
    private $_descriptionautrecentrale;
    private $_etapeautrecentrale;
    private $_centraleproximite;
    private $_descriptioncentraleproximite;
    private $_interneExterne;

    public function __construct($contactscentral, $idtypeprojet, $nbHeure, $datedebut, $dureeprojet, $idperiodicite, $centralepartenaireprojet, $idthematique, $idautrethematique, $descriptifTechnologique, $attachementdesc, $verrouidentifiee, $nbplaque, $nbrun, $envoidevis, $emailrespdevis, $reussite, $refinterneprojet, $devtechnologique, $nbeleve, $nomformateur, $partenaire1, $porteurprojet, $dureeestime, $periodestime, $descriptionautrecentrale, $etapeautrecentrale, $centraleproximite, 
            $descriptioncentraleproximite,$interneExterne) {
        $this->setContactscentralaccueil($contactscentral);
        $this->setIdtypeprojet_typeprojet($idtypeprojet);
        $this->setNbHeure($nbHeure);
        $this->setDateDebutTravaux($datedebut);
        $this->setDureeprojet($dureeprojet);
        $this->setIdperiodicite_periodicite($idperiodicite);
        $this->setCentralepartenaireprojet($centralepartenaireprojet);
        $this->setIdthematique_thematique($idthematique);
        $this->setIdautrethematique_autrethematique($idautrethematique);
        $this->setDescriptifTechnologique($descriptifTechnologique);
        $this->setAttachementdesc($attachementdesc);
        $this->setVerrouidentifiee($verrouidentifiee);
        $this->setNbplaque($nbplaque);
        $this->setNbrun($nbrun);
        $this->setEnvoidevis($envoidevis);
        $this->setEmailrespdevis($emailrespdevis);
        $this->setReussite($reussite);
        $this->setRefinterneprojet($refinterneprojet);
        $this->setDevtechnologique($devtechnologique);
        $this->setNbeleve($nbeleve);
        $this->setNomformateur($nomformateur);
        $this->setPartenaire1($partenaire1);
        $this->setPorteurprojet($porteurprojet);
        $this->setDureestime($dureeestime);
        $this->setPeriodestime($periodestime);
        $this->setDescriptionautrecentrale($descriptionautrecentrale);
        $this->setEtapeautrecentrale($etapeautrecentrale);
        $this->setCentraleproximite($centraleproximite);
        $this->setDescriptioncentraleproximite($descriptioncentraleproximite);
        $this->setInterneExterne($interneExterne);
    }
    public function getInterneExterne() {
        return $this->_interneExterne;
    }

    public function setInterneExterne($interneExterne) {
        $this->_interneExterne = $interneExterne;
    }
    
    public function getDescriptioncentraleproximite() {
        return $this->_descriptioncentraleproximite;
    }

    public function setDescriptioncentraleproximite($descriptioncentraleproximite) {
        $this->_descriptioncentraleproximite = $descriptioncentraleproximite;
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getContactscentralaccueil() {
        return $this->_contactscentralaccueil;
    }

    public function setContactscentralaccueil($param) {
        $this->_contactscentralaccueil = $param;
    }

    public function getIdtypeprojet_typeprojet() {
        return $this->_idtypeprojet_typeprojet;
    }

    public function setIdtypeprojet_typeprojet($idtypeprojet) {
        $this->_idtypeprojet_typeprojet = (int) $idtypeprojet;
    }

    public function getNbHeure() {
        return $this->_nbHeure;
    }

    public function setNbHeure($nbHeure) {
        $this->_nbHeure = (int) $nbHeure;
    }

    public function getDateDebutTravaux() {
        return $this->_dateDebutTravaux;
    }

    public function setDateDebutTravaux($debuttravaux) {
        $this->_dateDebutTravaux = $debuttravaux;
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

    public function getCentralepartenaireprojet() {
        return $this->_centralepartenaireprojet;
    }

    public function setCentralepartenaireprojet($centralepartenaireprojet) {
        $this->_centralepartenaireprojet = $centralepartenaireprojet;
    }

    public function getIdthematique_thematique() {
        return $this->_idthematique_thematique;
    }

    public function setIdthematique_thematique($id) {
        if (!empty($id)) {
            $this->_idthematique_thematique = (int) $id;
        } else {
            $this->_idthematique_thematique = null;
        }
    }

    public function getIdautrethematique_autrethematique() {
        return $this->_idautrethematique_autrethematique;
    }

    public function setIdautrethematique_autrethematique($id) {
        if ($id != null) {
            $this->_idautrethematique_autrethematique = (int) $id;
        } else {
            $this->_idautrethematique_autrethematique = null;
        }
    }

    public function getDescriptifTechnologique() {
        return $this->_descriptifTechnologique;
    }

    public function setDescriptifTechnologique($descriptifTechnologique) {
        $this->_descriptifTechnologique = $descriptifTechnologique;
    }

    public function setAttachementdesc($attachementdesc) {
        $this->_attachementdesc = $attachementdesc;
    }

    public function getAttachementdesc() {
        return $this->_attachementdesc;
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
        $this->_nbplaque = (int) $nbplaque;
    }

    public function getNbrun() {
        return $this->_nbrun;
    }

    public function setNbrun($nbrun) {
        $this->_nbrun = (int) $nbrun;
    }

    public function getEmailrespdevis() {
        return $this->_emailrespdevis;
    }

    public function setEmailrespdevis($emailrespdevis) {
        $this->_emailrespdevis = $emailrespdevis;
    }

    public function getEnvoidevis() {
        return $this->_envoidevis;
    }

    public function setEnvoidevis($envoidevis) {
        $this->_envoidevis = $envoidevis;
    }

    public function getReussite() {
        return $this->_reussite;
    }

    public function setReussite($reussite) {
        $this->_reussite = $reussite;
    }

    public function getRefinterneprojet() {
        return $this->_refinterneprojet;
    }

    public function setRefinterneprojet($param) {
        $this->_refinterneprojet = $param;
    }

    public function getDevtechnologique() {
        return $this->_devtechnologique;
    }

    public function setDevtechnologique($devtechnologique) {
        $this->_devtechnologique = $devtechnologique;
    }

    public function getNbeleve() {
        return $this->_nbeleve;
    }

    public function setNbeleve($nbeleve) {
        $this->_nbeleve = (int) $nbeleve;
    }

    public function getNomformateur() {
        return $this->_nomformateur;
    }

    public function setNomformateur($nomformateur) {
        $this->_nomformateur = $nomformateur;
    }

    public function getPartenaire1() {
        return $this->_partenaire1;
    }

    public function setPartenaire1($param) {
        $this->_partenaire1 = $param;
    }

    public function getPorteurprojet() {
        return $this->_porteurprojet;
    }

    public function setPorteurprojet($porteurprojet) {
        $this->_porteurprojet = $porteurprojet;
    }

    public function getDureestime() {
        return $this->_dureeestime;
    }

    public function setDureestime($dureestime) {
        $this->_dureeestime = (int) $dureestime;
    }

    public function getPeriodestime() {
        return $this->_periodestime;
    }

    public function setPeriodestime($idperiodestime) {
        $this->_periodestime = (int) $idperiodestime;
    }

    public function getDescriptionautrecentrale() {
        return $this->_descriptionautrecentrale;
    }

    public function setDescriptionautrecentrale($param) {
        $this->_descriptionautrecentrale = $param;
    }

    public function getEtapeautrecentrale() {
        return $this->_etapeautrecentrale;
    }

    public function setEtapeautrecentrale($etapeautrecentrale) {
        $this->_etapeautrecentrale = $etapeautrecentrale;
    }

    public function getCentraleproximite() {
        return $this->_centraleproximite;
    }

    public function setCentraleproximite($param) {
        $this->_centraleproximite = $param;
    }

}
