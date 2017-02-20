<?php

class Autrestutelle {
   
     private $_libelleautrestutelle;
     private $_idautrestutelle;

     public function __construct( $idautrestutelle,$libelleautrestutelle) {
         $this->setIdautrestutelle($idautrestutelle);
	  $this->setLibelleautrestutelle($libelleautrestutelle);
     }

      public function getIdautrestutelle() {
        return $this->_idautrestutelle;
    }

    public function setIdautrestutelle($id) {
            $this->_idautrestutelle =  (int)$id;
    }
     
     public function getLibelleautrestutelle() {
	  return $this->_libelleautrestutelle;
     }

     public function setLibelleautrestutelle($libelleautrestutelle) {
        $this->_libelleautrestutelle = $libelleautrestutelle;
     }

}