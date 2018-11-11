<?php
class nomClass {
    private $_propriete1;
    private $_propriete2;    
        /*
     * etc...
     */
    private $_proprieten;
    public function __construct($propriete1, $propriete2, $proprieten) {
        $this->setPropriete1($propriete1);
        $this->setPropriete2($propriete2);
        $this->setProprieten($proprieten);
    }
    
    function getPropriete1() {
        return $this->_propriete1;
    }

    function getPropriete2() {
        return $this->_propriete2;
    }

    function getProprieten() {
        return $this->_proprieten;
    }

    function setPropriete1($_propriete1) {
        $this->_propriete1 = $_propriete1;
    }

    function setPropriete2($_propriete2) {
        $this->_propriete2 = $_propriete2;
    }

    function setProprieten($_proprieten) {
        $this->_proprieten = $_proprieten;
    }

    

}

