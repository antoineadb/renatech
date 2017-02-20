<?php

class Projetcommentaire {

    private $_idprojet;
    private $_commentaire;

    public function __construct($idprojet, $commentaire) {

        $this->setIdprojet($idprojet);
        $this->setCommentaire($commentaire);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getCommentaire() {
        return $this->_commentaire;
    }

    public function setCommentaire($commentaire) {
        $this->_commentaire = $commentaire;
    }

}

?>
