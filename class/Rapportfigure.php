<?php

class Rapportfigure {
    private $_idrapport;
    private $_figure;

    public function __construct($idrapport, $figure) {
        $this->setIdprojet($idrapport);
        $this->setfigure($figure);
    }

    public function getIdprojet() {
        return $this->_idrapport;
    }

    public function setIdprojet($idrapport) {
        $this->_idrapport = (int) $idrapport;
    }

    public function getfigure() {
        return $this->_figure;
    }

    public function setfigure($figure) {
        $this->_figure = $figure;
    }
}
