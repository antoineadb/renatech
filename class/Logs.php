<?php

class Logs {

    private $_id;
    private $_dateHeure;
    private $_infos;
    private $_nomPrenom;
    private $_statutProjet;
    private $_idcentrale;

    function __construct($id, $dateHeure, $infos, $nomPrenom, $statutProjet, $idcentrale) {
        $this->setId($id);
        $this->setDateHeure($dateHeure);
        $this->setInfos($infos);
        $this->setNomPrenom($nomPrenom);
        $this->setStatutProjet($statutProjet);
        $this->setIdcentrale($idcentrale);
    }

    function getId() {
        return $this->_id;
    }

    function setId($id) {
        $this->_id = $id;
    }

    function getDateHeure() {
        return $this->_dateHeure;
    }

    function getInfos() {
        return $this->_infos;
    }

    function getStatutProjet() {
        return $this->_statutProjet;
    }

    function getIdcentrale() {
        return $this->_idcentrale;
    }

    function setIdcentrale($idcentrale) {
        $this->_idcentrale = $idcentrale;
    }

    function getNomPrenom() {
        return $this->_nomPrenom;
    }

    function setDateHeure($_dateHeure) {
        $this->_dateHeure = $_dateHeure;
    }

    function setInfos($_infos) {
        $this->_infos = $_infos;
    }

    function setNomPrenom($_nomPrenom) {
        $this->_nomPrenom = $_nomPrenom;
    }

    function setStatutProjet($statutProjet) {
        $this->_statutProjet = $statutProjet;
    }

}
