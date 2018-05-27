<?php

class Qualitedemandeurindust {

    private $_idqualitedemandeurindust;
    private $_libellequalitedemandeurindust;

    public function __construct($idqualitedemandeurindust, $libellequalitedemandeurindust) {
        $this->setIdqualitedemandeurindust($idqualitedemandeurindust);
        $this->setLibellequalitedemandeurindust($libellequalitedemandeurindust);
    }

    public function getIdqualitedemandeurindust() {
        return $this->_idqualitedemandeurindust;
    }

    public function setIdqualitedemandeurindust($idqualitedemandeurindust) {
            $this->_idqualitedemandeurindust =(int) $idqualitedemandeurindust;
    }

    public function getLibellequalitedemandeurindust() {
        return $this->_libellequalitedemandeurindust;
    }

    public function setLibellequalitedemandeurindust($libellequalitedemandeurindust) {
        $this->_libellequalitedemandeurindust = $libellequalitedemandeurindust;
    }

}