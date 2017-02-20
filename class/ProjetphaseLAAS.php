<?php

class ProjetphaseLAAS {

    private $_idprojet;
    private $_titre;
    private $_numero;
    private $_confidentiel;
    private $_description;
    private $_dateprojet;
    private $_contexte;
    private $_idtypeprojet_typeprojet;
    private $_refinterneprojet;
    private $_devtechnologique;
    private $_dureeprojet;
    private $_idperiodicite_periodicite;
    private $_idsourcefinancement_sourcefinancement;
    private $_idthematique_thematique;
    private $_dateDebutProjet;
    private $_datestatutfini;
    private $_porteurprojet;

    function __construct($idprojet, $titre, $numero, $confidentiel, $description, $dateprojet, $contexte, $idtypeprojet_typeprojet, $refinterneprojet, $devtechnologique, $dureeprojet, $idperiodicite_periodicite, $idsourcefinancement_sourcefinancement, $idthematique_thematique, $dateDebutProjet, $datestatutfini, $porteurprojet) {
        $this->setIdprojet($idprojet);
        $this->setTitre($titre);
        $this->setNumero($numero);
        $this->setConfidentiel($confidentiel);
        $this->setDescription($description);
        $this->setDateprojet($dateprojet);
        $this->setContexte($contexte);
        $this->setIdtypeprojet_typeprojet($idtypeprojet_typeprojet);
        $this->setRefinterneprojet($refinterneprojet);
        $this->setDevtechnologique($devtechnologique);
        $this->setDureeprojet($dureeprojet);
        $this->setIdperiodicite_periodicite($idperiodicite_periodicite);
        $this->setIdsourcefinancement_sourcefinancement($idsourcefinancement_sourcefinancement);
        $this->setIdthematique_thematique($idthematique_thematique);
        $this->setDateDebutProjet($dateDebutProjet);
        $this->setDatestatutfini($datestatutfini);
        $this->setPorteurprojet($porteurprojet);
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
        $strExpression = "#^[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'/’=°()_ ,.-]+$#";
        if (preg_match($strExpression, $param)) {
            $this->_titre = $param;
        } else {
            include_once '../decide-lang.php';
            trigger_error((TXT_ERR_TITRE));
            exit();
        }
    }
    public function getTitre() {
        return $this->_titre;
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

    public function getRefinterneprojet() {
        return $this->_refinterneprojet;
    }

    public function setRefinterneprojet($param) {
        $strExpression = "#^[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'’/=°()*_ ,.-]+$#";
        if (preg_match($strExpression, $param) || empty($param)) {
            $this->_refinterneprojet = $param;
        } else {
            include_once '../decide-lang.php';
            trigger_error((TXT_ERR_REFINTERNEPROJET));
            exit();
        }
    }

    public function getDevtechnologique() {
        return $this->_devtechnologique;
    }

    public function setDevtechnologique($devtechnologique) {
        $this->_devtechnologique = $devtechnologique;
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

    public function setDateDebutProjet($debutprojet) {
        $this->_dateDebutProjet = $debutprojet;
    }

    public function getDateDebutProjet() {
        return $this->_dateDebutProjet;
    }

    public function setDatestatutfini($datestatutfini) {
        $this->_datestatutfini = $datestatutfini;
    }

    public function getDatestatutfini() {
        return $this->_datestatutfini;
    }

    public function getPorteurprojet() {
        return $this->_porteurprojet;
    }

    public function setPorteurprojet($porteurprojet) {
        $this->_porteurprojet = $porteurprojet;
    }

}
