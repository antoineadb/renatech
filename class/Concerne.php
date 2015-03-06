<?php

class Concerne {

    private $_idcentrale_centrale;
    private $_idprojet_projet;
    private $_idstatutprojet_statutprojet;
    private $_commentaireProjet;

    public function __construct($idcentrale_centrale, $idprojet_projet, $idstatutprojet_statutprojet, $commentaireProjet) {
        $this->setIdcentrale_centrale($idcentrale_centrale);
        $this->setIdprojet_projet($idprojet_projet);
        $this->setIdstatutprojet_statutprojet($idstatutprojet_statutprojet);
        $this->setCommentaireProjet($commentaireProjet);
    }

    public function getIdcentrale_centrale() {
        return $this->_idcentrale_centrale;
    }

    public function setIdcentrale_centrale($id) {
        $this->_idcentrale_centrale = (int) $id;
    }

    public function getIdprojet_projet() {
        return $this->_idprojet_projet;
    }

    public function setIdprojet_projet($id) {
        $this->_idprojet_projet = (int) $id;
    }

    public function getIdstatutprojet_statutprojet() {
        return $this->_idstatutprojet_statutprojet;
    }

    public function setIdstatutprojet_statutprojet($id) {
        $this->_idstatutprojet_statutprojet = (int) $id;
    }

    public function getCommentaireProjet() {
        return $this->_commentaireProjet;
    }

    public function setCommentaireProjet($param) {
        $this->_commentaireProjet = $param;
    }

}
