<?php

class ProjetphaseLAAS2 {

    private $_idprojet;
    private $_refinterneprojet;
    private $_devtechnologique;
    private $_dureeprojet;
    private $_idperiodicite_periodicite;
    private $_idsourcefinancement_sourcefinancement;
    private $_idthematique_thematique;
    private $_dateDebutProjet;
    private $_datestatutfini;
    

    public function __construct($idprojet, $refinterneprojet, $devtechnologique, $dureeprojet, $idperiodicite_periodicite, $idsourcefinancement_sourcefinancement, $idthematique_thematique,$dateDebutProjet,$datestatutfini) {
        $this->setIdprojet($idprojet);
        $this->setRefinterneprojet($refinterneprojet);
        $this->setDevtechnologique($devtechnologique);
        $this->setDureeprojet($dureeprojet);
        $this->setIdperiodicite_periodicite($idperiodicite_periodicite);
        $this->setIdsourcefinancement_sourcefinancement($idsourcefinancement_sourcefinancement);
        $this->setIdthematique_thematique($idthematique_thematique);
        $this->setDateDebutProjet($dateDebutProjet);
        $this->setDatestatutfini($datestatutfini);
        
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
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
        $this->_idthematique_thematique =  $id;
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

}
