<?php

class Sitewebapplication {

    private $_refsiteweb;
    private $_adressesitewebcentrale;

    function __construct($refsiteweb, $adressesitewebcentrale) {
        $this->setRefsiteweb($refsiteweb);
        $this->setAdressesitewebcentrale($adressesitewebcentrale);
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

}
