<?php

class Sitewebapplication {

    private $_refsiteweb;
    private $_adressesitewebcentrale;
    private $_nomLogo;

    function __construct($refsiteweb, $adressesitewebcentrale,$nomLogo) {
        $this->setRefsiteweb($refsiteweb);
        $this->setAdressesitewebcentrale($adressesitewebcentrale);
        $this->setNomLogo($nomLogo);
    }

    public function setRefsiteweb($refsite) {
        $this->_refsiteweb = $refsite;
    }

    public function getRefsiteweb() {
        return $this->_refsiteweb;
    }

    public function getAdressesitewebcentrale() {
        return $this->_adressesitewebcentrale;
    }

    public function setAdressesitewebcentrale($site) {
        $this->_adressesitewebcentrale = $site;
    }
    
    public function getNomLogo() {
        return $this->_nomLogo;
    }

    public function setNomLogo($nom) {
        $this->_nomLogo = $nom;
    }

}
