<?php

class TypePartenaire {
    private $_idtypepartenaire;
    private $_libelletypepartenairefr;
    private $_libelletypepartenairefen;
    private $_masquetypepartenaire;

    public function __construct($idtypepartenaire,$libelletypepartenairefr,$libelletypepartenairefen,$masquetypepartenaire) {
       $this->setIdtypepartenaire($idtypepartenaire);
       $this->setLibelletypepartenairefr($libelletypepartenairefr);
       $this->setLibelletypepartenairefen($libelletypepartenairefen);
       $this->setMasquetypepartenaire($masquetypepartenaire);
    }
    
    
    
    function getIdtypepartenaire() {
        return $this->_idtypepartenaire;
    }

    function getLibelletypepartenairefr() {
        return $this->_libelletypepartenairefr;
    }

    function getLibelletypepartenairefen() {
        return $this->_libelletypepartenairefen;
    }

    function getMasquetypepartenaire() {
        return $this->_masquetypepartenaire;
    }

    function setIdtypepartenaire($idtypepartenaire) {
        $this->_idtypepartenaire = $idtypepartenaire;        
    }

    function setLibelletypepartenairefr($libelletypepartenairefr) {
        $this->_libelletypepartenairefr = $libelletypepartenairefr;
    }

    function setLibelletypepartenairefen($libelletypepartenairefen) {
        $this->_libelletypepartenairefen = $libelletypepartenairefen;
    }

    function setMasquetypepartenaire($masquetypepartenaire) {
        $this->_masquetypepartenaire = $masquetypepartenaire;
    }


}
