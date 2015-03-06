<?php

class Projetutilisateur {

     private $_titre;
     private $_libellestatutprojet;
     private $_numero;
     private $_dateprojet;
     private $_libellecentrale;
     private $_idprojet;
     private $_description;

     public function __construct($titre, $libellestatutprojet, $numero, $dateprojet, $libellecentrale, $idprojet, $description) {
	  $this->setTitre($titre);
	  $this->setLibellestatutprojet($libellestatutprojet);
	  $this->setNumero($numero);
	  $this->setDateprojet($dateprojet);
	  $this->setLibellecentrale($libellecentrale);
	  $this->setIdprojet($idprojet);
	  $this->setdescription($description);
     }

     public function getTitre() {
	  return $this->_titre;
     }

     public function setTitre($titre) {
	  $this->_titre = $titre;
     }

     public function setLibellestatutprojet($libellestatutprojet) {
	  $this->_libellestatutprojet = $libellestatutprojet;
     }

     public function getLibellestatutprojet() {
	  return $this->_libellestatutprojet;
     }

     public function getNumero() {
	  return $this->_numero;
     }

     public function setNumero($numero) {
	  $this->_numero = $numero;
     }

     public function getDateprojet() {
	  return $this->_dateprojet;
     }

     public function setDateprojet($dateprojet) {
	  $this->_dateprojet = $dateprojet;
     }

     public function getIdprojet() {
	  return $this->_idprojet;
     }

     public function setIdprojet($id) {
	  $this->_idprojet =(int) $id;
	  
     }

     public function getDescription() {
	  return $this->_description;
     }

     public function setDescription($description) {
	  $this->_description = $description;
     }

     public function getLibellecentrale() {
	  return $this->_libellecentrale;
     }

     public function setLibellecentrale($libellecentrale) {
	  $this->_libellecentrale = $libellecentrale;
     }

}