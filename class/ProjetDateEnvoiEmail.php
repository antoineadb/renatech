<?php

class ProjetDateEnvoiEmail {

    private $_dateenvoimail;
    private $_idprojet;

    public function __construct($dateenvoimail, $idprojet) {
        $this->setDateEnvoiMail($dateenvoimail);
        $this->setIdprojet($idprojet);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getDateEnvoiMail() {
        return $this->_dateenvoimail;
    }

    public function setDateEnvoiMail($dateenvoimail) {
        $this->_dateenvoimail = $dateenvoimail;
    }

}
