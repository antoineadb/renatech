<?php

class PersonneCentraleQualite {
    
    private $_idpersonneQualite;
    private $_libellepersonneQualite;

    public function __construct($idpersonneQualite, $libellepersonneQualite) {
        $this->setIdpersonneQualite($idpersonneQualite);
        $this->setlibellepersonneQualite($libellepersonneQualite);
    }

    public function getIdpersonneQualite() {
        return $this->_idpersonneQualite;
    }

    public function setIdpersonneQualite($id) {
        $this->_idpersonneQualite = (int) $id;
    }

    public function getlibellepersonneQualite() {
        return $this->_libellepersonneQualite;
    }

    public function setlibellepersonneQualite($libellepersonneQualite) {
        $this->_libellepersonneQualite = $libellepersonneQualite;
    }
}
