<?php

class villeCentrale {

    private $_idcentrale;
    private $_villeCentrale;

    function __construct($villeCentrale, $idcentrale) {
        $this->setIdcentrale($idcentrale);
        $this->setVilleCentrale($villeCentrale);
    }

    public function getIdcentrale() {
        return $this->_idcentrale;
    }

    public function setIdcentrale($id) {
        $this->_idcentrale = (int) $id;
    }

    public function setVilleCentrale($param) { 
        $this->_villeCentrale = $param;
    }

    public function getVilleCentrale() {
        return $this->_villeCentrale;
    }

}
