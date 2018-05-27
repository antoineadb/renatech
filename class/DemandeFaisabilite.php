<?php


class DemandeFaisabilite {

    private $idDemande;
    private $nomDemandeur;
    private $emailDemandeur;
    private $objetDemande;    
    private $dateDemande;
    

    public function __construct($iddemande,$nomDemandeur,$emailDemandeur,$objetDemande,$dateDemande) {        
        $this->setIdDemande($iddemande);
        $this->setNomDemandeur($nomDemandeur);
        $this->setEmailDemandeur($emailDemandeur);
        $this->setObjetDemande($objetDemande);
        $this->setDateDemande($dateDemande);
    }
    
    
    function getIdDemande() {
        return $this->idDemande;
    }
    function getNomDemandeur() {
        return $this->nomDemandeur;
    }

    function getEmailDemandeur() {
        return $this->emailDemandeur;
    }

    function getObjetDemande() {
        return $this->objetDemande;
    }

    
    function getDateDemande(){
        return $this->dateDemande;
    }

    function setNomDemandeur($nomDemandeur) {
        $this->nomDemandeur = $nomDemandeur;
    }

    function setEmailDemandeur($emailDemandeur) {
        $this->emailDemandeur = $emailDemandeur;
    }

    function setObjetDemande($objetDemande) {
        $this->objetDemande = $objetDemande;
    }

    function setDateDemande($dateDemande){
        $this->dateDemande = $dateDemande;
    }
    function setIdDemande($idDemande){
        $this->idDemande = $idDemande;
    }
}



