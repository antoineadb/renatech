<?php

class ClotureProjet {

    private $_datecloture;
    private $_idcentrale;

    function __construct($datecloture, $idcentrale) {
        $this->setDateCloture($datecloture);
        $this->setIdcentrale($idcentrale);
    }

    public function getDateCloture() {
        return $this->_datecloture;
    }

    public function setDateCloture($datecloture) {
        $this->_datecloture = $datecloture;
    }

    function getIdcentrale() {
        return $this->_idcentrale;
    }

    function setIdcentrale($idcentrale) {
        $this->_idcentrale = (int) $idcentrale;
    }

}
