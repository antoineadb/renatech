<?php

class Rapport {

    private $_idrapport;
    private $_title;
    private $_author;
    private $_entity;
    private $_villepays;
    private $_instituteinterest;
    private $_fundingsource;
    private $_collaborator;
    private $_thematics;
    private $_startingdate;
    private $_objectif;
    private $_results;
    private $_valorization;
    private $_technologicalwc;
    private $_logo;
    private $_logocentrale;
    private $_figure;
    private $_idprojet;

    function __construct($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo,$logocentrale, $figure, $idprojet) {
        $this->setIdrapport($idrapport);
        $this->setTitle($title);
        $this->setAuthor($author);
        $this->setEntity($entity);
        $this->setVillepays($villepays);
        $this->setInstituteinterest($instituteinterest);
        $this->setFundingsource($fundingsource);
        $this->setCollaborator($collaborator);
        $this->setThematics($thematics);
        $this->setStartingdate($startingdate);
        $this->setObjectif($objectif);
        $this->setResults($results);
        $this->setValorisation($valorization);
        $this->setTechnologicalwc($technologicalwc);
        $this->setFigure($figure);
        $this->setLogo($logo);
        $this->setLogocentrale($logocentrale);
        $this->setIdprojet($idprojet);
    }

    public function setIdrapport($idrapport) {
        $this->_idrapport = (int) $idrapport;
    }

    public function getIdrapport() {
        return $this->_idrapport;
    }

    public function setTitle($title) {
        $this->_title = $title;
    }

    public function getTitle() {
        return $this->_title;
    }

    public function setLogo($logo) {
        $this->_logo = $logo;
    }

    public function getLogo() {
        return $this->_logo;
    }
    public function setLogocentrale($logo) {
        $this->_logocentrale = $logo;
    }

    public function getLogocentrale() {
        return $this->_logocentrale;
    }

    public function setFigure($logo) {
        $this->_figure = $logo;
    }

    public function getFigure() {
        return $this->_figure;
    }

    public function setAuthor($author) {
        $this->_author = $author;
    }

    public function getAuthor() {
        return $this->_author;
    }

    public function setEntity($entity) {
        $this->_entity = $entity;
    }

    public function getEntity() {
        return $this->_entity;
    }

    public function setVillepays($param) {
        $this->_villepays = $param;
    }

    public function getVillepays() {
        return $this->_villepays;
    }

    public function setInstituteinterest($param) {
        $this->_instituteinterest = $param;
    }

    public function getInstituteinterest() {
        return $this->_instituteinterest;
    }

    public function setFundingsource($param) {
        $this->_fundingsource = $param;
    }

    public function getFundingsource() {
        return $this->_fundingsource;
    }

    public function setCollaborator($param) {
        $this->_collaborator = $param;
    }

    public function getCollaborator() {
        return $this->_collaborator;
    }

    public function setThematics($param) {
        $this->_thematics = $param;
    }

    public function getThematics() {
        return $this->_thematics;
    }

    public function setStartingdate($param) {
        $this->_startingdate = $param;
    }

    public function getStartingdate() {
        return $this->_startingdate;
    }

    public function setObjectif($param) {
        $this->_objectif = $param;
    }

    public function getObjectif() {
        return $this->_objectif;
    }

    public function setResults($param) {
        $this->_results = $param;
    }

    public function getResults() {
        return $this->_results;
    }

    public function setValorisation($param) {
        $this->_valorization = $param;
    }

    public function getValorisation() {
        return $this->_valorization;
    }

    public function setTechnologicalwc($param) {
        $this->_technologicalwc = $param;
    }

    public function getTechnologicalwc() {
        return $this->_technologicalwc;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

}
