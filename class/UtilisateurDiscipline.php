<?php

class UtilisateurDiscipline {

    private $_idutilisateur;
    private $_iddiscipline_disciplinescientifique;
    private $_idautrediscipline_autredisciplinescientifique;

    public function __construct($idutilisateur, $iddiscipline, $idautrediscipline) {
        $this->setIdutilisateur($idutilisateur);
        $this->setIddiscipline_disciplinescientifique($iddiscipline);
        $this->setIdautrediscipline_autredisciplinescientifique($idautrediscipline);
    }

    public function getIdutilisateur() {
        return $this->_idutilisateur;
    }

    public function setIdutilisateur($id) {
        $this->_idutilisateur = (int) $id;
    }

    public function getIddiscipline_disciplinescientifique() {
        return $this->_iddiscipline_disciplinescientifique;
    }

    public function setIddiscipline_disciplinescientifique($iddiscipline_disciplinescientifique) {
        if (empty($iddiscipline_disciplinescientifique)) {
            $this->_iddiscipline_disciplinescientifique = 1;
        } else {
            $this->_iddiscipline_disciplinescientifique = (int) $iddiscipline_disciplinescientifique;
        }
    }

    public function getIdautrediscipline_autredisciplinescientifique() {
        return $this->_idautrediscipline_autredisciplinescientifique;
    }

    public function setIdautrediscipline_autredisciplinescientifique($idautrediscipline_autredisciplinescientifique) {
        if (empty($idautrediscipline_autredisciplinescientifique)) {
            $this->_idautrediscipline_autredisciplinescientifique = 1;
        } else {
            $this->_idautrediscipline_autredisciplinescientifique = (int) $idautrediscipline_autredisciplinescientifique;
        }
    }

}